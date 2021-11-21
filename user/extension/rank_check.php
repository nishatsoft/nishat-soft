<?php
include('extension/connect.php');

$rankqry = mysqli_query($con,"select user_rank from users where user_id='$userid'");
$rankrow=mysqli_fetch_array($rankqry);
$rrank = $rankrow['user_rank'];

if ($rrank == 'superadmin') {

}else{
    header("location:dashboard");
}
?>