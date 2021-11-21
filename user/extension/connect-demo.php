<?php
    $db_host = "66.45.250.53";
	$db_user = "abolirez_soft";
	$db_pass = "abolirez_soft";
	$db_name = "abolirez_soft";
	
	$con = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
	if(mysqli_connect_error()){

		die('Failed');
		
	}
?>