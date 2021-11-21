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
?>

<?php
if(isset($_POST['adddns'])){
    $domain = mysqli_real_escape_string($con,$_POST['domain_name']);
    $zone = mysqli_real_escape_string($con,$_POST['zone']);
    $global = mysqli_real_escape_string($con,$_POST['global']);
    $email = mysqli_real_escape_string($con,$_POST['email']);
  
  
    if($domain!='' && $zone!='' && $global!='' && $email!=''){
            if(domain_check($domain)){
                $query = mysqli_query($con,"insert into dns_settings(`domain`,`zone`,`global_api`,`email`) values('$domain','$zone','$global','$email')");
                    if($query){
                        $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                        [Domain] Added successfully
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>';
                    }else{
                        $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                        [Domain] Add Failure
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>';
                            }
            }else{
                $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                Domain already registered
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

if(!isset($_POST['action_a'])){
    
}else{
    if($_POST['action_a'] == 'delete'){
        
        $u = mysqli_real_escape_string($con,$_POST['action_u']);
        
        $query = mysqli_query($con,"DELETE FROM dns_settings WHERE id='$u'");
        if($query)
        {
        	$status2 = '<div class="alert alert-success alert-dismissible" role="alert">
                            [Domain] Delete successfully
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        }else{
        	$status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                            [Domain] Delete failure
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        }
    }else{
        
    }
}

?>

<?php 
function domain_check($domain){
  global $con;
  
  $query =mysqli_query($con,"select * from servers where domain='$domain'");
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
<title><?php include('extension/title.php'); ?> | DNS Settings</title>
    
<script src="/assets/js/jquery-3.3.1.min.js"></script>

<!-- Favicon -->
<link rel="shortcut icon" href="<?php include('extension/logo.php'); ?>" />

<!-- Font -->
<link  rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">

<!-- css -->
<link rel="stylesheet" type="text/css" href="/assets/css/style.css" />

<link rel="stylesheet" type="text/css" href="/assets/alertifyjs/css/alertify.css">

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
                <h4 class="mb-0"> DNS Settings</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">DNS Settings</li>
            </ol>
          </div>
        </div>
      </div>
        
    <div class="row">
        <div class="col-xl-4 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title">Add Domain</h5>
                    <?php echo $status; ?>
                    <form method="post" id="dnsDomainAdd" class="ui grid form">
                        
                        <div class="row field">
                            <label class="twelve wide column" for="domain_name">Domain Name</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input id="domain_name" name="domain_name" type="text" placeholder="Enter domain name" autocomplete="off" required/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="zone">Zone ID</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input id="zone" name="zone" type="text" placeholder="Enter Zone API" autocomplete="off" required/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="global">Global API</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input id="global" name="global" type="text" placeholder="Enter Global API" autocomplete="off" required/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="email">Email Address</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input id="email" name="email" type="text" placeholder="Enter cloudflare email" autocomplete="off" required/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <label class="twelve wide column"></label>
                            <div class="twelve wide column">
                                <button type="submit" name="adddns" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Submit</button>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8 mb-30">
            <div class="card card-statistics h-100"> 
                <div class="card-body">
                    <div class="table-responsive">
                        <?php echo $status2; ?>
                        <table id="datatable" class="table table-striped table-bordered p-0">
                            <thead>
                                <tr>
                                    <th>Domain Name</th>
                                    <th>Zone ID</th>
                                    <th>Global API</th>
                                    <th>Email</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i=1;
                                    $query = mysqli_query($con,"select * from dns_settings");
                                    if(mysqli_num_rows($query)>0){
                                        while($row=mysqli_fetch_array($query)){
                                            $id = $row['id'];
                                            $domain = $row['domain'];
                                            $zone = $row['zone'];
                                            $global = $row['global_api'];
                                            $email = $row['email'];
                                ?>    
                                <tr>
                                    <td><?php echo $domain; ?></td>
                                    <td><?php echo $zone; ?></td>
                                    <td><?php echo $global; ?></td>
                                    <td><?php echo $email; ?></td>
                                    <td>
                                        <a href="#" class="btn btn-outline-danger btn-sm" onclick="submitForm('delete','<?php echo $id; ?>')">Delete</a>
                                    </td>
                                </tr>
                                <?php
                                    $i++;
                                    }
                                }else{
                                ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>
<!--=================================
 wrapper -->

<form method="post" id="action_form">
    <input type="hidden" id="action_a" name="action_a"/>
    <input type="hidden" id="action_u" name="action_u"/>
</form>
        
<script>
    function submitForm(action_id, user_id) {
      document.getElementById('action_a').value = action_id;
      document.getElementById('action_u').value = user_id;
      document.getElementById('action_form').submit();
    }
</script>
      
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

<script src="/assets/alertifyjs/alertify.js"></script>

<script>
  $(function () {
    $("#datatable").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
    });
  });
</script>

</body>
</html>
