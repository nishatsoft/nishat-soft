<?php
include('extension/connect.php');
include('extension/check-login.php');
include('extension/function.php');
$admin_data = data_records($con);
$fast_loading = fast_loading($con);
$fastpro_loading = fastpro_loading($con);
$userid = $_SESSION['userid'];
$search = $userid;
$status = '';

if(!isset($_POST['action_a'])){
    
}else{
    if($_POST['action_a'] == 'password'){
        $newpass = rand(0,9999999);
        $authvpn = md5($newpass);
        $u = mysqli_real_escape_string($con,$_POST['action_u']);
        
        $query = mysqli_query($con,"UPDATE users SET user_pass = '$newpass', user_encryptedPass = '$authvpn' WHERE user_id='$u'");
        if($query)
        {
        	$status = '<div class="alert alert-success alert-dismissible" role="alert">
                            [Password] Reset successfully
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        }else{
        	$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                            [Password] Reset failure
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        }
    }elseif($_POST['action_a'] == 'credits'){
    
        $u = mysqli_real_escape_string($con,$_POST['action_u']);
        
        $qry = mysqli_query($con,"select user_credits from users where user_id='$u'");
        $row=mysqli_fetch_array($qry);
        $my_credits = $row['user_credits'];
        
        $descript = ''.$my_credits.' credit(s) have been reset by your upline';
        
        $query = mysqli_query($con,"UPDATE users SET user_credits = '0' WHERE user_id='$u'");
        if($query)
        {
            $update = mysqli_query($con,"insert into credit_log(`user_id`,`description`) values('$u','$descript')");
                if($update){
                    $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                    [Credit] Reset successfully
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';    
                }else{
                    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    [Credit] Reset failure
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
                }
        }else{
        	$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                            [Credit] Reset failure
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        }
    }elseif($_POST['action_a'] == 'block'){
    
        $u = mysqli_real_escape_string($con,$_POST['action_u']);
        
        $query = mysqli_query($con,"UPDATE users SET is_freeze='1' WHERE user_id='".$u."'");
        if($query)
        {
        	$status = '<div class="alert alert-success alert-dismissible" role="alert">
                            [User] Block successfully
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        }else{
        	$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                            [User] Reset failure
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        }
    }elseif($_POST['action_a'] == 'unblock'){
    
        $u = mysqli_real_escape_string($con,$_POST['action_u']);
        
        $query = mysqli_query($con,"UPDATE users SET is_freeze='0' WHERE user_id='".$u."'");
        if($query)
        {
        	$status = '<div class="alert alert-success alert-dismissible" role="alert">
                            [User] Unblock successfully
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        }else{
        	$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                            [User] Unblock failure
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
        }
    }elseif($_POST['action_a'] == 'delete'){
    
        $u = mysqli_real_escape_string($con,$_POST['action_u']);
        $chk_user = mysqli_query($con,"select * from users where user_id='$u'");
        $chk = mysqli_fetch_array($chk_user);
        $del_userid = $chk['user_id'];
        $del_username = $chk['user_name'];
        $del_password = $chk['user_pass'];
        $del_rank = $chk['user_rank'];
        
        $query = mysqli_query($con,"insert into delete_users(`user_id`,`user_name`,`user_pass`,`user_rank`) values('$del_userid','$del_username','$del_password','$del_rank')");
        if($query)
            {
                $qq = mysqli_query($con,"DELETE FROM users WHERE user_id='".$u."'");
                    if($qq){
                	    $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                    [User] Delete successfully
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
                    }else{
                    	$status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                        [User] Delete failure
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
<title><?php include('extension/title.php'); ?> | View Resellers</title>
    
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
    $cred = mysqli_query($con,"select user_credits, user_rank from users where user_id='$userid'");
    $myinfo = mysqli_fetch_array($cred);
    $credit = $myinfo['user_credits'];
    
    if($credit == 0 && $myinfo['user_rank']!='superadmin'){
        $box_bg = 'red-bg';
    }else{
        $box_bg = 'green-bg';
    }
    
    if($myinfo['user_rank'] == 'superadmin'){
        $admincred = 'Unlimited';
    }else{
        $admincred = $credit;
    }
    
    if($myinfo['user_rank']=='superadmin'){
        $que = mysqli_query($con,"select * from users where user_rank='reseller'");
    }else{
        $que = mysqli_query($con,"select * from users where user_rank='reseller' and user_upline='$userid'");
    }
    
    $reseller = mysqli_num_rows($que);
    
    if($reseller == 0){
        $res_bg = 'red-bg';
    }else{
        $res_bg = 'green-bg';
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
                <h4 class="mb-0"> View Resellers</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">View Resellers</li>
            </ol>
          </div>
        </div>
      </div>
    
   <div class="row">
    <div class="col-xl-4 mb-30">
          <div class='card card-statistics <?php echo $res_bg; ?> h-100' >
            <div class="card-body">
              <div class="clearfix">
                <div class="float-left icon-box-fixed">
                  <span class="text-white">
                    <i class="fa fa-users highlight-icon" aria-hidden="true"></i>
                  </span>
                </div>
                <div class="float-right text-right">
                  <h4 class="text-white"><?php echo $reseller; ?></h4>
                  <p class="card-text text-white">Reseller  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
       
       <div class="col-xl-4 mb-30">
          <div class='card card-statistics <?php echo $box_bg; ?> h-100' >
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left icon-box-fixed">
                      <span class="text-white">
                        <i class="fa fa-dollar highlight-icon" aria-hidden="true"></i>
                      </span>
                    </div>
                    <div class="float-right text-right">
                      <h4 class="text-white"><?php echo $admincred; ?></h4>
                      <p class="card-text text-white">Credit(s)  </p>
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
                      <th>Username</th>
                      <th>Password</th>
                      <th>Credits</th>
                      <th>Status</th>
                      <th>Action</th>
                      <th></th>
                      <?php if($myinfo['user_rank']=='superadmin') { ?>
                      <th></th>
                      <?php } ?>
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
            "url": "serverside/reseller-serverside",
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
