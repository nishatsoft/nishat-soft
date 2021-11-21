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
        $chk_app = mysqli_query($con,"select * from application where id='$u'");
        $chk = mysqli_fetch_array($chk_app);
        $del_id = $chk['id'];
        $del_filename = $chk['filename'];
        $del_download = '0';
        $del_date = '0000-00-00 00:00:00';
        $dirpath = "../_uploads/";
        
        $unlink = unlink($dirpath . $del_filename);
		
		if($unlink){
        $query = mysqli_query($con,"UPDATE application SET filename='', download_count='$del_download', date='$del_date' WHERE id='$u'");
            if($query)
                {
                    $status = '<div class="alert alert-success alert-dismissible" role="alert">
                        [Application] Delete successfully
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                    </div>';
            }else{
                    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                        [Application] Delete failure
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
            }
		}else{
		    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                        [Application] Failed to delete file
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

function calc_time($minutes) {
    $seconds = 0;
    $hours = 0;
    
    $days = (int)($seconds / 86400);
    $seconds -= ($days * 86400);
    if ($seconds) {
        $hours = (int)($seconds / 3600);
        $seconds -= ($hours * 3600);
    }
    if ($seconds) {
        $minutes = (int)($seconds / 60);
        $seconds -= ($minutes * 60);
    }
    $time = array('days'=>(int)$days,
        'hours'=>(int)$hours,
        'minutes'=>(int)$minutes,
        'seconds'=>(int)$seconds);
    return $time;
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
<title><?php include('extension/title.php'); ?> | Application</title>
    
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
                <h4 class="mb-0"> Application</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Application</li>
            </ol>
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
                      <th>Platform</th>
                      <th>Filename</th>
                      <th>Downloaded</th>
                      <th>Date</th>
                      <th></th>
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

    <!-- Small modal -->
        <div class="modal fade bd-example-modal-sm" id="appadd" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title"><div class="mb-30">
                            <h6>EXPERTISE</h6>
                            <h2>Modal title</h2>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <div class="modal-body">
                    <form id="dlform" name="dlform" accept-charset="UTF-8" enctype="multipart/form-data" novalidate>
                    Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
                </div>
            </div>
        </div>

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
            "url": "serverside/app-serverside",
            "type": "POST"
        },
		"aoColumnDefs": [{
			'bSortable': false,
			'aTargets': [0,-1]
		}],
		order: [[ 0, 'asc' ], [ 0, 'asc' ]],
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
