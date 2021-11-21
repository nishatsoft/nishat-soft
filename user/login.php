<?php
include('extension/connect.php');
require "extension/config.php";
?>
<?php
    
    if (!isset($_SESSION['userid'])) {

    }else{
        header("location:dashboard");
    }
?>
<?php
$status = '';
if(isset($_POST['login'])){
  $user_name = mysqli_real_escape_string($con,$_POST['user_name']);
  $user_pass = mysqli_real_escape_string($con,$_POST['user_pass']);

  if($user_name!='' && $user_pass!=''){

  $query =mysqli_query($con,"select * from users where user_name='$user_name' and user_pass='$user_pass' and is_freeze='0' and user_rank!='normal' and user_rank!='export'");
  $qry = mysqli_fetch_array($query);
  $user_id = $qry['user_id'];
  
  if(mysqli_num_rows($query)>0){
  session_start();
  $_SESSION['id'] = session_id();
  $_SESSION['userid'] = $user_id;
  $_SESSION['login_type'] = "users";
  
  $ip = $db->get_client_ip();
  $descript = 'Login successful, IP: '.$ip.'';
  $update = mysqli_query($con,"insert into credit_log(`user_id`,`description`) values('$user_id','$descript')");
  
  echo "<script>
  window.location.href = 'dashboard';</script>";
} else {

  $status = '<p class="mt-20 mb-0 text-danger">Login Failed : Invalid username or password</p>';

}

  }
  else{

    $status = '<p class="mt-20 mb-0 text-danger">Please fill-out all forms</p>';
 
  }

}else{
    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $query = mysqli_query($con,"SELECT copyright_name from admin");
    $res = mysqli_fetch_assoc($query);
    
    $copyright_name = $res['copyright_name'];
  ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="keywords" content="HTML5 Template" />
<meta name="description" content="" />
<meta name="author" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title><?php include('extension/title.php'); ?> | Login</title>

<!-- Favicon -->
<link rel="shortcut icon" href="<?php include('extension/logo.php'); ?>" />

<!-- Font -->
<link  rel="stylesheet" href="../fonts.googleapis.com/css11b2.css?family=Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">

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
 login-->

<section class="height-100vh d-flex align-items-center page-section-ptb login" style="background-color: <?php include('extension/theme.php'); ?>;">
  <div class="container">
     <div class="row justify-content-center no-gutters vertical-align">
       <div class="col-lg-4 col-md-6 login-fancy-bg bg" style="background-image: url(/assets/images/login-inner-bg.jpg);">
         <div class="login-fancy">
            <?php
                $que1 = mysqli_query($con,"select * from application where platform='Android'");
                $row1=mysqli_fetch_array($que1);
                    $id = $row1['id'];
                    $platform = $row1['platform'];
                    $filename = $row1['filename'];
                    $dat = $row1['date'];
                                    
                    if($platform == 'Android' && $dat != '0000-00-00 00:00:00'){
                        $link1 = 'download.php?file='.$filename.'';
                    }else{
                        $link1 = '#';
                    }
                    
                $que2 = mysqli_query($con,"select * from application where platform='iOS'");
                $row2=mysqli_fetch_array($que2);
                    $id2 = $row2['id'];
                    $platform2 = $row2['platform'];
                    $filename2 = $row2['filename'];
                    $dat2 = $row2['date'];
                                    
                    if($platform2 == 'iOS' && $dat2 != '0000-00-00 00:00:00'){
                        $link2 = 'download.php?file='.$filename2.'';
                    }else{
                        $link2 = '#';
                    }
                    
                $que3 = mysqli_query($con,"select * from application where platform='Windows'");
                $row3=mysqli_fetch_array($que3);
                    $id3 = $row3['id'];
                    $platform3 = $row3['platform'];
                    $filename3 = $row3['filename'];
                    $dat3 = $row3['date'];
                                    
                    if($platform3 == 'Windows' && $dat3 != '0000-00-00 00:00:00'){
                        $link3 = 'download.php?file='.$filename3.'';
                    }else{
                        $link3 = '#';
                    }
                                    
            ?> 
          <img class="mb-10" src="<?php include('extension/logo.php'); ?>" alt="" height="100px">
          <h3 class="text-white mb-20"><?php include('extension/title.php'); ?></h3>
          <p class="mb-10 text-white">Click to download application.</p>
          <a href="<?php echo $link1; ?>"><span class="d-inline-block" data-toggle="tooltip" data-placement="right" title="<?php echo $filename; ?>"><img class="mb-10 mr-1" src="/assets/images/android.png" alt="Android Download" height="50px" style="border-radius: 50%; box-shadow: 0 1px 0 0.5px <?php include('extension/theme.php'); ?>;"></a>
          <a href="<?php echo $link2; ?>"><span class="d-inline-block" data-toggle="tooltip" data-placement="right" title="<?php echo $filename2; ?>"><img class="mb-10 mr-1" src="/assets/images/ios.png" alt="Android Download" height="50px" style="border-radius: 50%; box-shadow: 0 1px 0 0.5px <?php include('extension/theme.php'); ?>;"></a>
          <a href="<?php echo $link3; ?>"><span class="d-inline-block" data-toggle="tooltip" data-placement="right" title="<?php echo $filename3; ?>"><img class="mb-10 mr-1" src="/assets/images/windows.png" alt="Android Download" height="50px" style="border-radius: 50%; box-shadow: 0 1px 0 0.5px <?php include('extension/theme.php'); ?>;"></a>
          <ul class="list-unstyled  pos-bot pb-10">
            <li class="list-inline-item"><a class="text-white" href="terms_pub"> Terms of Use</a> </li>
            <li class="list-inline-item"><a class="text-white" href="privacy_pub"> Privacy Policy</a></li>
          </ul>
         </div> 
       </div>
       <div class="col-lg-4 col-md-6 bg-white">
        <div class="login-fancy pb-40 clearfix">
        <form method="post">
        <h3 class="mb-30">Sign In To Continue</h3>
         <div class="section-field mb-20">
             <label class="mb-10" for="user_name">Username* </label>
               <input id="user_name" class="web form-control" type="text" placeholder="Username" name="user_name">
            </div>
            <div class="section-field mb-20">
             <label class="mb-10" for="user_pass">Password* </label>
               <input id="user_pass" class="Password form-control" type="password" placeholder="Password" name="user_pass">
                 <?php echo $status; ?>
            </div>
            
            <div class="section-field">
              <button name="login" type="submit" class="btn" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">
                <span>Log in</span>
                <i class="fa fa-check"></i>
              </button>
             <p class="mt-20 mb-0"><?php include('extension/title.php'); ?> 2021</p>
          </div>
        </form>
          </div>
        </div>
      </div>
  </div>
</section>

<!--=================================
 login-->
  
</div>

<?php include('extension/protection.php'); ?>
 
<!--=================================
 jquery -->

<!-- jquery -->
<script src="/assets/js/jquery-3.3.1.min.js"></script>

<!-- plugins-jquery -->
<script src="/assets/js/plugins-jquery.js"></script>

<!-- plugin_path -->
<script>var plugin_path = '/assets/js/index.html';</script>

<!-- chart -->
<script src="/assets/js/chart-init.js"></script>

<!-- calendar -->
<script src="/assets/js/calendar.init.js"></script>

<!-- charts sparkline -->
<script src="/assets/js/sparkline.init.js"></script>

<!-- charts morris -->
<script src="/assets/js/morris.init.js"></script>

<!-- datepicker -->
<script src="/assets/js/datepicker.js"></script>

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