<?php
	include('../extension/connect.php');
	require "../extension/config.php";
	
	$ip = $db->get_client_ip();
    if($db->get_client_ip() == 'UNKNOWN')
    {
    	$con =  mysqli_connect($db_host,$db_user,$db_pass,$db_name);
    	if(mysqli_connect_error()){
    		echo 'connect to database failed';
    	} else {
    	
        	$server_check = mysqli_query($con,"SELECT server_ip FROM servers ORDER BY server_name ASC");
        	while($server_row=mysqli_fetch_array($server_check)){
        		$server_ip = $server_row['server_ip'];
        		$servers = @fsockopen($server_ip, 22, $errno, $errstr, 5);
        		if(!$servers)
        		{
        			$chk_server_parser = '0';
        		}else{
        			$chk_server_parser = '1';
        		}
        		$server_qry = mysqli_query($con,"UPDATE servers SET status = '$chk_server_parser' WHERE server_ip = '$server_ip'");
        	}
        	
        	$dns_check= mysqli_query($con,"SELECT ip_address FROM dns ORDER BY hostname ASC");
        	while($dns_row=mysqli_fetch_array($dns_check)){
        		$dns_ip = $dns_row['ip_address'];
        		$servers = @fsockopen($dns_ip, 22, $errno, $errstr, 2);
        		if(!$servers)
        		{
        			$chk_dns_parser = '0';
        		}else{
        			$chk_dns_parser = '1';
        		}
        		$dns_qry = mysqli_query($con,"UPDATE dns SET status = '$chk_dns_parser' WHERE ip_address = '$dns_ip'");
        	}
    	}
    }else{
	    echo '<script> alert("Opss! Invalid request detected! Your IP Address: '.$ip.'"); window.location.href="https://www.facebook.com/Unexpected-Reborn-Internet-Communication-Services-858910614466244/"; </script>';
    }
?>