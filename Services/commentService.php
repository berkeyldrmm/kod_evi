<?php
    require_once "./src/database_baglanti.php";
    function getComments(){
        $pdo=connectDatabase();
        $query="SELECT CommentText, commentdate, NameSurname, user_name, Score, DateOfRegister from comments c
        inner join users u on c.UserId=c.Id";
        $sonuc=$pdo->query($query);
        $pdo=null;
        return $sonuc;
    }

    function getComment($id){
        $pdo=connectDatabase();
        $query="SELECT CommentText, commentdate, NameSurname, user_name, Score, DateOfRegister from comments c
        inner join users u on c.UserId=c.Id
        where c.Id=?";
        
        $stmt=$pdo->prepare($query);
        $stmt->execute([$id]);

        $pdo=null;
        return $stmt;
    }

    function getCommentsOfPost($postId){
        $pdo=connectDatabase();
        $query="SELECT CommentText, commentdate, c.UserId, NameSurname, user_name, Score, ImageUrl, DateOfRegister from comments c
        inner join users u on c.UserId=u.Id
        inner join posts p on p.Id=c.PostId
        where c.PostId=?";
        
        $stmt=$pdo->prepare($query);
        $stmt->execute([$postId]);

        $pdo=null;
        return $stmt;
    }

    function addComment($CommentText, $commentdate, $PostId, $UserId){
        $pdo=connectDatabase();
        $query="INSERT INTO comments(CommentText, commentdate, PostId, UserId) VALUES(:CommentText, :commentdate, :PostId, :UserId);";

        $stmt=$pdo->prepare($query);
        $stmt->execute(["CommentText"=>$CommentText, "commentdate"=>$commentdate, "PostId"=>$PostId, "UserId"=>$UserId]);
        
        $pdo=null;
        return $stmt;

    }

    function updateComment($CommentText, $commentdate, $UserId, $CommentId){
        $pdo=connectDatabase();
        $query="UPDATE comments SET CommentText=:CommentText, commentdate=:commentdate, UserId=:UserId WHERE Id=:Id;";
        
        $stmt=$pdo->prepare($query);
        $stmt->execute(["CommentText"=>$CommentText, "commentdate"=>$commentdate, "UserId"=>$UserId, "Id"=>$CommentId]);
        $pdo=null;
        return $stmt;
        
        // $stmt=mysqli_prepare($GLOBALS["baglanti"],$query);
        // mysqli_stmt_bind_param($stmt,"ssii", $CommentText, $commentdate, $UserId, $CommentId);
        // $sonuc=mysqli_stmt_execute($stmt);
        // return $sonuc;
    }

    function deleteComment($id){
        $pdo=connectDatabase();
        $query="DELETE from comments where Id=:id";

        $stmt=$pdo->prepare($query);
        $stmt->execute(["id"=>$id]);
        $pdo=null;
        return $stmt;

        // $stmt=mysqli_prepare($GLOBALS["baglanti"],$query);
        // mysqli_stmt_bind_param($stmt,"i", $id);
        // $sonuc=mysqli_stmt_execute($stmt);
        // return $sonuc;
    }
?>