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
if(isset($_POST['passchange'])){
  $user_pass = mysqli_real_escape_string($con,$_POST['password']);
  $user_pass1 = mysqli_real_escape_string($con,$_POST['password1']);
  $user_pass2 = mysqli_real_escape_string($con,$_POST['password2']);
  $auth_pass = md5($user_pass1);
  
  $query =mysqli_query($con,"select user_pass from users where user_id='$userid'");
  $rw = mysqli_fetch_array($query);
  
  if($user_pass!='' && $user_pass1!='' && $user_pass2!=''){

      if($rw['user_pass'] == $user_pass){
        
        if($user_pass1 == $user_pass2){
    
            $query = mysqli_query($con,"UPDATE users set user_pass='$user_pass1', user_encryptedPass='$auth_pass' where user_id='$userid'");
                if($query){
                    $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                    Password changed successfully
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
                }else{
                    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    Password changed failed
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
                }
        
          }else{

            $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    Password not matched
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
          }

      }
      else{

        $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    Old password is incorrect
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
    
    }


  }
  else{

    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    Please fill all forms
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';

  }

}elseif(isset($_POST['passchange_'])){
    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                    Changing password is disabled in Demo
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>';
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
<title><?php include('extension/title.php'); ?> | Change Password</title>
    
<script src="/assets/js/jquery-3.3.1.min.js"></script>

<!-- Favicon -->
<link rel="shortcut icon" href="<?php include('extension/logo.php'); ?>" />

<!-- Font -->
<link  rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">

<!-- css -->
<link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
</head>

<body>

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
                <h4 class="mb-0"> Change Password</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Change Password</li>
            </ol>
          </div>
        </div>
      </div>
 
  <div class="row">
        
        <div class="col-xl-6 mb-30">
            <div class="card card-statistics mb-30">
                  <div class="card-body">
                    <h5 class="card-title">Change Password</h5>
                    <?php echo $status; ?>
                    <form method="post" id="changePasswordForm" class="ui grid form">
                        
                        <div class="row field">
                          <label class="twelve wide column" for="password">Current Password</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="password" name="password" type="text" placeholder="Enter current password" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="password1">New Password</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="password1" name="password1" type="text" placeholder="Enter new password" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="password2">Retype Password</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="password2" name="password2" type="text" placeholder="Retype new password" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <label class="twelve wide column"></label>
                          <div class="twelve wide column">
                            <button type="submit" name="passchange" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Change Password</button>
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
<script src="js/sparkline.init.js"></script>

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
 
<?php include('extension/toast.php'); ?>
  
</body>
</html>
