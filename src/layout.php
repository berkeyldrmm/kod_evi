<?php
    require_once "createToken.php";
    header("Access-Control-Allow-Origin: *");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kod_evi</title>
    <link rel="stylesheet" type="text/css" href="lib/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="lib/codemirror-5.65.14/lib/codemirror.css"/>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
</head>
<body>
    <div class="kapak mb-0">
        <header class="d-grid">
            <div class="row header-row mx-auto">
                <div class="col-2 img-fluid d-flex align-items-center justify-content-center">
                    <a href="/kod_evi" class="text-decoration-none d-flex justify-content-center"><img style="width:25%; border-radius: 100%;" src="img/logo1.png"><span class="text-white mt-1" style="font-size: 22px;">&nbsp; Kod Evi</span></a>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-center">
                        <form action="index.php" method="GET" class="searchBox">
                            <input class="searchInput" id="searchInput" type="text" name="search" placeholder="Ne yapmak istiyorsun?">
                            <button type="submit" name="filter" class="searchButton">
                                <i class="fa-solid fa-magnifying-glass">
                                </i>
                            </button>
                        </form>
                </div>
                <?php if(isset($_SESSION["logIn"]) and $_SESSION["logIn"]==true): ?>
                  <?php
                    require_once "Services/userService.php";
                    $GLOBALS["user"]=getUser($_SESSION["id"])->fetch();
                  ?>
                  <div class="col-4 d-flex align-items-center justify-content-center login">
                  <div class="bg-danger p-1"style="border-radius:20%;margin-right:10px;color:white;">
                          Kredi: <i class="fa-solid fa-mug-hot fa-sm"></i>&nbsp;<?php echo $GLOBALS["user"]->Credit; ?>
                        </div>
                        <div class="bg-light p-1"style="border-radius:20%;margin-right:10px;color:black;">
                          Skor: <i class="fa-solid fa-mug-hot fa-sm"></i>&nbsp;<?php echo $GLOBALS["user"]->Score; ?>
                        </div>
                        <div class="dropdown">
                           <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <?php echo $GLOBALS["user"]->user_name; ?>
                           </button>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item" href="postEkle.php">Post Ekle</a>
                              <a class="dropdown-item" href="postlar.php">Postlarım</a>
                              <a class="dropdown-item" href="profile.php">Profil</a>
                              <a style="font-weight:bold; font-size: 16px;" class="dropdown-item" href="logout.php">Çıkış yap</a>
                           </div>
                        </div>
                        
                     </div>
                <?php else: ?>
                  <div class="col-4 d-flex align-items-center justify-content-center login">
                    <a href="login.php"><button class="loginButton"><i class="fa-regular fa-user mr-2"></i> &nbsp; GİRİŞ YAP</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="signup.php"><button class="loginButton"><i class="fa-solid fa-right-to-bracket mr-2"></i> &nbsp; KAYDOL</button></a></button>
                </div>
                <?php endif; ?>
                
            </div>
        </header>
        <?php
            include_once $content
        ?>
        <footer class="footer">
          <div class="container">
              <div class="row">
                  <div class="col-md-5">
                      <h5><img src="img/logo1.png" width="75" height="75" /> &nbsp;&nbsp;Kod Evi</h5>
                      
                      <ul class="nav">
                          <li class="nav-item"><a href="" class="nav-link pl-0"><i class="fa-brands fa-facebook"></i></a></li>
                          <li class="nav-item"><a href="" class="nav-link"><i class="fa-brands fa-instagram"></i></a></li>
                          <li class="nav-item"><a href="" class="nav-link"><i class="fa-brands fa-twitter"></i></a></li>
                          <li class="nav-item"><a href="" class="nav-link"><i class="fa-brands fa-github"></i></a></li>
                      </ul>
                      <br>
                  </div>
                  <div class="col-md-7">
                      <h5 class="text-md-right">Contact Us</h5>
                      <hr>
                      <form>
                          <fieldset class="form-group">
                              <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                          </fieldset>
                          <fieldset class="form-group">
                              <textarea class="form-control" id="exampleMessage" placeholder="Message"></textarea>
                          </fieldset>
                          <fieldset class="form-group text-xs-right">
                              <button type="button" class="btn btn-light btn-lg mt-3">Send</button>
                          </fieldset>
                      </form>
                  </div>
              </div>
          </div>
      </footer>
    <?php
        include_once $javascriptImport;
    ?>
</body>
</html>