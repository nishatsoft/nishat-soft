<?php
    session_start();
    if (!isset($_SESSION['userid'])) {
        header("location:user/login");
    }else{
        header("location:user/dashboard");
    }
?>