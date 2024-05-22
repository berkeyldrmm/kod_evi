<?php

    if(!empty($_POST["login"])){
        if(empty($_POST["username"])){
			$username_error="Lütfen kullanıcı adınızı giriniz.";
		}

		if(empty($_POST["password"])){
			$password_error="Lütfen şifrenizi giriniz.";
		}

        if(!isset($username_error) and !isset($password_error)){

			include "src/database_baglanti.php";
			$query="SELECT id, user_name, PasswordHash FROM users WHERE user_name=?";
            $pdo=connectDatabase();
				if($stmt = $pdo->prepare($query)){
					$username_trim=trim($_POST["username"]);
					$stmt->bindParam(1, $username_trim);

					if($stmt->execute()){
						if($stmt->rowCount()==1){
							$stmt->bindColumn("id", $id);
							$stmt->bindColumn("user_name", $username);
							$stmt->bindColumn("PasswordHash",  $hashed_password);

							if($stmt->fetch()){
								$result=password_verify($_POST["password"],$hashed_password);
								
								if($result){
									require_once "Services/userService.php";
									session_start();
									$_SESSION["id"]=$id;
									setcookie("username",$username,time()+(60*60*24));
									$_SESSION["logIn"]=true;

									header("Location: index.php");
								}
								else{
									$loginError = "Kullanıcı adı ya da şifre yanlış.";
								}
							}
							else{
                                $errors=$pdo->errorInfo();
								$loginError = "Bir hata oluştu.".$errors[0];
							}
						}
						else{
							$loginError = "Kullanıcı adı ya da şifre yanlış.";
						}
					}
					else{
						$errors=$pdo->errorInfo();
						$loginError = "Bir hata oluştu.".$errors[0];
					}
				}
				else{
					$errors=$pdo->errorInfo();
					$loginError = "Bir hata oluştu.".$errors[0];
				}
                $pdo=null;
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
<!-- partial:index.partial.html -->
<body>
    <section class="container">
        <div class="login-container animate__animated animate__zoomIn">
            <div class="circle circle-one"></div>
            <div class="form-container">
                <img src="https://raw.githubusercontent.com/hicodersofficial/glassmorphism-login-form/master/assets/illustration.png" alt="illustration" class="illustration" />
                <h1 class="opacity">Giriş Yap</h1>
				<?php if(isset($loginError)): ?>
						<span class="bg-danger mt-0"> <?php echo $loginError ?> </span>
				<?php endif ?>
                <form action="login.php" method="post">

                    <?php if(isset($username_error)): ?>
						<span class="text-danger mt-0"> <?php echo $username_error ?> </span>
					<?php endif ?>
                    <label for="username">Kullanıcı adı</label>
                    <input type="text" name="username" placeholder="Kullanıcı adı" <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];}elseif(isset($_COOKIE["username"])){echo "value=".$_COOKIE["username"];} ?>>

                    <?php if(isset($password_error)): ?>
						<span class="text-danger mt-0"> <?php echo $password_error ?> </span>
					<?php endif ?>
                    <label for="password">Şifre</label>
                    <input type="password" name="password" placeholder="Şifre" >

                    <input type="submit" name="login" class="opacity" value="Giriş Yap" />
                </form>
                <div class="register-forget opacity">
                    <a href="signup.php">Üye ol</a>
                    <a href="">Şifremi unuttum?</a>
                </div>
            </div>
            <div class="circle circle-two"></div>
        </div>
        <div class="theme-btn-container"></div>
    </section>
</body>
<!-- partial -->

</body>
</html>
