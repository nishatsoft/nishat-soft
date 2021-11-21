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

if(!isset($_POST['action_a'])){
        
}else{
    if($_POST['action_a'] == 'delete'){

        $u = mysqli_real_escape_string($con,$_POST['action_u']);
			
			$qry = mysqli_query($con,"select * from dns where id='$u'");
	        $rowdns=mysqli_fetch_array($qry);
	        $dnsdomain = $rowdns['domain'];
	        $dnshostname = $rowdns['hostname'];
	        $dnsip = $rowdns['ip_address'];
	        $dnsglobal = $rowdns['global_api'];
	        $dnszone = $rowdns['zone'];
	        $dnsrecord = $rowdns['record_type'];
	        $dnsemail = $rowdns['email'];
	        
	        $apikey		= $dnsglobal; // Cloudflare Global API
            $email 		= $dnsemail; // Cloudflare Email Adress
            $domain 	= $dnsdomain;  // zone_name // Cloudflare Domain Name
            $zoneid 	= $dnszone; // zone_id // Cloudflare Domain Zone ID
            $type       = $dnsrecord; 
            $host_name1 = $dnshostname;
            $content    = $dnsip;
            $name   = $dnshostname.'.'.$dnsdomain;
            
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
    		curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/".$zoneid."/dns_records/".$lenz);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
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
    		
    		/* RETURN */
    		if($response['success'] == true){
                $query = mysqli_query($con,"DELETE from dns where id='$u'");
                if($query)
                {
                	$status = '<div class="alert alert-success alert-dismissible" role="alert">
                                    [DNS] Delete successfully
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
                }else{
                	$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    [DNS] Delete failure
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
    }else{
        
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
  $host_1 = $host_1.$domain_1;
  
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
<title><?php include('extension/title.php'); ?> | View DNS Records</title>
    
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
                <h4 class="mb-0"> View DNS Records</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">View DNS Records</li>
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
      <div class="col-xl-12 mb-30">     
        <div class="card card-statistics h-100"> 
          <div class="card-body">
            <div class="table-responsive">
            <?php echo $status; ?>
            <table id="datatablej" class="table table-striped table-bordered p-0">
              <thead>
                  <tr>
                      <th>Hostname</th>
                      <th>IP Address</th>
                      <th>Status</th>
                      <th>Action</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>                      
              </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>

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

<!-- Required datatable js -->
<script src="/assets/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Buttons examples -->
<script src="/assets/datatables/dataTables.buttons.min.js"></script>
<script src="/assets/datatables/buttons.bootstrap4.min.js"></script>
<script src="/assets/datatables/jszip.min.js"></script>
<script src="/assets/datatables/pdfmake.min.js"></script>
<script src="/assets/datatables/vfs_fonts.js"></script>
<script src="/assets/datatables/buttons.html5.min.js"></script>
<script src="/assets/datatables/buttons.print.min.js"></script>
<script src="/assets/datatables/buttons.colVis.min.js"></script>

<!-- Responsive examples -->
<script src="/assets/datatables/dataTables.responsive.min.js"></script>
<script src="/assets/datatables/responsive.bootstrap4.min.js"></script>

<script>
  $(function () {
    $("#datatablej").DataTable({
		responsive: false,
        "bProcessing": true,
        "bServerSide": true,
        "bStateSave": false,
        "ajax": {
            "url": "serverside/dns-serverside",
            "type": "POST"
        },
		"aoColumnDefs": [{
			'bSortable': false,
			'aTargets': [0,-1]
		}],
		order: [[ 0, 'desc' ], [ 0, 'asc' ]],
		"iDisplayLength": 10,
		"aLengthMenu": [
				[10, 20, 50, 100, 99999999999999],
				[10, 20, 50, 100, "ALL"]
		],
		"sPaginationType": "full",
		language: {
			"sSearchPlaceholder": "Search..",
			"lengthMenu": "_MENU_",
			"search": "_INPUT_",
			"oPaginate":
			{
				"sFirst":'<i class="fa fa-angle-double-left" aria-hidden="true"></i>',
				"sLast": '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
				"sNext": '<i class="fa fa-angle-right" aria-hidden="true"></i>',
				"sPrevious": '<i class="fa fa-angle-left" aria-hidden="true"></i>'
			},
			"sInfo":'Showing _START_ to _END_ of _TOTAL_ entries',
			"infoFiltered": "",
			"sZeroRecords": "No matching records found"
		},
    });
  });
</script>

</body>
</html>
