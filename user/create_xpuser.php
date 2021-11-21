<?php
include('extension/connect.php');
include('extension/check-login.php');
include('extension/function.php');
$admin_data = data_records($con);
$fast_loading = fast_loading($con);
$fastpro_loading = fastpro_loading($con);
$userid = $_SESSION['userid'];
$search = $userid;
$status = '';
$status2 = '';
?>

<?php
if(isset($_POST['register'])){
  $user_name = mysqli_real_escape_string($con,$_POST['username']);
  $user_pass = mysqli_real_escape_string($con,$_POST['password']);
  $auth_pass = md5($user_pass);
  $validity = mysqli_real_escape_string($con,$_POST['duration']);
  $create = 0;
  
  $query = mysqli_query($con,"select * from users where user_id='$userid'");
  $row=mysqli_fetch_array($query);
  $my_credits = $row['user_credits'];
  
    $sql = mysqli_query($con,"select * from users where user_name LIKE '$user_name%'");
    if (mysqli_num_rows($sql)>0) {
        $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                        Username is taken
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>';
    }else{
    
$kwery = mysqli_query($con,"select maintenance from admin");
$rows_kwery = mysqli_fetch_array($kwery);      
$maintenance_mode = $rows_kwery['maintenance'];
if($maintenance_mode == 0){     
    if((preg_match('/^([a-z-A-Z-0-9])+$/i', $user_name)) !== 0){    
        if($user_name!='' && $user_pass!=''){
        
            if(username_check($user_name)){
                
                if($validity == 1){
                    $user_credits = 1;
                    $duration = 43200;
                    $descript = 'deducted 1 credit for creating export user';
                }elseif($validity == 999){
                    $user_credits = 0;
                    $duration = 1440;
                    $descript = 'deducted 0 credit for creating trial export user';
                }
                
                if($my_credits>$user_credits-1){
            
                    $query = mysqli_query($con,"insert into users(`full_name`,`user_name`,`user_pass`,`user_encryptedPass`,`user_duration`,`user_upline`,`user_rank`) values('$user_name','$user_name','$user_pass','$auth_pass','$duration','$userid','export')");
                    $query2 = mysqli_query($con,"insert into radcheck(`username`,`attribute`,`op`,`value`) values('$user_name','Cleartext-Password',':=','$user_pass')");
                        if($query && $query2){
                            $qry = mysqli_query($con, "UPDATE users set user_credits = user_credits-'$user_credits' where user_id='$userid'");
                                if($qry){
                                    $update = mysqli_query($con,"insert into credit_log(`user_id`,`description`) values('$userid','$descript')");
                                        if($update){
                                            $copy = 'username:'.$user_name.' | password:'.$user_pass.'';
                                            $status = '<input type="text" value="'.$copy.'" id="myInput" style="position: absolute; top: -9999px; left: -9999px; opacity: 0;">
                                                        <div class="alert alert-success alert-dismissible" role="alert">
                                                            Account created successfully <button class="btn btn-success btn-sm" onclick="myFunction()">copy</button>
                                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>';
                                        }else{
                                            $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                                            Something went wrong
                                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>';
                                        }
                                }else{
                                    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                                    Something went wrong
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>';
                                }
                        }else{
                            $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                            Something went wrong
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>';
                        }
                  }else{
                    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    Not enough credits
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
                  }
        
              }
              else{
        
                $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                Username is taken
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>';
            
            }
        
        
        }else{
        
            $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                            Please fill-out all forms
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        
        }
    }else{
        $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                        Username is not valid.<br>
                        Only alphabets with numbers are allowed.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>';
    }
}else{
    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                    Site maintenance ongoing <br>
                    Try again later.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>';
}
}



}elseif(isset($_POST['multiregister'])){
  $count = mysqli_real_escape_string($con,$_POST['count']);
  $prefix = mysqli_real_escape_string($con,$_POST['prefix']);
  $validity = mysqli_real_escape_string($con,$_POST['duration']);
  $create = 0;
  $num = 0;
  
  $query = mysqli_query($con,"select * from users where user_id='$userid'");
  $row=mysqli_fetch_array($query);
  $my_credits = $row['user_credits'];
  
  $descript2 = 'deducted '.$count.' credits for creating multi export user';
  
  $sql = mysqli_query($con,"select * from users where user_name LIKE '$prefix%' ORDER BY user_name DESC LIMIT 1");
    
        $row_check = mysqli_fetch_array($sql) ;
$kwery = mysqli_query($con,"select maintenance from admin");
$rows_kwery = mysqli_fetch_array($kwery);      
$maintenance_mode = $rows_kwery['maintenance'];
if($maintenance_mode == 0){ 
        if($count > 1000){
            $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                            Maximum quantity exceeds the limit
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>'; 
        }else{
          if((preg_match('/^([a-z])+$/i', $prefix)) !== 0){   
          if($count!='' && $prefix!=''){
                
                if($my_credits>$count-1){
                    
                    $str = $row_check["user_name"];//"IVISA250532";
                    $int = preg_replace('/[^0-9]/', '',$str);//to get the Int from string '250532'
                    if (mysqli_num_rows($sql)>0) {
                        $pure_str = str_replace($int, "", $str);// tog get only the string word 'IVISA'
                    }else{
                        $pure_str = $prefix;
                    }
                    
                    while($num<$count)
                    {
                        $num++;
                        $user_credits = $count;
                        $ran_name = rand(0,999919);
                        $ran_name2 = rand(0,9999199);
                        $user_name = $prefix.$ran_name;
                        $user_pass	= $ran_name2;
                        $authpass = md5($user_pass);
                        $duration = 43200;
                        $int++;
                        $next_user_name = $pure_str.($int+1);
                    $query = mysqli_query($con,"insert into users(`full_name`,`user_name`,`user_pass`,`user_encryptedPass`,`user_duration`,`user_upline`,`user_rank`) values('$next_user_name','$next_user_name','$user_pass','$authpass','$duration','$userid','export')");
                    $query2 = mysqli_query($con,"insert into radcheck(`username`,`attribute`,`op`,`value`) values('$next_user_name','Cleartext-Password',':=','$user_pass')");
                    }
                    if($query && $query2){
                        $qry = mysqli_query($con, "UPDATE users set user_credits = user_credits-'$user_credits' where user_id='$userid'");
                            if($qry){
                                $update = mysqli_query($con,"insert into credit_log(`user_id`,`description`) values('$userid','$descript2')");
                                    if($update){
                                        $status2 = '<div class="alert alert-success alert-dismissible" role="alert">
                                                        '.$count.' accounts registered successfully!
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>';
                                    }else{
                                        $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                                                        Something went wrong
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>';
                                    }
                            }else{
                                $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                                                Something went wrong
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>';
                            }
                    }else{
                        $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                                        Something went wrong
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>';
                    }
                }else{
        
                    $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    Not enough credits
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
                  }
        
          }
          else{
        
            $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                            Please fill-out all forms
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        
          }
          }else{
             $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                            Prefix is not valid.<br>
                            Only alphabets are allowed.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>'; 
          }
        }
}else{
    $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                    Site maintenance ongoing <br>
                    Try again later.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>';
}
}
?>

