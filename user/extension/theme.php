<?php
    {
        $query_user7 = mysqli_query($con,"select * from admin");
        $result7 = mysqli_fetch_array($query_user7);
        $theme = $result7['theme'];
    }
?>
<?php echo $theme; ?>