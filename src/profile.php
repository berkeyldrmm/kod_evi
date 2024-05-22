<?php
    require_once "Services/userService.php";
    if(isset($_GET["id"])){
        $user=getUser($_GET["id"])->fetch();
        if(empty($user)){
            http_response_code(404);
            include('../404.php');
            exit;
        }
    }
    elseif(isset($_SESSION["logIn"]) and $_SESSION["logIn"]==true){
        $user=getUser($_SESSION["id"])->fetch();
    }else{
        
    }

    if(isset($_POST["editProfile"])){
        if(!isset($_POST["token"])){
            die("token bulunamadı..");
        }
        if($_POST["token"]!=$_SESSION["token"]){
            die("Token hatası.");
        }
        if(empty($_POST["username"])){
            $editProfileUsernameError="Kullanıcı adı boş olamaz.";
        }elseif(!preg_match('/^[A-Za-z][A-Za-z0-9]{5,31}$/', $_POST["username"])){
            $editProfileUsernameError="Kullanıcı adı sadece büyük harf, küçük harf ve sayıdan oluşabilir.";
        }elseif(strlen($_POST["username"])>45){
            $editProfileUsernameError="Bu kullanıcı adı çok uzun.";
        }elseif($_POST["username"]!=$user->user_name){
            require_once "src/validateFunctions.php";
            $editProfileUsernameError=validateUsernameToSignUp($_POST["username"]);
        }

        if(empty($_POST["email"])){
            $editProfileEmailError="Lütfen email alanını doldurun.";
        }elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            $editProfileEmailError="Lütfen geçerli bir mail adresi giriniz.";
       }elseif(strlen($_POST["email"])>50){
            $editProfileEmailError="Bu email çok uzun.";
        }elseif($_POST["email"]!=$user->Email){
            require_once "src/validateFunctions.php";
            $editProfileEmailError=validateEmailToSignUp($_POST["email"]);
        }
        if(!isset($editProfileEmailError) and !isset($editProfileUsernameError)){
            try {
                updateUser($_SESSION["id"], $_POST["username"], $_POST["email"], $_POST["profession"],$_POST["about"]);
                $successAdd="Bilgileriniz başarıyla güncellendi.";
            } catch (Exception $err) {
                echo $err->getMessage();
            }
        }
        else{
            echo $editProfileEmailError;
            echo $editProfileUsernameError;
        }
    }
?>

