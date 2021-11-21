<?php
session_start();
include('connect.php');
$userid = $_SESSION['userid'];
if(isset($_SESSION['id']) && $_SESSION['login_type']=='users'){
    
}
else{
    session_destroy();
	echo '<script>window.location.assign("login");</script>';
}
?>


