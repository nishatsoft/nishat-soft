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
$status2 = '';
$status3 = '';
$mainte1 = '';
$mainte2 = '';
define('resize_width', 480);
define('resize_height', 480);
?>

<?php
if(isset($_POST['siteupdate'])){
  $webname = mysqli_real_escape_string($con,$_POST['webname']);
  $weburl = mysqli_real_escape_string($con,$_POST['weburl']);
  $maintenance = mysqli_real_escape_string($con,$_POST['maintenance']);
  $mytheme = mysqli_real_escape_string($con,$_POST['themez']);
  $mytxt = mysqli_real_escape_string($con,$_POST['themetext']);
    
    $dirpath = "../assets/images/logo/";
	if(is_dir($dirpath) == false)
	{
		mkdir($dirpath, 0777, true) or die('Error: ');
	}
	
	$images = restructure_array( $_FILES );
    $allowedExts = array("gif", "jpeg", "jpg", "png");
        foreach ( $images as $key => $value)
    		{		
    			$i = $key+1;
    										
    			$image_name = $value['name'];
    			$ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    			$name = $i*time().'.'.$ext;
    			$image_size = $value["size"] / 1024;
    			$image_flag = true;
    			$max_size = 10000;
    				
    			}
  
    if($webname!='' && $weburl!=''){
            if($image_name=='')
                {
                    $query = mysqli_query($con,"UPDATE admin set title_name='$webname', website='$weburl', maintenance='$maintenance', theme='$mytheme', theme_text='$mytxt'");
                        if($query){
                            $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                            [Site] Update successful
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>';
                        }else{
                            $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                            [Site] Update failure
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>';
                    }
            }else{
                    if( in_array($ext, $allowedExts) && $image_size < $max_size )
    				{
    					$image_flag = true;
    				} 
    				else 
    				{
    					$image_flag = false;
    					$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    Maybe '.$image_name.' exceeds max '.$max_size.' KB size or incorrect file extension
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
    				} 
    						
    				if( $value["error"] > 0 ){
    					$image_flag = false;
    					$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    '.$image_name.' Image contains error - Error Code : '.$value["error"].'
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
    				}
    						
    				if($image_flag)
    				{
    					$pic = mysqli_query($con,"select * from admin");
    					while($rowas = mysqli_fetch_array($pic))
    					{
    						$site_logo = $rowas['logo'];
    						if($site_logo == '')
    						{
    							
    						}else{
    							$path_photo = $dirpath . $site_logo;
    							unlink($path_photo);	
    						}
    					}
    
    					move_uploaded_file($value["tmp_name"], $dirpath.$name);
    								
    					$original = $name;
    					$filename = $dirpath.$name;
    					$resized = $dirpath.$name;
    					if (resizeImage($filename, resize_width, resize_height, $resized))  
    					{  
    					}else{  
    						$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    [Site] There was an error resizing your image
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
    					}
    					
        				$query = mysqli_query($con,"UPDATE admin set title_name='$webname', website='$weburl', logo='$original', maintenance='$maintenance', theme='$mytheme', theme_text='$mytxt'");
                            if($query){
                                $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                                [Site] Update successful
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>';
                            }else{
                                $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                                [Site] Update failure
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>';
                        }
    				}
                }
    }
    else{
        $status = "toastr.info('Please fill-out all forms!', 'Error!', {timeOut: 3000})";
    }
}elseif(isset($_POST['privacyupdate'])){
    $privatext = $_POST['privacy']; 
    
    $content = "$privatext";
    $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/updater/privacy","wb");
    $fwrite = fwrite($fp,$content);
    $fclose = fclose($fp);
    
    if($fwrite && $fclose){
        $status2 = '<div class="alert alert-success alert-dismissible" role="alert">
                        [Privacy] Successfully updated
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>';
    }else{
        $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                        [Privacy] Update failed
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>';
    }
}elseif(isset($_POST['termsupdate'])){
    $termtext = $_POST['terms']; 
    
    $content = "$termtext";
    $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/updater/terms","wb");
    $fwrite = fwrite($fp,$content);
    $fclose = fclose($fp);
    
    if($fwrite && $fclose){
        $status3 = '<div class="alert alert-success alert-dismissible" role="alert">
                        [Terms] Successfully updated
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>';
    }else{
        $status3 = '<div class="alert alert-danger alert-dismissible" role="alert">
                        [Terms] Update failed
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>';
    }
}

function restructure_array(array $images)
{
	$result = array();

	foreach ($images as $key => $value) {
		foreach ($value as $k => $val) {
			for ($i = 0; $i < count($val); $i++) {
				$result[$i][$k] = $val[$i];
			}
		}
	}

	return $result;
}

