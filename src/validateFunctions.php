<?php
    require_once "database_baglanti.php";
    function validateUsernameToSignUp($username){
        $query="SELECT id FROM users WHERE user_name=?";

        try {
            $pdo=connectDatabase();
            $sonuc=$pdo->prepare($query);
            $username_trim=trim($username);
            $sonuc->bindParam(1, $username_trim);
            $sonuc->execute();
            if($sonuc->rowCount()>=1){
                return "Bu kullanıcı adına sahip bir kullanıcı zaten var.";
            }
        } catch (Exception $err) {
            return "Bir hata oluştu: ".$err;
        }
        $pdo=null;
    }

    function validateEmailToSignUp($email){
        $query="SELECT id FROM users WHERE Email=?";

        try {
            $pdo=connectDatabase();
            $sonuc=$pdo->prepare($query);
            $email_trim=trim($email);
            $sonuc->execute([$email_trim]);
            if($sonuc->rowCount()>=1){
                return "Bu mail adresiyle zaten bir kullanıcı var.";
            }
        } catch (Exception $err) {
            return "Bir hata oluştu: ".$err->getMessage();
        }
        $pdo=null;
    }

?>