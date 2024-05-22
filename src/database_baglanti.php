<?php
    require "config.php";

    function connectDatabase(){
        try {
            $dsn="mysql:host=".host.";dbname=".database;
            $pdo=new PDO($dsn,username,password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
        } catch (Exception $err) {
            echo "Bağlantı oluşturulamadı.".$err->getMessage();
        }
        return $pdo;
    }

    

?>