<?php 
function username_check($user_name){
  global $con;
  
  $query =mysqli_query($con,"select * from users where user_name='$user_name'");
  if(mysqli_num_rows($query)>0){
    return false;
  }
  else{
    return true;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="keywords" content="<?php include('extension/title.php'); ?>" />
<meta name="description" content="<?php include('extension/title.php'); ?> - VPN Panel System" />
<meta name="author" content="<?php include('extension/title.php'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title><?php include('extension/title.php'); ?> | Create Export</title>
    
<script src="/assets/js/jquery-3.3.1.min.js"></script>

<!-- Favicon -->
<link rel="shortcut icon" href="<?php include('extension/logo.php'); ?>" />

<!-- Font -->
<link  rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">

<!-- css -->
<link rel="stylesheet" type="text/css" href="/assets/css/style.css" />

</head>

<body>
<?php
    $cred = mysqli_query($con,"select user_credits, user_rank from users where user_id='$userid'");
    $myinfo = mysqli_fetch_array($cred);
    $credit = $myinfo['user_credits'];
    
    if($credit == 0 && $myinfo['user_rank']!='superadmin'){
        $box_bg = 'red-bg';
    }else{
        $box_bg = 'green-bg';
    }
    
    if($myinfo['user_rank'] == 'superadmin'){
        $admincred = 'Unlimited';
    }else{
        $admincred = $credit;
    }
    
    if($myinfo['user_rank']=='superadmin'){
        $que = mysqli_query($con,"select * from users where user_rank='export'");
    }else{
        $que = mysqli_query($con,"select * from users where user_rank='export' and user_upline='$userid'");
    }
    
    $exp_user = mysqli_num_rows($que);
    
    if($exp_user == 0){
        $res_bg = 'red-bg';
    }else{
        $res_bg = 'green-bg';
    }
?>
<div class="wrapper">

<!--=================================
 preloader -->
 
<div id="pre-loader">
    <img src="/assets/images/pre-loader/loader-01.svg" alt="">
</div>

<!--=================================
 preloader -->


<!--=================================
 header start-->
 
<?php include('extension/topnav.php'); ?>

<!--=================================
 header End-->

<!--=================================
 Main content -->
 
<div class="container-fluid">
  <div class="row">
    <?php include('extension/sidenav.php'); ?>

<!-- main content wrapper start-->

    <div class="content-wrapper">
      <div class="page-title">
        <div class="row">
          <div class="col-sm-6">
                <h4 class="mb-0"> Create Export User</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Create Export User</li>
            </ol>
          </div>
        </div>
      </div>
    
   <div class="row">
    <div class="col-xl-4 mb-30">
          <div class='card card-statistics <?php echo $res_bg; ?> h-100' >
            <div class="card-body">
              <div class="clearfix">
                <div class="float-left icon-box-fixed">
                  <span class="text-white">
                    <i class="fa fa-users highlight-icon" aria-hidden="true"></i>
                  </span>
                </div>
                <div class="float-right text-right">
                  <h4 class="text-white"><?php echo $exp_user; ?></h4>
                  <p class="card-text text-white">Export Users  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
       
       <div class="col-xl-4 mb-30">
          <div class='card card-statistics <?php echo $box_bg; ?> h-100' >
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left icon-box-fixed">
                      <span class="text-white">
                        <i class="fa fa-dollar highlight-icon" aria-hidden="true"></i>
                      </span>
                    </div>
                    <div class="float-right text-right">
                      <h4 class="text-white"><?php echo $admincred; ?></h4>
                      <p class="card-text text-white">Credit(s)  </p>
                    </div>
                  </div>
                </div>
              </div>
        </div>
  </div>
        
    <div class="row">
        
        <div class="col-xl-6 mb-30">
            <div class="card card-statistics mb-30">
                  <div class="card-body">
                    <h5 class="card-title">Create Single Export User</h5>
                    <?php echo $status; ?>
                    <form method="post" id="createSingleUserForm" class="ui grid form">
                        
                        <div class="row field">
                          <label class="twelve wide column" for="username">Username</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input type="text" id="username" name="username" aria-describedby="username" placeholder="Enter username" autocomplete="off" required>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="password">Password</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input type="text" id="password" name="password" aria-describedby="password" placeholder="Enter password" autocomplete="off" required>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="duration">Duration</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <select class="custom-select custom-select-lg mb-3" id="duration" name="duration">
                                <option selected value="1">1 Month</option>
                                <option value="999">Trial Account</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <label class="twelve wide column"></label>
                          <div class="twelve wide column">
                            <button type="submit" name="register" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Create Single</button>
                          </div>
                        </div>
                        
                    </form>

                </div>
            </div>
     </div>
        
     <div class="col-xl-6 mb-30">
         
            <div class="card card-statistics mb-30">
                  <div class="card-body">
                    <h5 class="card-title">Create Multiple Export Users</h5>
                    <?php echo $status2; ?>
                    <form method="post" id="createMultiUserForm" class="ui grid form">
                        
                        <div class="row field">
                          <label class="twelve wide column" for="count">No. Of Users</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                                <input type="number" min="1" max="1000" class="form-control" id="count" name="count" aria-describedby="count" placeholder="Number of users" autocomplete="off" required>
                                <small class="form-text text-muted">Number of users to create.</small>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="prefix">Username Prefix</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                                <input type="text" pattern="[a-zA-Z]+" class="form-control" id="prefix" name="prefix" aria-describedby="prefix" placeholder="Enter username prefix" autocomplete="off" required>
                                <small class="form-text text-muted">Usernames will start with prefix.</small>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="duration">Duration</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <select class="custom-select custom-select-lg mb-3" id="duration" name="duration">
                                <option selected value="1">1 Month</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <label class="twelve wide column"></label>
                          <div class="twelve wide column">
                            <button type="submit" name="multiregister" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Create Multi</button>
                          </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
   </div>
        
<!--=================================
 wrapper -->
      
<!-- main content wrapper end-->
        
    <?php include('extension/footer.php'); ?>
    </div>

</div>
 </div>
</div>

<!--=================================
 footer -->



<!--=================================
 jquery -->

<!-- jquery -->
<script src="/assets/js/jquery-3.3.1.min.js"></script>

<!-- plugins-jquery -->
<script src="/assets/js/plugins-jquery.js"></script>

<!-- plugin_path -->
<script>var plugin_path = 'js/';</script>

<!-- chart -->
<script src="/assets/js/chart-init.js"></script>

<!-- calendar -->
<script src="/assets/js/calendar.init.js"></script>

<!-- charts sparkline -->
<script src="/assets/js/sparkline.init.js"></script>

<!-- charts morris -->
<script src="/assets/js/morris.init.js"></script>

<!-- sweetalert2 -->
<script src="/assets/js/sweetalert2.js"></script>

<!-- toastr -->
<script src="/assets/js/toastr.js"></script>

<!-- validation -->
<script src="/assets/js/validation.js"></script>

<!-- lobilist -->
<script src="/assets/js/lobilist.js"></script>
 
<!-- custom -->
<script src="/assets/js/custom.js"></script>

<script>
function generate_user(length) {
    var chars = '0123456789',
        r1="",
        result=""
    for (var i = length; i > 0; --i)
        r1 += chars[Math.round(Math.random() * (chars.length - 1))],
        result = r1
    return result
}

$(document).ready( function () {
$('#username').val(generate_user(7));
$('#password').val(generate_user(7));
});

function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

  /* Copy the text inside the text field */
  document.execCommand("copy");

  /* Alert the copied text */
  alert("Copied: " + copyText.value);
}
</script>

</body>
</html>
