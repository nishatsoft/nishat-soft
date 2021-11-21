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
?>

<?php
if(isset($_POST['register'])){
  $user_name = mysqli_real_escape_string($con,$_POST['username']);
  $user_pass = mysqli_real_escape_string($con,$_POST['password']);
  $auth_pass = md5($user_pass);
  $user_credits = mysqli_real_escape_string($con,$_POST['credits']);
  
  $query = mysqli_query($con,"select * from users where user_id='$userid'");
  $row=mysqli_fetch_array($query);
  $user_name_2 = $row['user_name'];
  $my_credits = $row['user_credits'];
  
  $descript = 'deducted '.$user_credits.' credit(s) for creating reseller';
  
$kwery = mysqli_query($con,"select maintenance from admin");
$rows_kwery = mysqli_fetch_array($kwery);      
$maintenance_mode = $rows_kwery['maintenance'];
if($maintenance_mode == 0){ 
  if((preg_match('/^([a-z])+$/i', $user_name)) !== 0){ 
  if($user_name!='' && $user_pass!='' && $user_credits!=''){

      if(username_check($user_name)){
        
        if($my_credits>$user_credits-1){
    
            $query = mysqli_query($con,"insert into users(`full_name`,`user_name`,`user_pass`,`user_encryptedPass`,`user_credits`,`user_upline`,`user_rank`) values('$user_name','$user_name','$user_pass','$auth_pass','$user_credits','$userid','reseller')");
                if($query){
                    $qry = mysqli_query($con, "UPDATE users set user_credits = user_credits-'$user_credits' where user_id='$userid'");
                        if($qry){
                            $update = mysqli_query($con,"insert into credit_log(`user_id`,`description`) values('$userid','$descript')");
                            $update_ = mysqli_query($con,"insert into credits(`sender`,`receiver`,`amount`,`type`) values('$user_name_2','$user_name','$user_credits','add')");
                                if($update){
                                    $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                                    Account created successfully
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


  }
  else{

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
                        Only alphabets are allowed.
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
<title><?php include('extension/title.php'); ?> | Create Reseller</title>
    
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
        $que = mysqli_query($con,"select * from users where user_rank='reseller'");
    }else{
        $que = mysqli_query($con,"select * from users where user_rank='reseller' and user_upline='$userid'");
    }
    $reseller = mysqli_num_rows($que);
    
    if($reseller == 0){
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
                <h4 class="mb-0"> Create Reseller</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Create Reseller</li>
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
                  <h4 class="text-white"><?php echo $reseller; ?></h4>
                  <p class="card-text text-white">Reseller  </p>
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
                    <h5 class="card-title">Create Reseller</h5>
                    <?php echo $status; ?>
                    <form method="post" id="createResellerForm" class="ui grid form">
                        
                        <div class="row field">
                          <label class="twelve wide column" for="username">Username</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="username" name="username" type="text" placeholder="Enter username" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="password">Password</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="password" name="password" type="text" placeholder="Enter password" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="password">Credits</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="credits" min="1" name="credits" type="number" placeholder="Enter credits" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <label class="twelve wide column"></label>
                          <div class="twelve wide column">
                            <button type="submit" name="register" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Create Reseller</button>
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
  
</body>
</html>
