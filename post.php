<?php
  require_once "Services/postService.php";
  require_once "Services/commentService.php";
  session_start();
  $content="src/postcontent.php";
  $javascriptImport="javascriptImportPost.php";
  require_once "src/layout.php";
?>