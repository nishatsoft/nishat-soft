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
$uid = mysqli_real_escape_string($con,$_GET['uid']);
$qry = mysqli_query($con,"select * from updater where id='$uid'");
$rw=mysqli_fetch_array($qry);
$filen = $rw['filename'];
if(mysqli_num_rows($qry)>0){
    
}else{
    header("location:dashboard");
}
?>

<?php
if(isset($_POST['update'])){
    $name = mysqli_real_escape_string($con,$_POST['update_name']);
    $type = mysqli_real_escape_string($con,$_POST['protocol']);
    $guicode = $_POST['guicode'];
    
    
    
        if($name!='' && $type!='' && $guicode!=''){
            $content = "$guicode";
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/updater/$filen","wb");
            $fwrite = fwrite($fp,$content);
            $fclose = fclose($fp);
            
            if($fwrite && $fclose){
                $query = mysqli_query($con,"UPDATE updater SET name='$name', type='$type' WHERE id='$uid'");
                if($query){
                    $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                    [Update] Successfully updated
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
                }else{
                    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    [Update] Failed
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
                            Please fill-out all forms
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        }

}
?>

<?php 
function update_check($name){
  global $con;
  
  $query =mysqli_query($con,"select * from updater where name='$name'");
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
<title><?php include('extension/title.php'); ?> | Edit Update</title>
    
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
                <h4 class="mb-0"> Edit Update</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Edit Update</li>
            </ol>
          </div>
        </div>
      </div>
    
  <div class="row">
        
        <div class="col-xl-6 mb-30">
            <div class="card card-statistics mb-30">
                  <div class="card-body">
                    <h5 class="card-title">Edit Update</h5>
                    <?php echo $status; ?>
                    <form method="post" class="ui grid form">
                        
                        <?php
                            $query = mysqli_query($con,"select * from updater where id='$uid'");
                            $row=mysqli_fetch_array($query);
                            $id = $row['id'];
                            $name = $row['name'];
                            $filename = $row['filename'];
                            $type = $row['type'];  
                            $type2 = '';
                            $type1 = '';
                            
                            if($type == 'openvpn'){
                                $type1 = 'selected';
                            }elseif($type == 'openconnect'){
                                $type2 = 'selected';
                            }else{
                                
                            }
                            
                            $file = '../updater/'.$filename.'';
                            $myfile = fopen($file, "r") or die("Unable to open file!");
                            $editor = fread($myfile,filesize($file));
                            fwrite($myfile, $editor);
                            fclose($myfile);
                        ?>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="update_name">Name</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input id="update_name" name="update_name" type="text" placeholder="Enter update name" value="<?php echo $name; ?>" autocomplete="off" required/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="protocol">Protocol</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <select class="custom-select custom-select-lg mb-3" id="protocol" name="protocol">
                                        <option value="openvpn" <?php echo $type1; ?>>OpenVPN</option>
                                        <option value="openconnect" <?php echo $type2; ?>>OpenConnect</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="guicode">Value</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <textarea id="guicode" class="form-control" name="guicode" rows="20%" required><?php echo $editor; ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <label class="twelve wide column"></label>
                            <div class="twelve wide column">
                                <button type="submit" name="update" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Submit</button>
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
