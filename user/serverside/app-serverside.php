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
	0	=> 'platform', 
	1	=> 'filename',
	2	=> 'download_count'
);

$sql = "select id, platform, filename, download_count, date from application where 1=1";
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
    $platform = $row['platform'];
    $filenam = $row['filename'];
    $download_count = $row['download_count'];
    $dat = $row['date'];
    
    if($dat == '0000-00-00 00:00:00'){
        $date = '-';
    }else{
        $date = $dat;
    }
    
    if($filenam == ''){
        $filename = '-';
        $dcount = '-';
    }else{
        $filename = $filenam;
        $dcount = $download_count;
    }
                           
	$aydi = "'$id'";
	$delete_ = "'delete'";
	
	$nestedData[] = $platform;
	$nestedData[] = $filename;
	$nestedData[] = $dcount;
	$nestedData[] = $date;
    $nestedData[] = '<td>
                        <a href="/user/edit_application.php?uid='.$id.'" class="btn btn-outline-success btn-sm">Update</a>
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