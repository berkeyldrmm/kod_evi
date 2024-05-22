<?php
    require_once "Services/categoryService.php";
    require_once "Services/languageService.php";
    if(isset($_SESSION["logIn"]) and $_SESSION["logIn"]==true){

        if(isset($_POST["postEkle"])){
            if($_POST["token"]!=$_SESSION["token"]){
                die("Token hatası.");
            }
            if(empty($_POST["postTitle"])){
                $postTitleError="Lütfen gönderiniz için bir başlık giriniz.";
            }
            if(empty($_POST["postContent"])){
                $postContentError="Lütfen gönderinizin içeriğini giriniz.";
            }
            if(empty($_POST["postCode"])){
                $postCodeError="Lütfen gönderinizde paylaşacağınız kodu yazınız.";
            }
            if(empty($_POST["postLanguage"])){
                $postLanguageError="Lütfen gönderinizde paylaşacağınız kodun yazıldığı dili seçiniz.";
            }
            if(empty($_POST["postCategory"])){
                $postCategoryError="Lütfen gönderinizin hangi alanla ilgili olduğunu seçiniz.";
            }

            if(!isset($postTitleError) and !isset($postContentError) and !isset($postCodeError) and !isset($postLanguageError) and !isset($postCategoryError)){
                require_once "Services/postService.php";
                require_once "src/functions.php";
                try {
                    date_default_timezone_set('Europe/Istanbul');
                    $sonuc2=addPost(safe_html($_POST["postTitle"]),safe_html($_POST["postContent"]),safe_html($_POST["postCode"]),safe_html($_POST["postLanguage"]),safe_html($_POST["postCategory"]),$_SESSION["id"],date("Y-m-d H:i:s"));
                    $successAdd="Yeni post başarıyla eklendi.";
                } catch (Exception $err) {
                    echo "Bir hata oluştu: ".$err->getMessage();
                }
            }
        }
    }else{
        http_response_code(403);
        include('../403.php');
        exit;
    }
?>
<div id="postEkleDiv">
    <form action="postEkle.php" method="POST" class="decor">
        <div class="form-left-decoration"></div>
        <div class="form-right-decoration"></div>
        <div class="circle"></div>
        <input type="hidden" name="token" value="<?php echo $_SESSION["token"] ?>" />
        <?php if(isset($successAdd)): ?>
            <div class="alert alert-success"><?php echo $successAdd ?></div>
            <script>
                setTimeout(() => {
                    $(".alert").css("display","none");
                }, 2000);
            </script>
        <?php endif; $successAdd=null; ?>
        <div class="form-inner">
            <h1>Post ekle</h1>
            <?php if(isset($postTitleError)): ?>
				<span class="text-danger mt-0"> <?php echo $postTitleError ?> </span><br>
			<?php endif ?>
            <label class="form-label">Post Başlığı</label>
            <input type="text" name="postTitle" class="form-control" <?php echo isset($_POST["postTitle"]) ? "value=".$_POST["postTitle"] : "" ?> >

            <?php if(isset($postContentError)): ?>
				<span class="text-danger mt-0"> <?php echo $postContentError ?> </span><br>
			<?php endif ?>
            <label class="form-label">Post İçeriği</label>
            <textarea class="form-control" name="postContent"><?php echo isset($_POST["postContent"]) ? $_POST["postContent"] : "" ?></textarea>

            <?php if(isset($postCodeError)): ?>
				<span class="text-danger mt-0"> <?php echo $postCodeError ?> </span><br>
			<?php endif ?>
            <label class="form-label">Post Kod</label>
            <textarea id="editor" name="postCode" class="form-control" style="height:300px;"><?php echo isset($_POST["postCode"]) ? $_POST["postCode"] : "" ?></textarea>

            <?php if(isset($postLanguageError) or isset($postCategoryError)): ?>
				<span class="text-danger mt-0">
                    <?php echo (isset($postLanguageError)) ? $postLanguageError : "" ?>
                    <?php echo (isset($postCategoryError)) ? $postCategoryError : "" ?>
                </span>
			<?php endif ?>
            <div class="d-flex justify-content-center mt-3">

                <label class="form-label mr-2 pt-1">Dil</label>
                <select class="form-select w-25" name="postLanguage" style="margin-left:15px; margin-right:35px; background-color:#d0dfe8;">
                    <?php $languages=getLanguages()->fetchAll();
                        foreach ($languages as $language):
                    ?>
                        <option value=<?php echo $language->Id ?>><?php echo $language->LanguageName ?></option>
                    <?php endforeach; ?>

                </select>

                <label class="form-label mr-2 pt-1">Kategori</label>
                <select class="form-select w-25" name="postCategory" style="margin-left:15px; background-color:#d0dfe8;">
                    <?php $categories=getCategories()->fetchAll();
                        foreach ($categories as $category):
                    ?>
                        <option value=<?php echo $category->Id ?>><?php echo $category->CategoryName ?></option>
                    <?php endforeach; ?>

                </select>

            </div>
            <input type="submit" class="mt-3" name="postEkle" value="Paylaş"/>
        </div>
    </form>
</div>