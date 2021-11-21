<?php
	include('../extension/connect.php');
	
	$con =  mysqli_connect($db_host,$db_user,$db_pass,$db_name);
	if(mysqli_connect_error()){
		echo 'connect to database failed';
	} else {
	    
	    $query = mysqli_query($con,"UPDATE admin SET title_name='NISHAT VPN', website='nishatsoft.com', theme='rgb(0, 114, 216)', theme_text='rgb(240, 240, 240)', maintenance='0', logo='unex.png'");
	}
?>