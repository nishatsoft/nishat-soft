<?php
include('../extension/connect.php');
include('../extension/check-login.php');
include('../extension/rank_check.php');
$userid = $_SESSION['userid'];

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
	0	=> 'server_name', 
	1	=> 'server_ip'
);

$sql = "select id, server_name, server_ip, status from servers where 1=1";
$query = mysqli_query($con,$sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( server_name LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR server_ip LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = mysqli_query($con,$sql);
$totalFiltered = mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query = mysqli_query($con,$sql);

$data = array();
while($row = mysqli_fetch_array($query)) {
	$nestedData=array();
    
    $id = $row['id'];
    $ip_address = $row['server_name'];
    $hostname = $row['server_ip'];
    $status = $row['status'];
                                            
    if($status == 1){
        $stat = '<label class="badge badge-success">Online</label>';
    }else{
        $stat = '<label class="badge badge-success">Offline</label>';
    }
                           
	$aydi = "'$id'";
	$delete_ = "'delete'";
	
	$nestedData[] = $hostname.'.'.$domain;;
	$nestedData[] = $ip_address;
	$nestedData[] = $stat;
    $nestedData[] = '<td>
                        <a href="/user/edit_dns.php?uid='.$id.'" class="btn btn-outline-success btn-sm">Edit</a>
                    </td>';
    $nestedData[] = '<td>
                        <a href="#" class="btn btn-outline-danger btn-sm" onclick="submitForm('.$delete_.','.$aydi.')">Delete</a>
                    </td>';

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