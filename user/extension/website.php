<?php
    {
        $query_user1 = mysqli_query($con,"select * from admin");
        $result1 = mysqli_fetch_array($query_user1);
        $website_name = $result1['website'];
    }
?>
<?php echo $website_name; ?>