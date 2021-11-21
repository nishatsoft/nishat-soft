<?php
    {
        $query_user53 = mysqli_query($con,"select logo from admin");
        $result53 = mysqli_fetch_array($query_user53);
        $logo_name = $result53['logo'];
        $logo7 = '/assets/images/logo/'.$logo_name.'';
    }
?>
<?php echo $logo7; ?>