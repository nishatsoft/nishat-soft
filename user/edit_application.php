<?php
include('extension/connect.php');
include('extension/check-login.php');
include('extension/function.php');
include('extension/rank_check.php');
include "extension/mysql.class.php";

ini_set('post_max_size','1024M');
ini_set('upload_max_filesize','1024M');
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
if(isset($_POST['updateapp'])){
  $app_id = $uid;
  
  $uploadOk = 1;
  $dirpath = "../_uploads/";
  
  if(is_dir($dirpath) == false)
  {
    mkdir($dirpath, 0777, true) or die('Error: ');
  }
  
    if(!empty( $_FILES['file'] )){
        $orginial = basename($_FILES['file']['name']);
		$file_name = $_FILES['file']['name'];
		$ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
		$target_file = time().'.'.$ext;
		$tmp_name = $_FILES['file']['tmp_name'];
		$file_size = $_FILES['file']['size'];
		$max_size = 10 * 1024 * 1024;
			if (file_exists($target_file)) {
				$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                File already exist
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>';
				$uploadOk = 0;
			}
					
			$allowedExts = array("zip", "rar", "exe", "msi", "apk", "ipa");
				if(in_array($ext, $allowedExts)){
					$uploadOk = 1;
				}else{
					$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    '.$orginial.' exceeds max '.$max_size.' KB size or incorrect file extension only APK, EXE, MSI, ZIP, RAR files are allowed.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
					$uploadOk = 0;
				}
					
					if($uploadOk == 1)
					{
						$upl = move_uploaded_file($tmp_name, $dirpath . $orginial);
							
							if($upl){
							
							$chk_files = mysqli_query($con,"SELECT * FROM application WHERE id = '$app_id'");
							while($rows = mysqli_fetch_array($chk_files))
							{
								if($rows['filename'] == ''){
								}else{
									unlink($dirpath . $rows['filename']);	
								}
							}
							
    							$update = mysqli_query($con,"update application SET filename='$orginial', download_count='0', date=NOW() WHERE id='$app_id'");
    							if($update)
    							{
    								$status = '<div class="alert alert-success alert-dismissible" role="alert">
                                        Updated successfully
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>';
    							}else{
    								$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                        Update failed
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>';
    							}
							}else{
							    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                                Upload failed
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>';
							}
					} else {
						$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                        Failed to upload file
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
<title><?php include('extension/title.php'); ?> | Update Application</title>
    
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
                <h4 class="mb-0"> Update Application</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Update Application</li>
            </ol>
          </div>
        </div>
      </div>
        
  <div class="row">
        
        <div class="col-xl-6 mb-30">
            <div class="card card-statistics mb-30">
                  <div class="card-body">
                    <h5 class="card-title">Update Application</h5>
                    <?php echo $status; ?>
                    <form method="post" class="ui grid form" enctype="multipart/form-data">
                        <?php
                            $query = mysqli_query($con,"select * from application where id='$uid'");
                            $row=mysqli_fetch_array($query);
                                $id = $row['id'];
                                $platform = $row['platform'];
                                $filename = $row['filename'];
                                $dat = $row['date'];
                                
                            if($dat == '0000-00-00 00:00:00'){
                                $date = 'No File';
                            }else{
                                $date = $dat;
                            }
                                
                        ?>
                        <div class="row field">
                            <label class="twelve wide column" for="platform">Platform</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input id="platform" name="platform" type="text" placeholder="Enter title" value="<?php echo $platform; ?>" autocomplete="off" readonly/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="date">Last Updated</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input id="date" name="date" type="text" placeholder="Enter title" value="<?php echo $date; ?>" autocomplete="off" readonly/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="file">File</label>
                          <div class="twelve wide column">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file">
                                    <label class="custom-file-label" for="file">Choose file</label>
                                    <small class="form-text text-muted">Allowed file types: APK, EXE, MSI, ZIP, RAR only</small>
                                    <small class="form-text text-muted">Max file size: 50MB</small>
                                </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <label class="twelve wide column"></label>
                          <div class="twelve wide column">
                            <button type="submit" name="updateapp" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Submit</button>
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
