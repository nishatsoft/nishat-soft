<?php
    {
        $query_user8 = mysqli_query($con,"select * from admin");
        $result8 = mysqli_fetch_array($query_user8);
        $themetxt = $result8['theme_text'];
    }
?>
<?php echo $themetxt; ?>