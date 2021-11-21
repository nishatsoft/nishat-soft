<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
include('../user/extension/connect.php');
	
	$con =  mysqli_connect($db_host,$db_user,$db_pass,$db_name);
	if(mysqli_connect_error()){
		echo 'connect to database failed';
	} else { 
	
	
 if(isset($_GET['username'],$_GET['password'],$_GET['device_id'],$_GET['device_model'])){
 	$username = strip_tags(trim($_GET['username']));
 	$password = strip_tags(trim($_GET['password']));
 	$device_id = strip_tags(trim($_GET['device_id']));
 	$device_model = strip_tags(trim($_GET['device_model']));
 
 	if(empty($_GET['username'])){
 		$LenzData[value] = '';
        $LenzData[msg] = 'Error occured.';
        $LenzData[param] = 'username';
        $LenzData[location] = 'query';
        $data[] = $LenzData;
        $json_data = array(
    			        "status" => 'invalid',
    			        "errors" => ( $data ));
        echo json_encode($json_data);
 	}elseif(empty($_GET['password'])){
 		$LenzData[value] = '';
        $LenzData[msg] = 'Error occured.';
        $LenzData[param] = 'password';
        $LenzData[location] = 'query';
        $data[] = $LenzData;
        $json_data = array(
    			        "status" => 'invalid',
    			        "errors" => ( $data ));
        echo json_encode($json_data);
 	}elseif(empty($_GET['device_id'])){
 		$LenzData[value] = '';
        $LenzData[msg] = 'Error occured.';
        $LenzData[param] = 'device_id';
        $LenzData[location] = 'query';
        $data[] = $LenzData;
        $json_data = array(
    			        "status" => 'invalid',
    			        "errors" => ( $data ));
        echo json_encode($json_data);
 	}elseif(empty($_GET['device_model'])){
 		$LenzData[value] = '';
        $LenzData[msg] = 'Error occured.';
        $LenzData[param] = 'device_model';
        $LenzData[location] = 'query';
        $data[] = $LenzData;
        $json_data = array(
    			        "status" => 'invalid',
    			        "errors" => ( $data ));
        echo json_encode($json_data);
 	}elseif(!empty($_GET['username']) && !empty($_GET['password']) && !empty($_GET['device_id']) && !empty($_GET['device_model'])){
 		
 		$qry = mysqli_query($con,"SELECT user_duration FROM users WHERE user_name='".$username."' LIMIT 1");
        $result = mysqli_fetch_array($qry);
        
        function calc_time($minutes) {
    	$days = (int)($seconds / 86400);
    	$seconds -= ($days * 86400);
    	if ($seconds) {
    		$hours = (int)($seconds / 3600);
    		$seconds -= ($hours * 3600);
    	}
    	if ($seconds) {
    		$minutes = (int)($seconds / 60);
    		$seconds -= ($minutes * 60);
    	}
    	$time = array('days'=>(int)$days,
    			'hours'=>(int)$hours,
    			'minutes'=>(int)$minutes,
    			'seconds'=>(int)$seconds);
    	return $time;
        }
        
        $dur = calc_time($result['user_duration']);
        $pdays = $dur['days'] . " days";
		$phours = $dur['hours'] . " hours";
		$pminutes = $dur['minutes'] . " minutes";
		$pseconds = $dur['seconds'] . " seconds";
		
		$user_duration = strtotime($pdays . $phours . $pminutes . $pseconds);
        $user_duration = date('Y-m-d h:i:s', $user_duration);
 		
 		$result2 = mysqli_query($con,"SELECT user_name FROM users WHERE user_name='$username' AND user_pass='$password'");
 		
 		if (mysqli_num_rows($result2)>0){
 		    
 		        $sql2 = mysqli_query($con,"SELECT device_id FROM users WHERE user_name='$username' AND user_pass='$password'");
 		        $result3 = mysqli_fetch_array($sql2);
 		        
 		        $dev_id = $result3['device_id'];
 		        $dev_model = $result3['device_model'];
 		        
         		if($dev_id == ''.$device_id.'' && $dev_id != '') {
         			$json_data = array(
                                    "auth" => true,
                                    "expiry" => $user_duration,
                                    "device_match" => true);
                    echo json_encode($json_data);
                    
                    mysqli_query($con,"UPDATE users SET device_id = '$device_id', device_model = '$device_model', device_connected=1 WHERE user_name='$username'");
         		}else
         		if($dev_id != $device_id && $dev_id != ''){
         		    $json_data = array(
                                    "auth" => true,
                                    "expiry" => $user_duration,
                                    "device_match" => false);
                    echo json_encode($json_data);
         	    }else
         		if($dev_id == ''){
         		    $json_data = array(
                                    "auth" => true,
                                    "expiry" => $user_duration,
                                    "device_match" => true);
                    echo json_encode($json_data);
                    
                    mysqli_query($con,"UPDATE users SET device_id = '$device_id', device_model = '$device_model', device_connected=1 WHERE user_name='$username'");
         	    }else{
         			$json_data = array(
                                    "auth" => false,
                                    "expiry" => none,
                                    "device_match" => none);
                    echo json_encode($json_data);
         		}
 		}else{
         		    
         			$json_data = array(
                                    "auth" => false,
                                    "expiry" => none,
                                    "device_match" => none);
                    echo json_encode($json_data);
         		}
 	}else{
 	    
 		$json_data = array(
                        "auth" => false,
                        "expiry" => none,
                        "device_match" => none);
        echo json_encode($json_data);
 	}
 }else{
     
 	$json_data = array(
                    "auth" => false,
                    "expiry" => none,
                    "device_match" => none);
    echo json_encode($json_data);
 }
	}
 ?>