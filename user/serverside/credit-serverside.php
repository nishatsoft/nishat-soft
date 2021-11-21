<?php
include('../extension/connect.php');
include('../extension/check-login.php');
$userid = $_SESSION['userid'];

$cred = mysqli_query($con,"select user_name, user_rank from users where user_id='$userid'");
$myinfo = mysqli_fetch_array($cred);
$mrank = $myinfo['user_rank'];
$mname = $myinfo['user_name'];

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
	0	=> 'id',
	1	=> 'sender', 
	2	=> 'receiver',
	3	=> 'amount',
	4	=> 'type',
	5	=> 'date',
	6	=> null
);

if($mrank == 'superadmin'){
    $sql = "select * from credits where sender!='$mname' || receiver!='$mname'";
    $query1 = mysqli_query($con,"select * from credits");
}else{
    $sql = "select * from credits where sender='$mname' || receiver='$mname'";
    $query1 = mysqli_query($con,"select * from credits where sender='$mname' || receiver='$mname'");
}

$totalData = mysqli_num_rows($query1);
$totalFiltered = $totalData;

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( id LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR sender LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR receiver LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR type LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR amount LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR date LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = mysqli_query($con,$sql);
$totalFiltered = mysqli_num_rows($query);
$sql.="ORDER BY ".$columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start'].", ".$requestData['length']."";

$query = mysqli_query($con,$sql) or die( mysqli_error($con));

$data = array();
while($row = mysqli_fetch_array($query)) {
	$nestedData=array();
    
    $date = $row['date'];
    $sender = $row['sender'];
    $receiver = $row['receiver'];
    $type = $row['type'];
    $amount = $row['amount'];
	
	$nestedData[] = '<label class="badge" style="background-color: '.$thm_color.'; color: '.$thm_text.';">'.$date.'</label>';
	$nestedData[] = $sender;
	$nestedData[] = $receiver;
	$nestedData[] = $type;
	$nestedData[] = $amount;

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