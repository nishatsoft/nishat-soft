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
?>

<?php
if(isset($_POST['update'])){
  $dns_id = $uid;
  $hostname = mysqli_real_escape_string($con,$_POST['host_name']);
  $ipaddress = mysqli_real_escape_string($con,$_POST['ip_address']);
  
  $qry = mysqli_query($con,"select * from dns where id='$dns_id'");
  $dnsrow=mysqli_fetch_array($qry);
  
  $dnsdomain = $dnsrow['domain'];
  $dnshostname = $dnsrow['hostname'];
  $dnsip = $dnsrow['ip_address'];
  $dnszone = $dnsrow['zone'];
  $dnsglobal = $dnsrow['global_api'];
  $dnsemail = $dnsrow['email'];
  $record_type = 'A';
  
  if($hostname!='' && $ipaddress!=''){

            
        /* Cloudflare.com | AP襤v4 | 覺 */
            $apikey		= $dnsglobal; // Cloudflare Global API
            $email 		= $dnsemail; // Cloudflare Email Adress
            $domain 	= $dnsdomain;  // zone_name // Cloudflare Domain Name
            $zoneid 	= $dnszone; // zone_id // Cloudflare Domain Zone ID
            $type       = $record_type; 
            $name   = $dnshostname.'.'.$dnsdomain;
            $content      = $ipaddress;
            $ttl = 1;
            $cloudflare_proxy = false;
		    
		    $ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".$zoneid."/dns_records?name=$name");
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($ch, CURLOPT_VERBOSE, 1);
    		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Auth-Email: '.$email.'',
            'X-Auth-Key: '.$apikey.'',
            'Cache-Control: no-cache',
            // 'Content-Type: multipart/form-data; charset=utf-8',
            'Content-Type:application/json'
            
            ));
    		$content  = curl_exec($ch);
    		curl_close($ch);
    		/* PARSING RESPONSE */
    		$response = json_decode($content,true);
    		$return = [
    			"id" => $response['result'][0]['id'],
    			"type" => $response['result'][0]['type'],
    			"name" => $response['result'][0]['name'],
    			"data" => $response['result'][0]['data'],
    			"content" => $response['result'][0]['content'],
    			"proxied" => $response['result'][0]['proxied'],
    			"ttl" => $response['result'][0]['ttl']
    		];
		    
		    $lenz = $return['id'];

    		$ch = curl_init(); 		
    		$payload = json_encode( array( "type"=> $type,"name" => $hostname, "content" => $ipaddress, "ttl" => $ttl, "proxied" => $cloudflare_proxy ) );
    		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    		curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".$zoneid."/dns_records/".$lenz);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    		curl_setopt($ch, CURLOPT_VERBOSE, 1);
    		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Auth-Email: '.$email.'',
            'X-Auth-Key: '.$apikey.'',
            'Cache-Control: no-cache',
            'Content-Type: multipart/form-data; charset=utf-8',
            'Content-Type:application/json',
            'purge_everything: true'
            
            ));	
    		$content  = curl_exec($ch);
    		curl_close($ch);
    		/* PARSING RESPONSE */
    		$response = json_decode($content,true);
    		/* RETURN */
    		if($response['success'] == true){
	            
            $query = mysqli_query($con,"UPDATE dns SET hostname='$hostname', ip_address='$ipaddress' WHERE id='$dns_id'");
                if($query){
                    $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                    DNS updated successfully
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
<title><?php include('extension/title.php'); ?> | Edit DNS</title>
    
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
                <h4 class="mb-0"> Edit DNS</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Edit DNS</li>
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
                    <h5 class="card-title">Edit DNS</h5>
                    <?php echo $status; ?>
                    <form method="post" id="createResellerForm" class="ui grid form">
                                <?php
                                    $query = mysqli_query($con,"select * from dns where id='$uid'");
                                    $row=mysqli_fetch_array($query);
                                        $id = $row['id'];
                                        $ip_address = $row['ip_address'];
                                        $hostname = $row['hostname'];
                                        $domain = $row['domain'];
                                        $status = $row['status'];
                                            
                                    ?>
                        <div class="row field">
                          <label class="twelve wide column" for="host_name">Hostname</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="host_name" name="host_name" type="text" value="<?php echo $hostname; ?>" placeholder="Enter hostname" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row field">
                          <label class="twelve wide column" for="ip_address">IP Address</label>
                          <div class="twelve wide column">
                            <div class="ui input">
                              <input id="ip_address" name="ip_address" type="text" value="<?php echo $ip_address; ?>" placeholder="Enter IPv4 address" autocomplete="off" required/>
                            </div>
                          </div>
                        </div>
                        
                        <input id="domain" name="domain" type="hidden" value="<?php echo $domain; ?>"/>
                        
                        <div class="row">
                          <label class="twelve wide column"></label>
                          <div class="twelve wide column">
                            <button type="submit" name="update" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Update DNS</button>
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
