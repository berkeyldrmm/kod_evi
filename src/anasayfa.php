<div class="sekiller"></div>
<main class="d-grid">
            <div class="row">
                <div class="col-6 d-flex justify-content-center align-items-center">
                    <div class="img-container w-75">
                        <img src="img/index.jpg" class="w-100">
                    </div>
                </div>
                <div class="col-6 animate__animated animate__slideInDown">
                    <div class="row d-flex justify-content-center align-items-end h-50">
                        <div class="slogan">
                            İhtiyacın olan kod daha önce yazıldı.<br>
                            Ve burada...
                        </div>
                    </div>
                    <div class="row d-flex flex-column justify-content-evenly align-items-center h-50">
                        
                        <button class="button w-50" id="searchButton" style="--c: #99ACCC;--b: 5px;--s:12px">NEYE İHTİYACIN VAR ? <i class="fa-solid fa-magnifying-glass"></i></button>
                        <a type="button" href=#kesfet class="button w-50 d-flex justify-content-center align-items-center" style="border:3px solid #CECE5A;text-decoration: none;"><span>KEŞFET</span> <i class="fa-solid fa-chevron-down"></i></a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div class="container-fluid pt-5 mb-5" id="kesfet">
        <div class="row px-xl-5">
            <form action="index.php" method="GET" class="col-2 d-none d-lg-block">
                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Dil</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0" id="navbar-vertical">
                    <div class="navbar-nav w-100 overflow-hidden">

                        <?php
                            require_once "Services/languageService.php";
                            $languages=getLanguages()->fetchAll();
                            foreach($languages as $language):
                        ?>
                            <input type="radio" id=<?php echo "Checkbox".$language->LanguageName ?> name="radioForLanguage" value=<?php echo $language->Id ?>>
                            <label class="nav-item nav-link" for=<?php echo "Checkbox".$language->LanguageName ?>> <?php echo $language->LanguageName ?></label>
                        <?php endforeach; ?>

                    </div>
                </nav>

                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100 mt-4" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Kategori</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0" id="navbar-vertical">
                    <div class="navbar-nav w-100 overflow-hidden">

                        <?php
                            require_once "Services/categoryService.php";
                            $categories=getCategories()->fetchAll();
                            foreach($categories as $category):
                        ?>
                            <input type="radio" id=<?php echo "Checkbox".$category->CategoryName ?> name="radioForCategory" value=<?php echo $category->Id ?>>
                            <label class="nav-item nav-link" for=<?php echo "Checkbox".$category->CategoryName ?>> <?php echo $category->CategoryName ?></label>
                        <?php endforeach; ?>

                    </div>
                </nav>
                <div class="wrap">
                    <div class="search">
                       <input type="text" class="searchTerm" name="search" placeholder="Ne arıyorsun?">
                       <button type="submit" name="filter" class="searchButton">
                         <i class="fa fa-search"></i>
                      </button>
                    </div>
                 </div>
            </form>
            <div class="col-10 d-flex flex-wrap">
            <?php
                $page=1;
                if(isset($_GET["page"]) and is_numeric($_GET["page"])){
                    $page=$_GET["page"];
                }
                if(isset($_GET["filter"])){
                    $posts=getPostsByFilters($_GET["radioForLanguage"], $_GET["radioForCategory"], $_GET["search"], $page);
                }
                else{
                    $posts=getPosts($page);
                }
                foreach($posts["datas"] as $post){
                  echo '
                    <div class="card pt-1 mx-2 codediv" style="height:400px;">
                      <textarea class="editor">'.$post->PostCodes.'</textarea>
                      <div class="card-body">
                        <h5 class="card-title">'.$post->PostTitle.'</h5>
                        <p class="card-text" style="height:60px;">'.$post->PostText.'</p>
                        <div class="mb-3">
                          <div>
                            <a href="profile.php?id='.$post->UserId.'" style="text-decoration:none;color:black;">
                              '.$post->NameSurname.' @'.$post->user_name.'
                            </a>
                          </div>
                          <span class="badge rounded-pill bg-primary"><i class="fa-solid fa-mug-hot fa-sm"></i>&nbsp;'.$post->PostScore.'</span>
                          <span class="badge rounded-pill bg-warning"><i class="fa-solid fa-code"></i>&nbsp;'.$post->LanguageName.'</span>
                          <span class="badge rounded-pill bg-success">'.$post->CategoryName.'</span>
                        </div>
                        <a href="/kod_evi/post.php?id='.$post->Id.'" class="btn btn-primary mx-auto">İncele</a>
                        <span style="float:right;">'.$post->PostDate.'</span>
                      </div>
                    </div>
                    ';
                }
            ?>
            <?php if($posts["page_count"]>1): ?>
                <div class="d-flex justify-content-center w-100 mt-5">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <?php for($i=1;$i<=$posts["page_count"];$i++): ?>
                                <li class="page-item <?php echo (isset($_GET["page"])) ? (($i==$_GET["page"]) ? "active":"") : (($i==1) ? "active" : ""); ?>"><a class="page-link" href="
                                    <?php
                                        if(isset($_GET["filter"])){
                                            $url="";
                                            $and=false;
                                            if(isset($_GET["search"])){
                                                $url.="search=".$_GET["search"];
                                                $and=true;
                                            }
                                            if(isset($_GET["radioForCategory"])){
                                                ($and) ? $url.="&" : null;
                                                $url.="radioForCategory=".$_GET["radioForCategory"];
                                            }
                                            if(isset($_GET["radioForLanguage"])){
                                                ($and) ? $url.="&" : null;
                                                $url.="radioForLanguage=".$_GET["radioForLanguage"];
                                            }
                                            $and=null;
                                            $url.="&filter=";
                                        }
                                        echo "?".$url."&page=$i";
                                    ?>">
                                    <?php echo $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif;?>
            </div>
        </div>
    </div>