<div class="container emp-profile">
        <?php if(isset($successAdd)): ?>
            <div class="alert alert-success"><?php echo $successAdd ?></div>
            <script>
                setTimeout(() => {
                    $(".alert").css("display","none");
                }, 2000);
            </script>
        <?php endif; $successAdd=null; ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-img">
                            <?php
                                if(!empty($user->ImageUrl)){
                                    echo '<img src="'.$user->ImageUrl.'" alt=""/>';
                                }
                                else{
                                    echo '<img src="img/user_image.jpg" alt=""/>';
                                }
                            ?>
                            <?php if(!isset($_GET["id"]) or (isset($_SESSION["id"]) and $_GET["id"]==$_SESSION["id"])): ?>
                                <div class="file btn btn-lg btn-primary">
                                    Fotoğraf Değiştir
                                    <input type="file" name="file"/>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-head">
                                    <h5>
                                        <?php echo $user->NameSurname ?>
                                    </h5>
                                    <p class="proile-rating"><span class="badge rounded-pill bg-danger text-light">Kredi: <i class="fa-solid fa-mug-hot fa-sm"></i>&nbsp;<?php echo $GLOBALS["user"]->Credit; ?></span><span class="badge rounded-pill bg-primary text-light" style="margin-left:10px;">Skor: <i class="fa-solid fa-mug-hot fa-sm"></i>&nbsp;<?php echo $user->Score ?></span></p>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Kişisel</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Hesap</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php if(!isset($_GET["id"]) or (isset($_SESSION["id"]) and $_GET["id"]==$_SESSION["id"])): ?>
                        <div class="col-md-2">
                            <button class="profile-edit-btn" id="editButton" value="Profili düzenle">Profili düzenle</button>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-work">
                            <p>Paylaşım yapılan alanlar</p>
                            <?php if(!getPostofCategoryCountOfUser($user->Id)["rows"]): ?>
                                -
                            <?php else: ?>
                            <?php $rows=getPostofCategoryCountOfUser($user->Id)["rows"]; foreach($rows as $row): ?>
                                <a href=""><?php echo $row->CategoryName ?></a><br/>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <p>Paylaşım yapılan diller</p>
                            <?php if(!getPostofCategoryCountOfUser($user->Id)["rows"]): ?>
                                -
                            <?php else: ?>
                            <?php $rows=getPostofLanguageCountOfUser($user->Id)["rows"]; foreach($rows as $row): ?>
                                <a href=""><?php echo $row->LanguageName ?></a><br/>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content profile-tab" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Kullanıcı Adı</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $user->user_name ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Email</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $user->Email ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Kayıt Tarihi</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $user->DateOfRegister ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Uzmanlık Alanı</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo empty($user->Profession) ? "-" : $user->Profession ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Hakkında</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo empty($user->About) ? "-" : $user->About ?></p>
                                            </div>
                                        </div>
                            </div>
                            <?php if(!isset($_GET["id"]) or (isset($_SESSION["id"]) and $_GET["id"]==$_SESSION["id"])): ?>
                                <div class="tab-pane fade show" id="editProfile" role="tabpanel" aria-labelledby="home-tab" style="display:none;">
                                        <form action="profile.php" method="post">
                                            <input type="hidden" name="token" value="<?php echo $_SESSION["token"] ?>">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Kullanıcı Adı</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php if(isset($editProfileUsernameError)): ?>
                                                        <span class="text-danger mt-0"> <?php echo $editProfileUsernameError ?> </span><br>
                                                    <?php endif ?>
                                                    <p><input class="form-control" name="username" type="text" value="<?php echo $user->user_name ?>"/></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Email</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php if(isset($editProfileEmailError)): ?>
                                                        <span class="text-danger mt-0"> <?php echo $editProfileEmailError ?> </span><br>
                                                    <?php endif ?>
                                                    <p><input class="form-control" name="email" type="email" value="<?php echo $user->Email ?>"/></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Kayıt Tarihi</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><?php echo $user->DateOfRegister ?></p>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Uzmanlık Alanı</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><input class="form-control" name="profession" type="text" value="<?php echo $user->Profession ?>"/></p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Hakkında</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><textarea class="form-control" name="about"><?php echo $user->About ?></textarea></p>
                                                </div>
                                            </div>
                                            <div class="row d-flex justify-content-end">
                                                <button type="submit" name="editProfile" class="btn btn-primary w-25" style="margin-right:10px;">Kaydet</button>
                                            </div>
                                        </form>
                                </div>
                            <?php endif; ?>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Yapılan Yorumlar</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo getCommentCountOfUser($user->Id) ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Yapılan Paylaşımlar</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo getPostCountOfUser($user->Id) ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>En çok paylaşım yapılan alan</label>
                                            </div>
                                            <div class="col-md-6">
                                            <p><?php echo (!getPostofCategoryCountOfUser($user->Id)["firstOne"]) ? "Henüz hiç yaplayım yapılmamış." : getPostofCategoryCountOfUser($user->Id)["firstOne"]->CategoryName ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>En çok paylaşım yapılan dil</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo (!getPostofLanguageCountOfUser($user->Id)["firstOne"]) ? "Henüz hiç yaplayım yapılmamış." : getPostofLanguageCountOfUser($user->Id)["firstOne"]->LanguageName ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Alınan Yorumlar</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo getCommentsCountOfUser($user->Id) ?></p>
                                            </div>
                                        </div>
                            </div>
                        </div>
                    </div>
                </div>      
        </div>