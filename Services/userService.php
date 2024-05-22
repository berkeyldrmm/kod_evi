<?php
    require_once "./src/database_baglanti.php";
    function getUsers(){
        $pdo=connectDatabase();
        $query="SELECT NameSurname, user_name, Email, DateOfRegister, Score, Credit, Profession, About, ImageUrl from users";

        $sonuc=$pdo->query($query);
        $pdo=null;
        return $sonuc;
    }

    function getUser($id){
        $pdo=connectDatabase();
        $query="SELECT Id, NameSurname, user_name, Email, DateOfRegister, Score, Credit, Profession, About, ImageUrl from users
            where users.Id=?";
        
        $stmt=$pdo->prepare($query);
        $stmt->execute([$id]);
        $pdo=null;
        return $stmt;
    }

    function addUser($NameSurname, $Username, $Email, $PasswordHash, $DateOfRegister){
        $pdo=connectDatabase();
        $query="INSERT INTO users(NameSurname, user_name, Email, PasswordHash, DateOfRegister) VALUES(:NameSurname, :Username, :Email, :PasswordHash, :DateOfRegister);";
        
        $stmt=$pdo->prepare($query);
        $stmt->execute(["NameSurname"=>$NameSurname, "Username"=>$Username, "Email"=>$Email, "PasswordHash"=>$PasswordHash, "DateOfRegister"=>$DateOfRegister]);
        
        $pdo=null;
        return $stmt;
        
        // $stmt=mysqli_prepare($GLOBALS["baglanti"],$query);
        // mysqli_stmt_bind_param($stmt,"ssssssd", $NameSurname, $Username, $Email, $DateOfBirth, $PasswordHash, $DateOfRegister, $Score);
        // $sonuc=mysqli_stmt_execute($stmt);
        // return $sonuc;
    }

    function updateUser($id, $Username, $Email, $Profession, $About){
        $pdo=connectDatabase();
        $query="UPDATE users SET user_name=:Username, Email=:Email, Profession=:Profession, About=:About WHERE Id=:Id;";
        
        $stmt=$pdo->prepare($query);
        $stmt->execute(["Username"=>$Username, "Email"=>$Email, "Id"=>$id, "Profession"=>$Profession, "About"=>$About]);
        
        $pdo=null;
        return $stmt;
        
        // $stmt=mysqli_prepare($GLOBALS["baglanti"],$query);
        // mysqli_stmt_bind_param($stmt,"ssssssdi", $NameSurname, $Username, $Email, $DateOfBirth, $PasswordHash, $DateOfRegister, $Score, $userId);
        // $sonuc=mysqli_stmt_execute($stmt);
        // return $sonuc;
    }

    function deleteUser($id){
        $pdo=connectDatabase();
        $query="DELETE from users where Id=:id";

        $stmt=$pdo->prepare($query);
        $stmt->execute(["id"=>$id]);
        
        $pdo=null;
        return $stmt;

        // $stmt=mysqli_prepare($GLOBALS["baglanti"],$query);
        // mysqli_stmt_bind_param($stmt,"i", $id);
        // $sonuc=mysqli_stmt_execute($stmt);
        // return $sonuc;
    }

    function getCommentCountOfUser($userId){
        $pdo=connectDatabase();
        $query="SELECT c.Id from comments c
        inner join users u on c.UserId=u.Id
        inner join posts p on p.Id=c.PostId
        where u.Id=?";

        $stmt=$pdo->prepare($query);
        $stmt->execute([$userId]);
        
        $pdo=null;
        return $stmt->rowCount();
    }

    function getPostCountOfUser($userId){
        $pdo=connectDatabase();
        $query="SELECT p.Id from posts p
        inner join users u on p.UserId=u.Id
        where u.Id=?";

        $stmt=$pdo->prepare($query);
        $stmt->execute([$userId]);
        
        $pdo=null;
        return $stmt->rowCount();
    }

    function getCommentsCountOfUser($userId){
        $pdo=connectDatabase();
        $query="SELECT c.Id from comments c
        inner join posts p on c.PostId=p.Id
        where p.UserId=?";

        $stmt=$pdo->prepare($query);
        $stmt->execute([$userId]);
        
        $pdo=null;
        return $stmt->rowCount();
    }

    function getPostofCategoryCountOfUser($userId){
        $pdo=connectDatabase();
        $query="SELECT CategoryName, count(CategoryName) c from posts p
        inner join categories c on p.CategoryId=c.Id
        inner join users u on p.UserId=u.Id
        where u.Id=?
        GROUP BY CategoryId
        ORDER BY c DESC
        LIMIT 4";

        $stmt=$pdo->prepare($query);
        $stmt->execute([$userId]);
        $categories=$stmt->fetchAll();
        $pdo=null;
        return array(
            "firstOne" => (empty($categories[0])) ? false : $categories[0],
            "rows" => (empty($categories)) ? false : $categories
        );
    }

    function getPostofLanguageCountOfUser($userId){
        $pdo=connectDatabase();
        $query="SELECT LanguageName, count(LanguageName) c from posts p
        inner join languages l on p.LanguageId=l.Id
        inner join users u on p.UserId=u.Id
        where u.Id=?
        GROUP BY LanguageId
        ORDER BY c DESC
        LIMIT 4";

        $stmt=$pdo->prepare($query);
        $stmt->execute([$userId]);
        $languages=$stmt->fetchAll();
        $pdo=null;
        return array(
            "firstOne" => (empty($languages[0])) ? false : $languages[0],
            "rows" => (empty($languages)) ? false : $languages
        );
    }

    function Score($userId, $postId, $credit){
        $user=getUser($userId)->fetch();
        if($user->Credit<$credit){
            return array(
                "error"=>true,
                "errorMessage"=>"Yeterli krediniz bulunmamakta."
            );
        }
        if(0>=$credit){
            return array(
                "error"=>true,
                "errorMessage"=>"Lütfen 0'dan büyük bir puan veriniz."
            );
        }
        else{
            $pdo=connectDatabase();
            $queryUser="UPDATE users SET Credit=Credit-:credit WHERE Id=:id";
            $queryPost="UPDATE posts SET PostScore=PostScore+:postScore WHERE Id=:id";

            $stmtUser=$pdo->prepare($queryUser);
            $stmtPost=$pdo->prepare($queryPost);
            
            try {
                $stmtUser->execute(["credit"=>$credit, "id"=>$userId]);
                $stmtPost->execute(["postScore"=>$credit, "id"=>$postId]);
                
            } catch (Exception $err) {
                return array(
                    "error"=> true,
                    "errorMessage"=> $err->getMessage()
                );
            }
            
            $pdo=null;
        }
    }
?>