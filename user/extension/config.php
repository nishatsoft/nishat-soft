<?php
include('connect.php');
include "mysql.class.php";

$query_bk = mysqli_query($con,"select website, title_name from admin");
$result1 = mysqli_fetch_array($query_bk);
$website_name = $result1['website'];
$title = $result1['title_name'];

$db = new mysql_db();
$db->InitDB($db_host,$db_user,$db_pass,$db_name);
$db->SetWebsiteName(''.$website_name.'');
$db->SetWebsiteTitle(''.$title.'');
?>