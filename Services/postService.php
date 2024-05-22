<?php
    require_once "./src/database_baglanti.php";
    function getPosts($page, $datasPerPage=12){
        $offset=($page-1)*$datasPerPage;
        $pdo=connectDatabase();
        $query='from posts p 
        inner join categories c on p.CategoryId=c.Id
        inner join users u on p.UserId=u.Id
        inner join languages l on p.LanguageId=l.Id
        ORDER BY PostDate DESC';
        
        $queryForCount='SELECT count(PostTitle) count '.$query;

        $stmtCount=$pdo->prepare($queryForCount);
        $stmtCount->execute();
        $totalDatas=$stmtCount->fetch();
        
        $pageCount=ceil($totalDatas->count/$datasPerPage);

        $queryForDatas='SELECT p.Id, PostTitle, PostText, PostCodes, PostScore, PostDate, UserId, NameSurname, user_name, DateOfRegister, Score, ImageUrl, LanguageName, CategoryName '.$query.' LIMIT '.$offset.', '.$datasPerPage;

        $datas=$pdo->query($queryForDatas);

        $pdo=null;
        return array(
            "page_count"=> $pageCount,
            "datas"=> $datas->fetchAll()
        );
    }

    function getPost($id){
        $pdo=connectDatabase();
        $query="SELECT p.Id, PostTitle, PostText, PostCodes, PostScore, PostDate, UserId, NameSurname, user_name, DateOfRegister, Score, ImageUrl, LanguageName, CategoryName from posts p 
            inner join categories c on p.CategoryId=c.Id
            inner join users u on p.UserId=u.Id
            inner join languages l on p.LanguageId=l.Id
            where p.Id=?
            ORDER BY PostDate DESC";
        
        $stmt=$pdo->prepare($query);
        $stmt->execute([$id]);
        $pdo=null;
        return $stmt;
    }

    function getPostsByFilters($radioForLanguage, $radioForCategory, $search, $page, $datasPerPage=2){
        $offset=($page-1)*$datasPerPage;

        $pdo=connectDatabase();
        $query='from posts p
            inner join categories c on p.CategoryId=c.Id
            inner join users u on p.UserId=u.Id
            inner join languages l on p.LanguageId=l.Id';
        if(!empty($radioForLanguage) or !empty($radioForCategory) or !empty($search)){
            $query.=' WHERE';
            $and=false;
            if(!empty($radioForLanguage)){
                $query.=' p.LanguageId='.$radioForLanguage;
                $and=true;
            }
            if(!empty($radioForCategory)){
                ($and) ? $query.=' and' : null;
                $query.=' p.CategoryId='.$radioForCategory;
            }
            if(!empty($search)){
                ($and) ? $query.=' and' : null;
                $query.=' PostTitle LIKE "%'.$search.'%"';
            }
        }

        $query.=' ORDER BY PostDate DESC';
        $queryForCount='SELECT count(PostTitle) count '.$query;

        $stmtCount=$pdo->prepare($queryForCount);
        $stmtCount->execute();
        $totalDatas=$stmtCount->fetch();
        $pageCount=ceil($totalDatas->count/$datasPerPage);

        $queryForDatas='SELECT p.Id, PostTitle, PostText, PostCodes, PostScore, PostDate, UserId, NameSurname, user_name, DateOfRegister, Score, ImageUrl, LanguageName, CategoryName '.$query.' LIMIT '.$offset.', '.$datasPerPage;

        $datas=$pdo->query($queryForDatas);

        $pdo=null;
        return array(
            "page_count"=> $pageCount,
            "datas"=> $datas->fetchAll()
        );
    }

    function addPost($postTitle, $postText, $postCodes, $languageId, $categoryId, $userId, $postDate){
        $pdo=connectDatabase();
        $query="INSERT INTO posts(PostTitle, PostText, PostCodes, LanguageId, CategoryId, UserId, PostDate) VALUES(:PostTitle, :PostText, :PostCodes, :LanguageId, :CategoryId, :UserId, :PostDate);";
        
        $stmt=$pdo->prepare($query);
        $stmt->execute(["PostTitle"=>$postTitle, "PostText"=>$postText, "PostCodes"=>$postCodes, "LanguageId"=>$languageId, "CategoryId"=>$categoryId, "UserId"=>$userId, "PostDate"=>$postDate]);
        
        $pdo=null;
        return $stmt;
    }

    function updatePost($id, $postTitle, $postText, $postScore, $postCodes, $languageId, $categoryId, $userId){
        $pdo=connectDatabase();
        $query="UPDATE posts SET PostTitle=:PostTitle, PostText=:PostText, PostCodes=:PostCodes, PostScore=:PostScore, LanguageId=:LanguageId, CategoryId=:CategoryId, UserId=:UserId WHERE Id=:Id;";
        
        $stmt=$pdo->prepare($query);
        $stmt->execute(["Id"=>$id, "PostTitle"=>$postTitle, "PostText"=>$postText, "PostCodes"=>$postCodes, "PostScore"=>$postScore, "LanguageId"=>$languageId, "CategoryId"=>$categoryId, "UserId"=>$userId]);
        
        $pdo=null;
        return $stmt;
        
        // $stmt=mysqli_prepare($GLOBALS["baglanti"],$query);
        // mysqli_stmt_bind_param($stmt,"isssdiii", $id, $postTitle, $postText, $postCodes, $languageId, $categoryId, $userId);
        // $sonuc=mysqli_stmt_execute($stmt);
        // return $sonuc;
    }

    function getPostsOfUser($userId){
        $pdo=connectDatabase();
        $query="SELECT p.Id, PostTitle, PostText, PostCodes, PostScore, PostDate, UserId, NameSurname, user_name, DateOfRegister, Score, LanguageName, CategoryName from posts p
            inner join categories c on p.CategoryId=c.Id
            inner join users u on p.UserId=u.Id
            inner join languages l on p.LanguageId=l.Id
            where u.Id=?";
        
        $stmt=$pdo->prepare($query);
        $stmt->execute([$userId]);

        $pdo=null;
        return $stmt;
    }

    function deletePost($id){
        $pdo=connectDatabase();
        $query="DELETE from posts where Id=:id";
        
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