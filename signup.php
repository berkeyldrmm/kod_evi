<?php
    if(!empty($_POST["signup"])){
        if(empty($_POST["nameSurname"])){
            $nameSurname_error="Lütfen isim soyisminizi giriniz.";
        }elseif(strlen($_POST["nameSurname"])>45){
            $nameSurname_error="Bu isim çok uzun.";
        }

        if(empty($_POST["username"])){
            $username_error="Lütfen username alanını doldurun.";
        }elseif(!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $_POST["username"])){
            $username_error="Kullanıcı adı sadece büyük harf, küçük harf ve sayıdan oluşabilir.";
        }elseif(strlen($_POST["username"])>45){
            $username_error="Bu kullanıcı adı çok uzun.";
        }else{
            require_once "src/validateFunctions.php";
            $username_error=validateUsernameToSignUp($_POST["username"]);
        }

        if(empty($_POST["email"])){
            $email_error="Lütfen email alanını doldurun.";
        }elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            $email_error="Lütfen geçerli bir mail adresi giriniz.";
       }elseif(strlen($_POST["email"])>50){
            $email_error="Bu email çok uzun.";
        }else{
            require_once "src/validateFunctions.php";
            $email_error=validateEmailToSignUp($_POST["email"]);
        }

        if(empty($_POST["password"])){
            $password_error="Lütfen password alanını doldurun.";
        }

        if(empty($_POST["passwordConfirm"])){
            $passwordConfirm_error="Lütfen şifreyi tekrar giriniz.";
        } else{
            $uppercase = preg_match('@[A-Z]@', $_POST["password"]);
            $lowercase = preg_match('@[a-z]@', $_POST["password"]);
            $number    = preg_match('@[0-9]@', $_POST["password"]);
            
            if(!$uppercase || !$lowercase || !$number || strlen($_POST["password"]) < 8) {
                $password_error="Şifreniz en az 8 karakterden oluşup, bir büyük harf, bir küçük harf ve bir sayısal karakter içermelidir.";
            }
            elseif($_POST["password"]!==$_POST["passwordConfirm"]){
                $passwordConfirm_error="Şifreler uyuşmuyor.";
            }
        }

        if(!isset($nameSurname_error) and !isset($username_error) and !isset($email_error) and !isset($password_error) and !isset($passwordConfirm_error)){
            require_once "Services/userService.php";
            require_once "src/functions.php";
            try {
                $sonuc2=addUser(safe_html($_POST["nameSurname"]),safe_html($_POST["username"]),safe_html($_POST["email"]),password_hash(safe_html($_POST["password"]),PASSWORD_DEFAULT),date("Y-m-d"));
                header("Location: login.php");
            } catch (Exception $err) {
                echo "Bir hata oluştu: ".$err->getMessage();
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Giriş Yap</title>
  <link rel="stylesheet" href="./css/style2.css">
  <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
/>
</head>
<body>
    <section class="container">
        <div class="login-container animate__animated animate__zoomIn">
            <div class="circle circle-one"></div>
            <div class="form-container">
                <img src="https://raw.githubusercontent.com/hicodersofficial/glassmorphism-login-form/master/assets/illustration.png" alt="illustration" class="illustration" />
                <h1 class="opacity">Üye ol</h1>
                <form action="signup.php" method="post">
                    <?php if(isset($nameSurname_error)): ?>
						<span class="text-danger mt-0"> <?php echo $nameSurname_error ?> </span><br>
					<?php endif ?>
                    <label for="nameSurname">İsim soyisim</label>
                    <input type="text" name="nameSurname" placeholder="İsim soyisim" <?php if(!empty($_POST["nameSurname"])){echo "value=".$_POST["nameSurname"];} ?> />
                    
                    <?php if(isset($email_error)): ?>
						<span class="text-danger mt-0"> <?php echo $email_error ?> </span><br>
					<?php endif ?>
                    <label for="email">Mail adresi</label>
                    <input type="email" name="email" placeholder="Mail adresi" <?php if(!empty($_POST["email"])){echo "value=".$_POST["email"];} ?> />
                    
                    <?php if(isset($username_error)): ?>
						<span class="text-danger mt-0"> <?php echo $username_error ?> </span><br>
					<?php endif ?>
                    <label for="username">Kullanıcı adı</label>
                    <input type="text" name="username" placeholder="Kullanıcı adı" <?php if(!empty($_POST["username"])){echo "value=".$_POST["username"];} ?> />
                    
                    <?php if(isset($password_error)): ?>
						<span class="text-danger mt-0"> <?php echo $password_error ?> </span><br>
					<?php endif ?>
                    <label for="password">Şifre</label>
                    <input type="password" name="password" placeholder="Şifre" />
                    
                    <?php if(isset($passwordConfirm_error)): ?>
						<span class="text-danger mt-0"> <?php echo $passwordConfirm_error ?> </span><br>
					<?php endif ?>
                    <label for="passwordConfirm">Şifre tekrar</label>
                    <input type="password" name="passwordConfirm" placeholder="Şifre" />

                    <input type="submit" name="signup" class="opacity" value="Üye ol"/>
                </form>
            </div>
            <div class="circle circle-two"></div>
        </div>
        <div class="theme-btn-container"></div>
    </section>
    <script  src="./js/script2.js"></script>
</body>
</html>
