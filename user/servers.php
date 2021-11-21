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
if(isset($_POST['addserver'])){
    $server_name = mysqli_real_escape_string($con,$_POST['server_name']);
    $server_ip = mysqli_real_escape_string($con,$_POST['server_ip']);
    $server_user = mysqli_real_escape_string($con,$_POST['server_user']);
    $server_pass = mysqli_real_escape_string($con,$_POST['server_pass']);
    $protocol = mysqli_real_escape_string($con,$_POST['protocol']);
  
    
    $query = mysqli_query($con,"select * from admin");
    $result = mysqli_fetch_array($query);
    $website = $result['website'];
  
    if($server_name!='' && $server_ip!='' && $server_user!='' && $server_pass!=''){
        if($protocol == 'openvpn'){
            if(ip_check($server_ip)){
                if(!$connection = ssh2_connect($server_ip, 22)){
                    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    [OpenVPN] Connection Failed
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
                }else{
                    if(!ssh2_auth_password($connection,$server_user,$server_pass)){
                        $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                        [OpenVPN] Authentication Failed
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>';
                    }else{
                        $link = "http://installerko.xyz/setupko/nishatscript/ubuntu/openvpn/openvpn.sh";
                        $stream = ssh2_exec($connection, "apt update -y; apt install screen -y; wget -N --no-check-certificate -q -O ~/.installer.sh $link && chmod +x ~/.installer.sh && screen -AmdS installer ./.installer.sh");
                    
                        if($stream){
                            $query = mysqli_query($con,"insert into servers(`server_name`,`server_ip`,`protocol`,`status`) values('$server_name','$server_ip','$protocol','0')");
                            if($query){
                                $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                                [OpenVPN] Please wait 5-10 minutes to complete the installation
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>';
                            }else{
                                $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                                [OpenVPN] Something went wrong
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>';
                            }
                        }else{
                            $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                            [OpenVPN] Installation Failed
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>';
                        }
                    }
                }
            }else{
                $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                [OpenVPN] IP address already registered
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>';
            }
        #OPENVPN WEBSOCKET
        }elseif($protocol == 'openvpnws'){
            if(ip_check($server_ip)){
                if(!$connection = ssh2_connect($server_ip, 22)){
                    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    [OpenVPN] Connection Failed
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
                }else{
                    if(!ssh2_auth_password($connection,$server_user,$server_pass)){
                        $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                        [OpenVPN] Authentication Failed
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>';
                    }else{
                        $link = "http://installerko.xyz/setupko/nishatscript/ubuntu/openvpn/openvpn-ws.sh";
                        $stream = ssh2_exec($connection, "apt update -y; apt install screen -y; wget -N --no-check-certificate -q -O ~/.installer.sh $link; chmod +x ~/.installer.sh; screen -AmdS installer ./.installer.sh");
                    
                        if($stream){
                        
                            $query = mysqli_query($con,"insert into servers(`server_name`,`server_ip`,`protocol`,`status`) values('$server_name','$server_ip','$protocol','0')");
                            if($query){
                                $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                                [OpenVPN] Please wait 5-10 minutes to complete the installation
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>';
                            }else{
                                $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                                [OpenVPN] Something went wrong
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>';
                            }
                        }else{
                            $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                            [OpenVPN] Installation Failed
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>';
                        }
                    }
                }
            }else{
                $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                [OpenVPN] IP address already registered
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>';
            }
        }elseif($protocol == 'openconnect'){
            if(ip_check($server_ip)){
                if(!$connection = ssh2_connect($server_ip, 22)){
                    $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                    [Openconnect] Connection Failed
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>';
                }else{
                    if(!ssh2_auth_password($connection,$server_user,$server_pass)){
                        $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                        [Openconnect] Authentication Failed
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>';
                    }else{
                        $link = "http://installerko.xyz/setupko/nishatscript/ubuntu/openconnect/openconnect.sh";
                        $stream = ssh2_exec($connection, "apt update -y; apt install screen -y; wget -N --no-check-certificate -q -O ~/.installer.sh $link && chmod +x ~/.installer.sh && screen -AmdS installer ./.installer.sh");
                    
                        if($stream){
                            $query = mysqli_query($con,"insert into servers(`server_name`,`server_ip`,`protocol`,`status`) values('$server_name','$server_ip','$protocol','0')");
                            if($query){
                                $status = '<div class="alert alert-success alert-dismissible" role="alert">
                                                [Openconnect] Please wait 5-10 minutes to complete the installation
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>';
                            }else{
                                $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                                [Openconnect] Something went wrong
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>';
                            }
                        }else{
                            $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                            [Openconnect] Installation Failed
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>';
                        }
                    }
                }
            }else{
                $status = '<div class="alert alert-danger alert-dismissible" role="alert">
                                [Openconnect] IP address already registered
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>';
            }
        }else{
            
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
        $qryv = mysqli_query($con,"select * from servers where id='$u'");
        $vrow=mysqli_fetch_array($qryv);
        
        $stype = $vrow['protocol'];
        $servip = $vrow['server_ip'];
        
            $query = mysqli_query($con,"DELETE FROM servers WHERE id='$u'");
            if($query)
            {
                $status2 = '<div class="alert alert-success alert-dismissible" role="alert">
                                [Server] Delete successfully
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>';
            }else{
                $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                                [Server] Delete failure
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>';
                }
        
    }elseif($_POST['action_a'] == 'restart'){
        
        $u = mysqli_real_escape_string($con,$_POST['action_u']);
        $query1 = mysqli_query($con,"SELECT server_ip FROM servers WHERE id='$u'");
        $row1=mysqli_fetch_array($query1);
        $ipadd = $row1['server_ip'];
        $user1 = 'michael';
        $pass1 = '@@michaeldev@@';
        
        if(!$connection1 = ssh2_connect($ipadd, 22)){
            $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                            [Service] Connection Failed
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>';
            }else{
                if(!ssh2_auth_password($connection1,$user1,$pass1)){
                        $status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                                        [Service] Authentication Failed
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>';
                    }else{
                        $restartserv = ssh2_exec($connection1, "sudo service openvpn restart");
                        if($restartserv)
                        {
                        	$status2 = '<div class="alert alert-success alert-dismissible" role="alert">
                                            [Service] Restarted successfully
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>';
                        }else{
                        	$status2 = '<div class="alert alert-danger alert-dismissible" role="alert">
                                            [Service] Restart failure
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>';
                        }
                    }
            }
    }else{
        
    }
}

?>

<?php 
function ip_check($server_ip){
  global $con;
  
  $query =mysqli_query($con,"select * from servers where server_ip='$server_ip'");
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
<title><?php include('extension/title.php'); ?> | Servers</title>
    
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
                <h4 class="mb-0"> Servers</h4>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb pt-0 pr-0 float-left float-sm-right">
              <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
              <li class="breadcrumb-item active">Servers</li>
            </ol>
          </div>
        </div>
      </div>
        
    <div class="row">
        <div class="col-xl-4 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <h5 class="card-title">Add Server</h5>
                    <?php echo $status; ?>
                    <form method="post" class="ui grid form">
                        <div class="row field">
                            <label class="twelve wide column" for="server_name">Server Name</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input id="server_name" name="server_name" type="text" placeholder="Enter server name" autocomplete="off" required/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="server_ip">IP Address</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$" id="server_ip" name="server_ip" type="text" placeholder="Enter server ip" autocomplete="off" required/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="server_user">Username</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input pattern="[a-zA-Z0-9]+" id="server_user" name="server_user" type="text" placeholder="Enter server user" autocomplete="off" required/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="server_pass">Password</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <input id="server_pass" name="server_pass" type="text" placeholder="Enter server pass" autocomplete="off" required/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row field">
                            <label class="twelve wide column" for="protocol">Protocol</label>
                            <div class="twelve wide column">
                                <div class="ui input">
                                    <select class="custom-select custom-select-lg mb-3" id="protocol" name="protocol">
                                        <option value="openvpn" selected>OpenVPN</option>
                                        <option value="openvpnws">OpenVPN WS</option>
                                        <option value="openconnect">OpenConnect</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <label class="twelve wide column"></label>
                            <div class="twelve wide column">
                                <button type="submit" name="addserver" class="btn ml-15" style="color: <?php include('extension/theme_text.php'); ?>; background-color: <?php include('extension/theme.php'); ?>;">Submit</button>
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
                                    <th>Server Name</th>
                                    <th>Server IP</th>
                                    <th>Protocol</th>
                                    <th>Status</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i=1;
                                    $query = mysqli_query($con,"select * from servers");
                                    if(mysqli_num_rows($query)>0){
                                        while($row=mysqli_fetch_array($query)){
                                            $id = $row['id'];
                                            $server_name = $row['server_name'];
                                            $server_date = $row['server_ip'];
                                            $status = $row['status'];
                                            
                                        if($status == 0){
                                            $stat = '<label class="badge badge-danger">Offline</label>';
                                        }else{
                                            $stat = '<label class="badge badge-success">Online</label>';
                                        }
                                        
                                        if($row['protocol'] == 'openvpn'){
                                            $proto = 'OpenVPN';
                                            $modalbutton = '<a href="#" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target=".openvpn">Ports</a>';
                                        }elseif($row['protocol'] == 'openvpnws'){
                                            $proto = 'OpenVPN WS';
                                            $modalbutton = '<a href="#" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target=".openvpnws">Ports</a>';
                                        }elseif($row['protocol'] == 'openconnect'){
                                            $proto = 'OpenConnect';
                                            $modalbutton = '<a href="#" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target=".openconnect">Ports</a>';
                                        }else{
                                            $proto = '';
                                            $modalbutton = '';
                                        }
                                ?>    
                                <tr>
                                    <td><?php echo $server_name; ?></td>
                                    <td><?php echo $server_date; ?></td>
                                    <td><?php echo $proto; ?></td>
                                    <td><?php echo $stat; ?></td>
                                    <td>
                                        <?php echo $modalbutton; ?>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-outline-success btn-sm" disabled onclick="submitForm('restart','<?php echo $id; ?>')">Restart</a>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-outline-danger btn-sm" disabled onclick="submitForm('delete','<?php echo $id; ?>')">Delete</a>
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

<div class="modal fade bd-example-modal-sm openvpn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <div>
                        <h6>OpenVPN Ports</h6>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered p-0">
                    <tbody>
                        <tr>
                            <td>OpenVPN TCP</td>
                            <td>1194</td>
                        </tr>
                        <tr>
                            <td>OpenVPN UDP</td>
                            <td>53</td>
                        </tr>
                        <tr>
                            <td>OpenVPN SSL</td>
                            <td>443</td>
                        </tr>
                        <tr>
                            <td>Socks HTTP</td>
                            <td>80</td>
                        </tr>
                        <tr>
                            <td>Squid Proxy 1</td>
                            <td>8080</td>
                        </tr>
                        <tr>
                            <td>Squid Proxy 2</td>
                            <td>3128</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href='../script/client.ovpn'">Download Config</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm openvpnws" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <div>
                        <h6>OpenVPN Ports</h6>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered p-0">
                    <tbody>
                        <tr>
                            <td>OpenVPN TCP</td>
                            <td>1194</td>
                        </tr>
                        <tr>
                            <td>OpenVPN UDP</td>
                            <td>53</td>
                        </tr>
                        <tr>
                            <td>OpenVPN SSL</td>
                            <td>443</td>
                        </tr>
                        <tr>
                            <td>OpenVPN Websocket</td>
                            <td>80</td>
                        </tr>
                        <tr>
                            <td>Squid Proxy 1</td>
                            <td>8080</td>
                        </tr>
                        <tr>
                            <td>Squid Proxy 2</td>
                            <td>3128</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href='../script/client.ovpn'">Download Config</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm openconnect" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <div>
                        <h6>OpenConnect Ports</h6>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered p-0">
                    <tbody>
                        <tr>
                            <td>OpenConnect TCP</td>
                            <td>1194</td>
                        </tr>
                        <tr>
                            <td>OpenConnect SSL</td>
                            <td>443</td>
                        </tr>
                        <tr>
                            <td>Socks HTTP</td>
                            <td>80</td>
                        </tr>
                        <tr>
                            <td>Squid Proxy 1</td>
                            <td>8080</td>
                        </tr>
                        <tr>
                            <td>Squid Proxy 2</td>
                            <td>3128</td>
                        </tr>
                        <tr>
                            <td>Squid Proxy 2</td>
                            <td>8181</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
              
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