function resizeImage($filename, $max_width, $max_height, $newfilename="", $withSampling=true)   
{   
   $width = 0;  
   $height = 0;  
  
   $newwidth = 0;  
   $newheight = 0;  
  
	// If no new filename was specified then use the original filename  
	if($newfilename == "")   
	{  
		$newfilename = $filename;   
	}  
      
	// Get original sizes   
	list($width, $height) = getimagesize($filename);   
      
	if($width > $height)  
	{  
		// We're dealing with max_width  
		if($width > $max_width)  
		{  
			$newwidth = $width * ($max_width / $width);  
			$newheight = $height * ($max_width / $width);  
		}else{  
			// No need to resize  
			$newwidth = $width;  
			$newheight = $height;  
		}  
	}else{  
		// Deal with max_height  
		if($height > $max_height)  
		{  
			$newwidth = $width * ($max_height / $height);  
			$newheight = $height * ($max_height / $height);  
		}else{  
			// No need to resize  
			$newwidth = $width;  
			$newheight = $height;  
		}  
	}  
  
	// Create a new image object   
	$thumb = imagecreatetruecolor($newwidth, $newheight);   
	imagealphablending($thumb, false);
	imagesavealpha($thumb, true);
	
	// Load the original based on it's extension  
	$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));  
  
	if($ext=='jpg' || $ext=='jpeg'){  
		$source = imagecreatefromjpeg($filename);   
	}elseif($ext=='gif'){  
		$source = imagecreatefromgif($filename);
		imagealphablending($source, true);		
	}elseif($ext=='png'){   
		$source = imagecreatefrompng($filename); 
		imagealphablending($source, true);		
	}else{  
		// Fail because we only do JPG, JPEG, GIF and PNG  
		return FALSE;  
	}  
      
	// Resize the image with sampling if specified  
	if($withSampling)   
	{  
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);   
	}else{     
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);   
	}  
          
	$imageQuality = 100;       
	// Save the new image   
	if($ext=='jpg' || $ext=='jpeg'){  
		return imagejpeg($thumb, $newfilename);   
	}elseif($ext=='gif'){  
      return imagegif($thumb, $newfilename);   
	}elseif($ext=='png'){  
		$scaleQuality = round(($imageQuality/100) * 9);
		$invertScaleQuality = 9 - $scaleQuality;
		return imagepng($thumb, $newfilename);   
	}
	imagedestroy($thumb);
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
<title><?php include('extension/title.php'); ?> | Site Settings</title>
    
<script src="/assets/js/jquery-3.3.1.min.js"></script>

<!-- Favicon -->
<link rel="shortcut icon" href="<?php include('extension/logo.php'); ?>" />

<!-- Font -->
<link  rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">

<!-- css -->
<link rel="stylesheet" type="text/css" href="/assets/css/style.css" />

<style>
    .note-toolbar{
        z-index: 10 !important;
    }
</style>
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
                <h4 class="mb-0"> Site Settings</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Site Settings</li>
            </ol>
          </div>
        </div>
      </div>
 
  <div class="row">
        
        <div class="col-xl-6 mb-30">
            <div class="card card-statistics mb-30">
                <div class="card-body">
                    <h5 class="card-title">Site Settings</h5>
                    <?php echo $status; ?>
                    <form method="post" class="ui grid form" enctype="multipart/form-data">
                        
                        <?php
                        $query = mysqli_query($con,"select * from admin");
                        $result = mysqli_fetch_array($query);
                        
                        $web_name = $result['title_name'];
                        $web_url = $result['website'];
                        $mainte = $result['maintenance'];
                        $themez = $result['theme'];
                        $themeztext = $result['theme_text'];
                        
                        if($mainte == '0'){
                            $mainte1 = 'selected';
                        }else{
                            $mainte2 = 'selected';
                        }
                        ?>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="webname">Logo</label>
                          <div class="twelve wide column">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="images" name="images[]">
                                    <label class="custom-file-label" for="images">Choose file</label>
                                </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="webname">Website Name</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="webname" name="webname" type="text" placeholder="Enter website name" value="<?php echo $web_name; ?>" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="weburl">Website URL</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="weburl" name="weburl" type="text" placeholder="ex. www.domain.com" value="<?php echo $web_url; ?>" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="maintenance">Maintenance Mode</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <select class="custom-select custom-select-lg mb-3" id="maintenance" name="maintenance">
                                        <option value="0" <?php echo $mainte1; ?>>Inactive</option>
                                        <option value="1" <?php echo $mainte2; ?>>Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column">Theme Color</label>
                            <div id="cp2" class="input-group colorpicker-component" style="flex-wrap: nowrap !important;">
                                <input type="text" name="themez" class="form-control input-lg" value="<?php echo $themez; ?>"/>
                                <div class="input-group-append">
                                    <span class="input-group-addon input-group-text"><i></i></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column">Main Text Color</label>
                            <div id="cp1" class="input-group colorpicker-component" style="flex-wrap: nowrap !important;">
                                <input type="text" name="themetext" class="form-control input-lg" value="<?php echo $themeztext; ?>"/>
                                <div class="input-group-append">
                                    <span class="input-group-addon input-group-text"><i></i></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                          <label class="twelve wide column"></label>
                          <div class="twelve wide column">
                            <button type="submit" name="siteupdate" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Submit</button>
                          </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-30">
            <div class="card card-statistics mb-30">
                <div class="card-body">
                    <h5 class="card-title">Privacy Policy</h5>
                    <?php echo $status2; ?>
                    <form method="post" class="ui grid form">
                        <?php
                            $file = '../updater/privacy';
                            $myfile = fopen($file, "r") or die("Unable to open file!");
                            $privacy = fread($myfile,filesize($file));
                            fwrite($myfile, $privacy);
                            fclose($myfile);
                        ?>
                        <textarea id="privacy" name="privacy"><?php echo $privacy; ?></textarea>
                        <div class="row">
                            <label class="twelve wide column"></label>
                            <div class="twelve wide column">
                                <button type="submit" name="privacyupdate" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card card-statistics mb-30">
                <div class="card-body">
                    <h5 class="card-title">Terms & Conditions</h5>
                    <?php echo $status3; ?>
                    <form method="post" class="ui grid form">
                        <?php
                            $file = '../updater/terms';
                            $myfile = fopen($file, "r") or die("Unable to open file!");
                            $terms = fread($myfile,filesize($file));
                            fwrite($myfile, $terms);
                            fclose($myfile);
                        ?>
                        <textarea id="terms" name="terms"><?php echo $terms; ?></textarea>
                        <div class="row">
                            <label class="twelve wide column"></label>
                            <div class="twelve wide column">
                                <button type="submit" name="termsupdate" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Submit</button>
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
<script src="/assets/js/custom_.js"></script>
</body>
</html>
