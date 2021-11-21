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
if(isset($_POST['transfer'])){
  $user_name = mysqli_real_escape_string($con,$_POST['username']);
  $user_credits = mysqli_real_escape_string($con,$_POST['credits']);
  
  $query = mysqli_query($con,"select * from users where user_id='$userid'");
  $row=mysqli_fetch_array($query);
  $my_credits = $row['user_credits'];
  $user_name_2 = $row['user_name'];
  
  $qry = mysqli_query($con,"select * from users where user_name='$user_name'");
  $row2=mysqli_fetch_array($qry);
  $userid_2 = $row2['user_id'];
  
  $descript = 'transferred '.$user_credits.' credit(s) to '.$user_name.'';
  $descript2 = 'received '.$user_credits.' credit(s) from '.$user_name_2.'';
if($user_name!='' && $user_credits!=''){
    
    if(username_check($user_name)){
        if(rank_check($user_name)){
            
            if($my_credits>$user_credits-1){
        
                $query = mysqli_query($con,"UPDATE users set user_credits = user_credits+'$user_credits' where user_name='$user_name' and user_rank='reseller'");
                    if($query){
                        $qry = mysqli_query($con, "UPDATE users set user_credits = user_credits-'$user_credits' where user_id='$userid'");
                        $update2 = mysqli_query($con,"insert into credit_log(`user_id`,`description`) values('$user_name_2','$descript2')");
                            if($qry){
                                $update = mysqli_query($con,"insert into credit_log(`user_id`,`description`) values('$userid','$descript')");
                                $update_ = mysqli_query($con,"insert into credits(`sender`,`receiver`,`amount`,`type`) values('$user_name_2','$user_name','$user_credits','add')");
                                    if($update && $update_){
                                        $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                                        Transferred successfully
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
    
        }else{
            $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                            User is not a reseller
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        }
    }else{
        $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                        User not found
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

}
?>

<?php 
function username_check($user_name){
  global $con;
  
  $query =mysqli_query($con,"select * from users where user_name='$user_name'");
  if(mysqli_num_rows($query)>0){
    return true;
  }
  else{
    return false;
  }
}

function rank_check($user_name){
  global $con;
  
  $query =mysqli_query($con,"select * from users where user_name='$user_name' and user_rank='reseller'");
  if(mysqli_num_rows($query)>0){
    return true;
  }else{
    return false;
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
<title><?php include('extension/title.php'); ?> | Transfer Credits</title>
    
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
    
    $que = mysqli_query($con,"select user_name from users where user_rank='reseller' and user_upline='$userid'");
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
    <!-- Left Sidebar End-->

<!-- main content wrapper start-->

    <div class="content-wrapper">
      <div class="page-title">
        <div class="row">
          <div class="col-sm-6">
                <h4 class="mb-0"> Transfer Credits</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Transfer Credit</li>
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
                    <h5 class="card-title">Transfer Credit</h5>
                    <?php echo $status; ?>
                    <form method="post" id="creditResellerForm" class="ui grid form">
                        
                        <div class="row field">
                          <label class="twelve wide column" for="username">Username</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="username" name="username" type="text" placeholder="Enter username" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="credits">No. Of Credits</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input type="number" min="1" max="1000" class="form-control" id="credits" name="credits" placeholder="Number of credits" autocomplete="off" required>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <label class="twelve wide column"></label>
                          <div class="twelve wide column">
                            <button type="submit" name="transfer" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Transfer Credit</button>
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
