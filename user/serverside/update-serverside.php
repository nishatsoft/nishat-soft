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
	0	=> 'name', 
	1	=> 'filename'
);

$sql = "select id, name, type, filename from updater where 1=1";
$query = mysqli_query($con,$sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' "; 
	$sql.=" OR filename LIKE '%".$requestData['search']['value']."%' ) ";
}

$query = mysqli_query($con,$sql);
$totalFiltered = mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query = mysqli_query($con,$sql);

$data = array();
while($row = mysqli_fetch_array($query)) {
	$nestedData=array();
    
    $id = $row['id'];
    $name = $row['name'];
    $type = $row['type'];
    $filename = $row['filename'];
    
    if($type == 'openvpn'){
        $proto = 'OpenVPN';
    }elseif($type == 'openconnect'){
        $proto = 'OpenConnect';
    }else{
        $proto = '';
    }
                           
	$aydi = "'$id'";
	$delete_ = "'delete'";
	
	$nestedData[] = $name;
	$nestedData[] = $proto;
	$nestedData[] = $filename;
    $nestedData[] = '<div class="dropdown show">
                                          <a class="btn btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: '.$thm_text.'; background-color: '.$thm_color.';">
                                            Action
                                          </a>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            
                                            <a href="../updater/'.$filename.'" class="btn" target="_blank">View Link</a>
                                            <a href="edit_update.php?uid='.$id.'" class="btn">Edit Update</a>
                                            <a class="btn" onclick="submitForm('.$delete_.','.$aydi.')">Delete Update</a>
                                              
                                          </div>
                                        </div>';

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