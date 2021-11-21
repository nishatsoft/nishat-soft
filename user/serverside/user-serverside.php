<?php
include('../extension/connect.php');
include('../extension/check-login.php');
$userid = $_SESSION['userid'];

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

$cred = mysqli_query($con,"select user_rank from users where user_id='$userid'");
$myinfo = mysqli_fetch_array($cred);
$mrank = $myinfo['user_rank'];

$thm = mysqli_query($con,"select theme, theme_text from admin");
$thminfo = mysqli_fetch_array($thm);
$thm_color = $thminfo['theme'];
$thm_text = $thminfo['theme_text'];

$requestData= $_REQUEST;
if(empty($requestData)){
	header("location:dashboard");
	exit;	
}

$columns = array( 
	0	=> 'user_id',
	1	=> 'user_name', 
	2	=> 'user_pass',
	3	=> 'device_id',
	4	=> null
);

$query = mysqli_query($con,"select user_id, user_name, user_pass, user_duration, is_freeze, user_upline, device_connected, is_active, device_id from users where user_rank='normal'");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

if($mrank=='superadmin'){
    $sql = "select user_id, user_name, user_pass, user_duration, is_freeze, user_upline, device_connected, is_active, device_id from users where user_rank='normal'";
}else{
    $sql = "select user_id, user_name, user_pass, user_duration, is_freeze, user_upline, device_connected, is_active, device_id from users where user_rank='normal' and user_upline='$userid'";
}

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( user_id LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR user_name LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR user_pass LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = mysqli_query($con,$sql);
$totalFiltered = mysqli_num_rows($query);
$sql.="ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']." ";

$query = mysqli_query($con,$sql);

$data = array();
while($row = mysqli_fetch_array($query)) {
	$nestedData=array();
    
    $id = $row['user_id'];
    $user_name = $row['user_name'];
    $user_pass = $row['user_pass'];
    $user_duration = $row['user_duration'];
    $freeze = $row['is_freeze'];
    $user_upline = $row['user_upline'];
    $devcon = $row['device_connected'];
    $stats = $row['is_active'];
    $device = $row['device_id'];
                                            
                                        
    $dur = calc_time($user_duration);
    $pdays = $dur['days'] . " days";
    $phours = $dur['hours'] . " hours";
    $pminutes = $dur['minutes'] . " minutes";
    $pseconds = $dur['seconds'] . " seconds";
                                    		
    $user_dur = strtotime($pdays . $phours . $pminutes . $pseconds);
    $user_dur = date('m-d-Y H:m:s', $user_dur);
                                            
    if($devcon == '1' && $freeze == '0' && $user_duration > '0'){
        $status = '<label class="badge badge-success">status:active</label>';
        $dura = $user_dur;
    }elseif($devcon == '0' && $freeze == '0' && $user_duration > '0'){
        $status = '<label class="badge badge-warning">status:inactive</label>';
        $dura = '';
    }elseif($devcon == '1' && $freeze == '0' && $user_duration <= '0'){
        $status = '<label class="badge badge-danger">status:expired</label>';
        $dura = '';
    }elseif($devcon == '1' && $freeze == '1' && $user_duration > '0'){
        $status = '<label class="badge badge-danger">status:blocked</label>';
        $dura = $user_dur;
    }elseif($devcon == '0' && $freeze == '1' && $user_duration > '0'){
        $status = '<label class="badge badge-danger">status:blocked</label>';
        $dura = '';
    }elseif($devcon == '0' && $freeze == '1' && $user_duration <= '0'){
        $status = '<label class="badge badge-danger">status:blocked</label>';
        $dura = '';
    }elseif($devcon == '1' && $freeze == '1' && $user_duration <= '0'){
        $status = '<label class="badge badge-danger">status:blocked</label>';
        $dura = '';
    }
	
	$aydi = "'$id'";
	$renew = "'renew'";
	$block = "'block'";
	$unblock = "'unblock'";
	$delete = "'delete'";
	$password = "'password'";
	$device_ = "'device'";
	
	$nestedData[] = $user_name;
	$nestedData[] = $user_pass;
	$nestedData[] = $dura;
	$nestedData[] = $status;
	$nestedData[] = $device;
    $nestedData[] = '<div class="dropdown show">
                        <a class="btn btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: '.$thm_text.'; background-color: '.$thm_color.';">
                            Action
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="btn" onclick="submitForm('.$renew.','.$id.')">Renew User</a>
                            <a class="btn" onclick="submitForm('.$block.','.$id.')">Block User</a>
                            <a class="btn" onclick="submitForm('.$unblock.','.$id.')">Unblock User</a>
                            <a class="btn" onclick="submitForm('.$delete.','.$id.')">Delete User</a>
                        </div>
                    </div>';
    $nestedData[] = '<a href="#" class="btn btn-outline-success btn-sm" onclick="submitForm('.$password.','.$aydi.')">Reset Password</a>';
    $nestedData[] = '<a href="#" class="btn btn-outline-danger btn-sm" onclick="submitForm('.$device_.','.$id.')">Reset Device</a>';

	$data[] = $nestedData;	
}

$json_data = array(
			"draw"            => intval( $requestData['draw'] )? intval( $_REQUEST['draw'] ) : 0,
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => ($data )
			);

echo json_encode($json_data);
?>