<?php
    if(count($_POST)===0 and isset($_SESSION["logIn"])){
        $_SESSION["token"]=md5(time().rand(0,999999));
    }
?>