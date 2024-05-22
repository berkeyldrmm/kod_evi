<?php
    session_start();
    $_SESSION=array();
    session_destroy();
    $GLOBALS["user"]=null;
    header("Location: login.php");
?>