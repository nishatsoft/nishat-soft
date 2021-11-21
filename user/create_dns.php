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
?>

<?php
if(isset($_POST['register'])){
  $hostname = mysqli_real_escape_string($con,$_POST['hostname']);
  $ipaddress = mysqli_real_escape_string($con,$_POST['ipaddress']);
  $domain_id = mysqli_real_escape_string($con,$_POST['domain']);
  $host = $hostname.'.'.$domain;
  
  $qry = mysqli_query($con,"select * from dns_settings where id='$domain_id'");
  $dnsrow=mysqli_fetch_array($qry);
  $dnsdomain = $dnsrow['domain'];
  $dnszone = $dnsrow['zone'];
  $dnsglobal = $dnsrow['global_api'];
  $dnsemail = $dnsrow['email'];
  $record_type = 'A';
  
  if($hostname!='' && $ipaddress!='' && $domain_id!=''){
      
      
      $query =mysqli_query($con,"select * from dns where hostname='$hostname'");
      $dns_rows =mysqli_fetch_array($query);
      $host_1 = $dns_rows['hostname'];
      $domain_1 = $dns_rows['domain'];
      $host_1 = $host_1.'.'.$domain_1;
  
      if($host !== $host_1){
            
        /* Cloudflare.com | AP襤v4 | 覺 */
            $apikey		= $dnsglobal; // Cloudflare Global API
            $email 		= $dnsemail; // Cloudflare Email Adress
            $domain 	= $dnsdomain;  // zone_name // Cloudflare Domain Name
            $zoneid 	= $dnszone; // zone_id // Cloudflare Domain Zone ID
            $type       = $record_type; 
            $name   = $hostname;
            $content      = $ipaddress;
            $ttl = 1;
            $cloudflare_proxy = false;
		    
            $ch = curl_init();		
    		$payload = json_encode(  array( "type"=> $type,"name" => $name, "content" => $content, "ttl" => $ttl, "proxied" => $cloudflare_proxy ));
    		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    		curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".$zoneid."/dns_records/");
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($ch, CURLOPT_VERBOSE, 1);
    		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Auth-Email: '.$email.'',
            'X-Auth-Key: '.$apikey.'',
            'Cache-Control: no-cache',
            // 'Content-Type: multipart/form-data; charset=utf-8',
            'Content-Type:application/json',
            'purge_everything: true'
            
            ));
    		$content  = curl_exec($ch);
    		curl_close($ch);
    		/* PARSING RESPONSE */
    		$response = json_decode($content,true);
    		/* RETURN */
            
            
            if(!empty($response['success'])){
            
            $query = mysqli_query($con,"insert into dns(`ip_address`,`hostname`,`domain`,`record_type`,`zone`,`global_api`,`email`,`status`) values('$ipaddress','$hostname','$dnsdomain','A','$dnszone','$dnsglobal','$dnsemail','0')");
                if($query){
                    $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                    DNS created successfully
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
                                Cloudflare API error
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>';
            }
      }
      else{

        $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                        DNS hostname is taken
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
function dns_check($host){
  global $con;
  
  $query =mysqli_query($con,"select * from dns");
  $dns_rows =mysqli_fetch_array($query);
  $host_1 = $dns_rows['hostname'];
  $domain_1 = $dns_rows['domain'];
  $host_1 = $host_1.'.'.$domain_1;
  
  if($host = $host_1){
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
<title><?php include('extension/title.php'); ?> | Create DNS</title>
    
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
    $dns_status = mysqli_query($con,"select status from dns");
    $dns_count = mysqli_num_rows($dns_status);
    
    if($dns_count == 0){
        $dns_bg = 'red-bg';
    }else{
        $dns_bg = 'green-bg';
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
                <h4 class="mb-0"> Create DNS</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Create DNS</li>
            </ol>
          </div>
        </div>
      </div>
    
   <div class="row">
    <div class="col-xl-4 mb-30">
          <div class='card card-statistics <?php echo $dns_bg; ?> h-100' >
            <div class="card-body">
              <div class="clearfix">
                <div class="float-left icon-box-fixed">
                  <span class="text-white">
                    <i class="fa fa-users highlight-icon" aria-hidden="true"></i>
                  </span>
                </div>
                <div class="float-right text-right">
                  <h4 class="text-white"><?php echo $dns_count; ?></h4>
                  <p class="card-text text-white">DNS Record(s)  </p>
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
                    <h5 class="card-title">Create DNS</h5>
                    <?php echo $status; ?>
                    <form method="post" id="createResellerForm" class="ui grid form">
                        
                        <div class="row field">
                          <label class="twelve wide column" for="hostname">Hostname</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="hostname" name="hostname" type="text" placeholder="Enter hostname" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="ipaddress">IP Address</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="ipaddress" name="ipaddress" type="text" placeholder="Enter IPv4 address" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="domain">Domain</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <select class="custom-select custom-select-lg mb-3" id="domain" name="domain">
                                        <option value="" selected>Select Domain</option>
                                        <?php $i=1;
                                            $query = mysqli_query($con,"select * from dns_settings");
                                            if(mysqli_num_rows($query)>0){
                                            while($row=mysqli_fetch_array($query)){
                                                $id = $row['id'];
                                                $domain = $row['domain'];
                                                $zone = $row['zone'];
                                                $global = $row['global_api'];
                                                $email = $row['email'];
                                        ?>
                                        <option value="<?php echo $id; ?>"><?php echo $domain; ?></option>
                                        <?php
                                            $i++;
                                            }
                                        }else{
                                        ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                          <label class="twelve wide column"></label>
                          <div class="twelve wide column">
                            <button type="submit" name="register" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Create DNS</button>
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
