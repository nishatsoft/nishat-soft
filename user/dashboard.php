<?php
include('extension/connect.php');
include('extension/check-login.php');
include('extension/function.php');
$admin_data = data_records($con);
$fast_loading = fast_loading($con);
$fastpro_loading = fastpro_loading($con);
$userid = $_SESSION['userid'];
$search = $userid;

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
<title><?php include('extension/title.php'); ?> | Dashboard</title>
    
<script src="js/jquery-3.3.1.min.js"></script>

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

<?php
    $query = mysqli_query($con,"select * from users where user_id='$userid'");
    $result = mysqli_fetch_array($query);
    
    if($result['user_rank'] == 'superadmin'){
        $credits = '&#8734;';
        $cred_details = 'Unlimited Credits';
    }else{
        $credits = $result['user_credits'];
        $cred_details = 'Available Credit(s)';
    }
    
    if($result['user_credits'] > 0){
        $icon = 'text-success';
    }else{
        $icon = 'text-danger';
    }
    
    if($result['user_rank']=='superadmin'){
        $que3 = mysqli_query($con,"select * from users where user_rank='export'");
    }else{
        $que3 = mysqli_query($con,"select * from users where user_rank='export' and user_upline='$userid'");
    }
    $exp = mysqli_num_rows($que3);
    
    if($exp == 0){
        $desc_exp = 'You have no  export users!';
    }else{
        $desc_exp = 'Total export user(s)';
    }
    
    if($result['user_rank']=='superadmin'){
        $que2 = mysqli_query($con,"select * from users where user_rank='reseller'");
    }else{
        $que2 = mysqli_query($con,"select * from users where user_rank='reseller' and user_upline='$userid'");
    }
    $rese = mysqli_num_rows($que2);
    
    if($rese == 0){
        $desc_reseller = 'You have no resellers!';
    }else{
        $desc_reseller = 'Total reseller(s)';
    }
    
    if($result['user_rank']=='superadmin'){
        $que = mysqli_query($con,"select * from users where user_rank='normal'");
    }else{
        $que = mysqli_query($con,"select * from users where user_rank='normal' and user_upline='$userid'");
    }
    $client = mysqli_num_rows($que);
    
    if($client == 0){
        $desc = 'You have no users!';
    }else{
        $desc = 'Total user(s)';
    }
    
    if($result['user_rank']=='superadmin'){
        $que4 = mysqli_query($con,"select * from users where is_active='1'");
    }else{
        $que4 = mysqli_query($con,"select * from users where is_active='1' and user_upline='$userid'");
    }
    $onl = mysqli_num_rows($que4);
    
    if($onl == 0){
        $desc_online = 'You have no users online!';
    }else{
        $desc_online = 'Total user(s) online';
    }
?>
 
<div class="container-fluid">
  <div class="row">
    
    <?php include('extension/sidenav.php'); ?>
    
