<?php
    require_once "./src/database_baglanti.php";
    function getCategories(){
        $pdo=connectDatabase();
        $query="SELECT Id, CategoryName from categories";
        $sonuc=$pdo->query($query);
        $pdo=null;
        return $sonuc;
    }

    function getCategory($id){
        $pdo=connectDatabase();
        $query="SELECT Id, CategoryName from categories
            where p.Id=?";

        $stmt=$pdo->prepare($query);
        $stmt->execute([$id]);
        $pdo=null;
        return $stmt;
    }
?>