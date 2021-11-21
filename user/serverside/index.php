<?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header("location:login");
    }else{
        header("location:dashboard");
    }
?>