<!-- main content wrapper start-->

    <div class="content-wrapper">
      <div class="page-title">
        <div class="row">
          <div class="col-sm-6">
                <?php 
                $kwery = mysqli_query($con,"select maintenance from admin");
                $rows_kwery = mysqli_fetch_array($kwery);      
                $maintenance_mode = $rows_kwery['maintenance'];
                if($maintenance_mode == 1){ ?>
                <h4 class="mb-0">🔧 UNDER MAINTENANCE 🔧 </h4>
                <?php }else{ ?>
                <h4 class="mb-0"> Dashboard</h4>
                <?php } ?>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="index.html" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
        
      <!-- widgets -->
      
      <div class="row">
        
        <div class="col-xl-6 col-lg-6 col-md-6 mb-30">
          <div class="card card-statistics h-100" style="background-color: <?php include('extension/theme.php'); ?>;">
            <div class="card-body">
            <a href="view_resellers">
              <div class="clearfix">
                <div class="float-left">
                  <span class="text-danger">
                    <i class="fa fa-users highlight-icon" aria-hidden="true" style="color: <?php include('extension/theme_text.php'); ?>;"></i>
                  </span>
                </div>
                <div class="float-right text-right">
                  <p class="card-text" style="color: <?php include('extension/theme_text.php'); ?>;">Online</p>
                  <h4 style="color: <?php include('extension/theme_text.php'); ?>;"><?php echo $onl; ?></h4>
                </div>
              </div>
              <p class="pt-3 mb-0 mt-2 border-top" style="color: <?php include('extension/theme_text.php'); ?>;">
                <i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> 
                  <?php echo $desc_online; ?>             
              </p>
            </a>
            </div>
          </div>
        </div>
        
        <div class="col-xl-6 col-lg-6 col-md-6 mb-30">
              <div class="card card-statistics h-100" style="background-color: <?php include('extension/theme.php'); ?>;">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <span class="<?php echo $icon; ?>">
                        <i class="fa fa-dollar highlight-icon" aria-hidden="true" style="color: <?php include('extension/theme_text.php'); ?>;"></i>
                      </span>
                    </div>
                    <div class="float-right text-right">
                      <p class="card-text" style="color: <?php include('extension/theme_text.php'); ?>;">Credits</p>
                      <h4 style="color: <?php include('extension/theme_text.php'); ?>;"><?php echo $credits; ?></h4>
                    </div>
                  </div>
                  <p class="pt-3 mb-0 mt-2 border-top" style="color: <?php include('extension/theme_text.php'); ?>;">
                    <i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> 
                      <?php echo $cred_details; ?>                  </p>
                </div>
              </div>
        </div>
        
        <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
          <div class="card card-statistics h-100" style="background-color: <?php include('extension/theme.php'); ?>;">
            <div class="card-body">
            <a href="view_resellers">
              <div class="clearfix">
                <div class="float-left">
                  <span class="text-danger">
                    <i class="fa fa-users highlight-icon" aria-hidden="true" style="color: <?php include('extension/theme_text.php'); ?>;"></i>
                  </span>
                </div>
                <div class="float-right text-right">
                  <p class="card-text" style="color: <?php include('extension/theme_text.php'); ?>;">Resellers</p>
                  <h4 style="color: <?php include('extension/theme_text.php'); ?>;"><?php echo $rese; ?></h4>
                </div>
              </div>
              <p class="pt-3 mb-0 mt-2 border-top" style="color: <?php include('extension/theme_text.php'); ?>;">
                <i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> 
                  <?php echo $desc_reseller; ?>             
              </p>
            </a>
            </div>
          </div>
        </div>
       
        
        <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
          <div class="card card-statistics h-100" style="background-color: <?php include('extension/theme.php'); ?>;">
            <div class="card-body">
              <a href="view_users">
              <div class="clearfix">
                <div class="float-left">
                  <span class="text-danger">
                    <i class="fa fa-users highlight-icon" aria-hidden="true" style="color: <?php include('extension/theme_text.php'); ?>;"></i>
                  </span>
                </div>
                <div class="float-right text-right">
                  <p class="card-text" style="color: <?php include('extension/theme_text.php'); ?>;">Users</p>
                  <h4 style="color: <?php include('extension/theme_text.php'); ?>;"><?php echo $client; ?></h4>
                </div>
              </div>
              <p class="pt-3 mb-0 mt-2 border-top" style="color: <?php include('extension/theme_text.php'); ?>;">
                <i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> 
                  <?php echo $desc; ?>
              </p>
            </a>
            </div>
          </div>
        </div>

          <div class="col-xl-4 col-lg-6 col-md-6 mb-30">
          <div class="card card-statistics h-100" style="background-color: <?php include('extension/theme.php'); ?>;">
            <div class="card-body">
            <a href="view_xpusers">
              <div class="clearfix">
                <div class="float-left">
                  <span class="text-danger">
                    <i class="fa fa-address-card highlight-icon" aria-hidden="true" style="color: <?php include('extension/theme_text.php'); ?>;"></i>
                  </span>
                </div>
                <div class="float-right text-right">
                  <p class="card-text" style="color: <?php include('extension/theme_text.php'); ?>;">Export Users</p>
                  <h4 style="color: <?php include('extension/theme_text.php'); ?>;"><?php echo $exp; ?></h4>
                </div>
              </div>
              <p class="pt-3 mb-0 mt-2 border-top" style="color: <?php include('extension/theme_text.php'); ?>;">
                <i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i> 
                  <?php echo $desc_exp; ?>          
              </p>
                </a>
            </div>
          </div>
        </div>
        
            <div class="col-xl-12 mb-10">
                <div class="card card-statistics mb-30">
                    <div class="card-body">
                        <h5 class="card-title">Notice & Updates</h5>
                            <div class="scrollbar max-h-600">
                                <ul class="list-unstyled">
                                    <?php
                                        $i=1;
                                        $dquery = mysqli_query($con,"select * from download order by download_date desc");
                                        if(mysqli_num_rows($dquery)>0){
                                            while($drow=mysqli_fetch_array($dquery)){
                                                $id = $drow['id'];
                                                $download_title = $drow['download_title'];
                                                $download_msg = $drow['download_msg'];
                                                $download_type = $drow['download_type'];
                                                $download_date = $drow['download_date'];
                                    ?>
                                    <li class="pt-15 bg-light">
                                        <div class="media px-2">
                                            <!--div class="position-relative clearfix">
                                                <img class="img-fluid mr-15 avatar-small" src="<?php include('extension/logo.php'); ?>" alt="">
                                            </div--> 
                                            <div class="media-body">
                                                <small class="float-right"><?php echo $download_date; ?></small> <span class="badge badge-pill" style="background-color: <?php include('extension/theme.php'); ?>; color: <?php include('extension/theme_text.php'); ?>;"><?php echo $download_type; ?></span>
                                                <h6 class="mt-0 "><?php echo $download_title; ?></h6>
                                                <p class="text-muted"><?php echo $download_msg; ?></p>
                                            </div>
                                        </div>
                                        <div class="divider mt-15"></div>
                                    </li>
                                    <?php
                                        $i++;
                                        }
                                    }else{
                                    ?>
                                    <?php } ?>
                                </ul>
                            </div>
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
<script>var plugin_path = '/assets/js/';</script>

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
