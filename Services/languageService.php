<?php
    require_once "./src/database_baglanti.php";
    function getLanguages(){
        $pdo=connectDatabase();
        $query="SELECT Id, LanguageName from languages";
        $sonuc=$pdo->query($query);
        $pdo=null;
        return $sonuc;
    }

    function getLanguage($id){
        $pdo=connectDatabase();
        $query="SELECT Id, LanguageName from languages
            where p.Id=?";

        $stmt=$pdo->prepare($query);
        $stmt->execute([$id]);
        $pdo=null;
        return $stmt;
    }

    // function addPost($postTitle, $postText, $postCodes, $postScore, $languageId, $categoryId, $userId){
    //     $pdo=connectDatabase();
    //     $query="INSERT INTO posts(PostTitle, PostText, PostCodes, PostScore, LanguageId, CategoryId, UserId) VALUES(:PostTitle, :PostText, :PostCodes, :PostScore, :LanguageId, :CategoryId, :UserId);";
        
    //     $stmt=$pdo->prepare($query);
    //     $stmt->execute(["PostTitle"=>$postTitle, "PostText"=>$postText, "PostCodes"=>$postCodes, "PostScore"=>$postScore, "LanguageId"=>$languageId, "CategoryId"=>$categoryId, "UserId"=>$userId]);
        
    //     $pdo=null;
    //     return $stmt;
    // }

    // function updatePost($id, $postTitle, $postText, $postScore, $postCodes, $languageId, $categoryId, $userId){
    //     $pdo=connectDatabase();
    //     $query="UPDATE posts SET PostTitle=:PostTitle, PostText=:PostText, PostCodes=:PostCodes, PostScore=:PostScore, LanguageId=:LanguageId, CategoryId=:CategoryId, UserId=:UserId WHERE Id=:Id;";
        
    //     $stmt=$pdo->prepare($query);
    //     $stmt->execute(["Id"=>$id, "PostTitle"=>$postTitle, "PostText"=>$postText, "PostCodes"=>$postCodes, "PostScore"=>$postScore, "LanguageId"=>$languageId, "CategoryId"=>$categoryId, "UserId"=>$userId]);
        
    //     $pdo=null;
    //     return $stmt;
        
    //     // $stmt=mysqli_prepare($GLOBALS["baglanti"],$query);
    //     // mysqli_stmt_bind_param($stmt,"isssdiii", $id, $postTitle, $postText, $postCodes, $languageId, $categoryId, $userId);
    //     // $sonuc=mysqli_stmt_execute($stmt);
    //     // return $sonuc;
    // }

    // function deletePost($id){
    //     $pdo=connectDatabase();
    //     $query="DELETE from posts where Id=:id";
        
    //     $stmt=$pdo->prepare($query);
    //     $stmt->execute(["id"=>$id]);
        
    //     $pdo=null;
    //     return $stmt;

    //     // $stmt=mysqli_prepare($GLOBALS["baglanti"],$query);
    //     // mysqli_stmt_bind_param($stmt,"i", $id);
    //     // $sonuc=mysqli_stmt_execute($stmt);
    //     // return $sonuc;
    // }
?>