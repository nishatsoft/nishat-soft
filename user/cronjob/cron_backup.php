<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require "../extension/config.php";
ini_set('max_execution_time', 150);

 $ip = $db->get_client_ip();
    if($db->get_client_ip() == 'UNKNOWN')
    {
        
    $path = '../backup/';
    $para = array(
    	'db_host'=> $db_host,
    	'db_uname' => $db_user,
    	'db_password' => $db_pass,
    	'db_to_backup' => $db_name,
    	'db_backup_path' => $path,
    	'db_exclude_tables' => array()
    );
    
    $db->__backup_mysql_database($para);

    }else{
	    echo '<script> alert("Opss! Invalid request detected! Your IP Address: '.$ip.'"); window.location.href="https://www.facebook.com/Unexpected-Reborn-Internet-Communication-Services-858910614466244/"; </script>';
    }
?>