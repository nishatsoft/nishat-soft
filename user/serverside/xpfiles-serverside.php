<?php
include('../extension/connect.php');
include('../extension/check-login.php');
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
	0	=> 'user_name'
);

if($mrank=='superadmin'){
    $sql = "select * from users 
    where 1=1 and user_rank='export' 
    and user_created in ( select user_created from users
        group by user_created having count(*) > 0 ) group by user_created";
}else{
    $sql = "select * from users 
    where 1=1 and user_upline='$userid' 
    and user_rank='export' 
    and user_created in ( select user_created from users
        group by user_created having count(*) > 0 ) group by user_created";
}
$query = mysqli_query($con,$sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

if( !empty($requestData['search']['value']) ) { 
	$sql.=" AND ( user_name LIKE '%".$requestData['search']['value']."%' ) "; 
}

$query = mysqli_query($con,$sql);
$totalFiltered = mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$query = mysqli_query($con,$sql);

$data = array();
while($row = mysqli_fetch_array($query)) {
	$nestedData=array();
    
    $datecreate = $row['user_created'];
	
	if($mrank=='superadmin'){
        $qr = mysqli_query($con,"select * from users where user_rank='export' and user_created='$datecreate'");
        $qr2 = mysqli_query($con,"select * from users where user_rank='export' and user_created='$datecreate' ORDER BY user_id ASC LIMIT 1");
    }else{
        $qr = mysqli_query($con,"select * from users where user_rank='export' and user_upline='$userid' and user_created='$datecreate'");
        $qr2 = mysqli_query($con,"select * from users where user_rank='export' and user_upline='$userid' and user_created='$datecreate' ORDER BY user_id ASC LIMIT 1");
    }
    $tot = mysqli_num_rows($qr);

    while($row3=mysqli_fetch_array($qr2)){
        $firstuser = $row3['user_name'];
    }
    while($row2=mysqli_fetch_array($qr)){
        $lastuser = $row2['user_name'];
    }
	
	$nestedData[] = $tot;
	$nestedData[] = $firstuser;
	$nestedData[] = $lastuser;
	$nestedData[] = $datecreate;
    $nestedData[] = '<td>
                        <form method="post">
                            <input type="hidden" name="download" value="true">
                            <input type="hidden" name="start" value="'.$firstuser.'">
                            <input type="hidden" name="end" value="'.$lastuser.'">
                            <input type="hidden" name="date" value="'.$datecreate.'">
                            <input type="hidden" name="filetype" value="csv">
                            <button type="submit" name="exp" class="btn btn-outline-success btn-sm">Download</button>
                        </form>
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