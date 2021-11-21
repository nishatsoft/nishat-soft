<?php
include('extension/connect.php');
include('extension/check-login.php');
include('extension/function.php');
include('extension/rank_check.php');
$admin_data = data_records($con);
$fast_loading = fast_loading($con);
$fastpro_loading = fastpro_loading($con);
$userid = $_SESSION['userid'];
$search = $userid;
$status = '';
$type1 = '';
$type2 = '';
$type3 = '';
$uid = mysqli_real_escape_string($con,$_GET['uid']);
?>

<?php
if(isset($_POST['updatenotice'])){
  $download_id = $uid;
  $download_title = mysqli_real_escape_string($con,$_POST['notice_title']);
  $dmsg = $_POST['noticemsg'];
  $download_type = mysqli_real_escape_string($con,$_POST['category']);
  
  if($download_title!='' && $dmsg!=''){
      
    $query = mysqli_query($con,"UPDATE download SET download_title='$download_title', download_msg='$dmsg', download_type='$download_type' WHERE id='$download_id'");
        if($query){
            $status = '<div class="alert alert-success alert-dismissible" role="alert">
                            [Notice] updated successfully
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
                    Please fill-out all forms
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>';

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
<title><?php include('extension/title.php'); ?> | Edit Notice</title>
    
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
                <h4 class="mb-0"> Edit Notice</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Edit Notice</li>
            </ol>
          </div>
        </div>
      </div>
        
  <div class="row">
        
        <div class="col-xl-6 mb-30">
            <div class="card card-statistics mb-30">
                  <div class="card-body">
                    <h5 class="card-title">Edit Notice</h5>
                    <?php echo $status; ?>
                    <form method="post" class="ui grid form">
                        <?php
                            $query = mysqli_query($con,"select * from download where id='$uid'");
                            $row=mysqli_fetch_array($query);
                                $id = $row['id'];
                                $download_title = $row['download_title'];
                                $download_msg = $row['download_msg'];
                                $download_type = $row['download_type'];
                                
                                if($download_type == 'Notice'){
                                    $type1 = 'selected';
                                }elseif($download_type == 'Update'){
                                    $type2 = 'selected';
                                }elseif($download_type == 'Warning'){
                                    $type3 = 'selected';    
                                }
                        ?>
                        <div class="row field">
                            <label class="twelve wide column" for="notice_title">Title</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input id="notice_title" name="notice_title" type="text" placeholder="Enter title" value="<?php echo $download_title; ?>" autocomplete="off" required/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="category">Category</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <select class="custom-select custom-select-lg mb-3" id="category" name="category">
                                        <option value="Notice" <?php echo $type1; ?>>Notice</option>
                                        <option value="Update" <?php echo $type2; ?>>Update</option>
                                        <option value="Warning" <?php echo $type3; ?>>Warning</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <textarea id="noticemsg" name="noticemsg"><?php echo $download_msg; ?></textarea>
                        
                        <div class="row">
                          <label class="twelve wide column"></label>
                          <div class="twelve wide column">
                            <button type="submit" name="updatenotice" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Submit</button>
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
<script src="/assets/js/custom_.js"></script>
  
</body>
</html>
