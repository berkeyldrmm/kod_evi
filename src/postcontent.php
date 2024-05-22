<?php
          if(isset($_POST["comment"])){
            if($_POST["token"]!=$_SESSION["token"]){
              die("Token hatası.");
            }
            require_once "Services/commentService.php";
            if(isset($_SESSION["logIn"]) and $_SESSION["logIn"]==true){
              if($_POST["commentText"]!=""){
                date_default_timezone_set('Europe/Istanbul');
                addComment($_POST["commentText"],date("Y-m-d H:i:s"),$_POST["id"],$_SESSION["id"]);
                header("Location: post.php?id=".$_POST["id"]);
              }else{
                header("Location: post.php?id=".$_POST["id"]."&commentError=true");
              }
            }
            else{
              header("Location: login.php");
            }
          }
          elseif(isset($_POST["score"])){
            if($_POST["token"]!=$_SESSION["token"]){
              die("Token hatası.");
          }
            require_once "Services/userService.php";
            $post=getPost($_POST["postId"])->fetch();
            if($_SESSION["id"]==$post->UserId){
              header("Location: index.php");
            }else{
              if(isset($_SESSION["logIn"]) and $_SESSION["logIn"]==true){
                $result=Score($_SESSION["id"],$_POST["postId"],(int)$_POST["point"]);
                if(isset($result["error"]) and $result["error"]==true){
                  header("Location: post.php?id=".$_POST["postId"]."&scoreError=".$result["errorMessage"]);
                }
                header("Location: post.php?id=".$_POST["postId"]);
              }else{
                header("Location: login.php");
              }
            }
          }
          elseif(isset($_GET["id"])){
            $post=getPost($_GET["id"])->fetch();
            if(!empty($post)){
              echo '
                <main class="d-grid">
                <div class="row mx-auto my-4 container" style="height: min-content;">
                      <div class="col1 col-5" style="height: 80vh;overflow-y: scroll;">
                          <textarea id="editor">'.$post->PostCodes.'</textarea>
                      </div>
                      <div class="col2 col-7 d-flex">
                          <div style="height: 80vh;width: 100vw;overflow-y: scroll;">
                              <h2 class="text-center">'.$post->PostTitle.'</h2>
                              <div>'.$post->PostText.'</div>
                          </div>
                      </div>
                  </div>
              </main>
            ';
            echo '
            <hr>
            <div class="container" style="margin-top: 35px;">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-grid mb-3 bg-light" style="width:35%; height:130px;">
                  <div class="row">
                    <div class="col-5 d-flex align-items-center">'.((!empty($post->ImageUrl)) ? '<img src="'.$post->ImageUrl.'" class="container-fluid" width="100%" height="100%" style="border-radius: 100%;">' : '<img src="img/user_image.jpg" class="container-fluid" width="100%" height="100%" style="border-radius: 100%;">').'
                    </div>
                    <div class="col-7 d-flex flex-column justify-content-center">
                      <div>'.$post->NameSurname.'&nbsp;&nbsp;<span class="badge rounded-pill bg-primary"><i class="fa-solid fa-mug-hot fa-sm"></i>&nbsp;'.$post->Score.'</span></div>
                      <div>'.$post->DateOfRegister.'</div>
                    </div>  
                  </div>
                </div>
                <div style="border-radius: 20%; width:180px; height: 65px;color:white;" class="bg-primary d-flex justify-content-center align-items-center">
                  <div>Gönderinin puanı: <i class="fa-solid fa-mug-hot fa-sm"></i>&nbsp;'.$post->PostScore.'</div>
                </div>
                <div>'.((isset($_SESSION["id"]) and ($_SESSION["id"]!=$post->UserId)) ? 
                '<form action="post.php" method="post">
                    <button type="submit" name="score" class="btn btn-success" style="height: 65px;">
                      Bu gönderiye puan ver!&nbsp;<i class="fa-solid fa-mug-hot fa-sm"></i>
                    </button>
                    <input type="hidden" name="token" value="'. $_SESSION["token"].'">
                    <input type="hidden" name="postId" value="'.$post->Id.'">
                    <input type="number" name="point" value="100" style="width: 65px;">
                </form>
                ' : '').'</div>
              </div>
            </div>
            ';
           }
           else{
            http_response_code(404);
            include('../404.php');
            exit;
           }
          }
          else{
            http_response_code(404);
            include('../404.php');
            exit;
          }
        
        ?>
        <?php if(isset($_GET["commentError"]) and $_GET["commentError"]==true): ?>
            <div class="alert alert-danger"><?php echo "Yorum kısmı boş geçilmez." ?></div>
            <script>
                setTimeout(() => {
                    $(".alert").css("display","none");
                }, 4000);
            </script>
        <?php endif; $successAdd=null; ?>
        <form action="post.php" method="post" class="form-block container">
          <?php
            echo isset($commentError) ? "
              <div class='alert alert-danger'>
            ".$commentError."</div>
            <script>
                setTimeout(() => {
                    $('.alert').css('display','none');
                }, 2000);
            </script>" : "";
          ?>
          <input type="hidden" name="token" value="<?php echo isset($_SESSION["token"]) ? $_SESSION["token"] : "" ?>">
          <div class="row">
            <div class="col-xs-12">									
              <div class="form-group">
                <textarea class="form-input" name="commentText" required="" placeholder="Yorumunuzu giriniz.."></textarea>
              </div>
            </div>
            <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
            <input type="submit" name="comment" class="btn btn-primary ml-5" style="width: 130px;">
          </div>
        </form>

          <?php

            $comments=getCommentsOfPost($_GET["id"])->fetchAll();
              echo '
                <div class="container">
                <div class="be-comment-block">
                  <h1 class="comments-title">Yorumlar ('.count($comments).')</h1>
              ';

                foreach ($comments as $comment) {
                  echo '
                    <div class="be-comment">
                      <div class="be-img-comment">	
                        <a href="profile.php?id='.$comment->UserId.'">'.((!empty($comment->ImageUrl)) ? '<img src="'.$comment->ImageUrl.'" class="container-fluid" width="100%" height="100%" style="border-radius: 100%;">' : '<img src="img/user_image.jpg" class="container-fluid" width="100%" height="100%" style="border-radius: 100%;">').'</a>
                      </div>
                      <div class="be-comment-content">
                        
                          <span class="be-comment-name">
                            <a href="profile.php?id='.$comment->UserId.'">'.$comment->NameSurname.'</a>
                            </span>
                          <span class="be-comment-time">
                            <i class="fa fa-clock-o"></i>
                            '.$comment->commentdate.'
                          </span>
                  
                        <p class="be-comment-text">
                          '.$comment->CommentText.'
                        </p>
                      </div>
                    </div>
                  ';
                }
              
            echo '
              </div>
              </div>
            ';
             
          ?>