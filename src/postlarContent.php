<div class="container d-flex flex-column align-items-center mt-5" style="border:1px solid grey;">
    <?php
        if(!isset($_SESSION["logIn"])){
          http_response_code(403);
            include('../403.php');
            exit;
        }
        require_once "Services/postService.php";
        $posts=getPostsOfUser($_SESSION["id"])->fetchAll();
        if(count($posts)!=0):
    ?>
        <?php foreach($posts as $post): ?>
            <div class="card pt-1 mx-2 codediv w-75" style="height:450px; margin-top:20px;">
                      <textarea class="editor"><?php echo $post->PostCodes ?></textarea>
                      <div class="card-body">
                        <h5 class="card-title"><?php echo $post->PostTitle ?></h5>
                        <p class="card-text" style="height:60px;"><?php echo $post->PostText ?></p>
                        <div class="mb-3">
                          <div>
                            <a href="profile?id='.$post->UserId.'" style="text-decoration:none;color:black;">
                              <?php echo $post->NameSurname ?>&nbsp;@<?php echo $post->user_name ?>
                            </a>
                          </div>
                          <span class="badge rounded-pill bg-primary"><i class="fa-solid fa-mug-hot fa-sm"></i>&nbsp;<?php echo $post->PostScore ?></span>
                          <span class="badge rounded-pill bg-warning"><i class="fa-solid fa-code"></i>&nbsp;<?php echo $post->LanguageName ?></span>
                          <span class="badge rounded-pill bg-success"><?php echo $post->CategoryName ?></span>
                        </div>
                        <a href="/kod_evi/post.php?id=<?php echo $post->Id ?>" class="btn btn-primary mx-auto">İncele</a>
                        <span style="float:right;"><?php echo $post->PostDate ?></span>
                      </div>
                    </div>
        <?php endforeach ?>

    <?php else: ?>
        Paylaştığınız hiç gönderiniz yok...
    <?php endif; ?>
        
</div>