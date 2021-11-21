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
    	    
    	    $query = mysqli_query($con,"UPDATE users SET user_duration=user_duration-15 WHERE user_duration>0 and is_freeze='0' and device_connected=1");
    	    $query2 = mysqli_query($con,"UPDATE users SET user_duration=0 WHERE user_duration<1 and is_freeze='0' and device_connected=1");
    	    $query3 = mysqli_query($con,"UPDATE users SET user_credits='99999999' WHERE user_id='1' and user_rank='superadmin'");
    	    $query4 = mysqli_query($con,"DELETE FROM users WHERE user_duration<1 and device_connected=1 and (user_rank='normal' OR user_rank='export')");
    	    $query5 = mysqli_query($con,"DELETE FROM radpostauth WHERE id > 0");
    	    
    	    $qry_ = mysqli_query($con,"select user_name from users where user_duration<1");
    	    while($row_ = mysqli_fetch_array($qry_)) {
            $uname = $row_['user_name'];
            
        	$query6 = mysqli_query($con,"DELETE FROM radcheck WHERE username = '$uname'");
            }
        }
    }else{
	    echo '<script> alert("Opss! Invalid request detected! Your IP Address: '.$ip.'"); window.location.href="https://www.facebook.com/Unexpected-Reborn-Internet-Communication-Services-858910614466244/"; </script>';
    }
?>