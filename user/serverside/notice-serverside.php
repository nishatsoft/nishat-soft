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
	0	=> 'download_title', 
	1	=> 'download_type',
	2	=> 'download_date'
);

$sql = "select id, download_title, download_msg, download_type, download_date from download where 1=1";
$query = mysqli_query($con,$sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( ip_address LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR hostname LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR domain LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = mysqli_query($con,$sql);
$totalFiltered = mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query = mysqli_query($con,$sql);

$data = array();
while($row = mysqli_fetch_array($query)) {
	$nestedData=array();
    
    $id = $row['id'];
    $download_title = $row['download_title'];
    $download_msg = $row['download_msg'];
    $download_type = $row['download_type'];
    $download_date = $row['download_date'];

	$aydi = "'$id'";
	$delete_ = "'delete'";
	
	$nestedData[] = $download_title;
	$nestedData[] = $download_type;
	$nestedData[] = $download_date;
    $nestedData[] = '<td>
                        <a href="/user/edit_notice.php?uid='.$id.'" class="btn btn-outline-success btn-sm">Edit</a>
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