<?php
session_start();
require('../../dbconnect.php');

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/normalize.css">
  <link rel="stylesheet" href="/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <title>管理者登録完了画面</title>
</head>


<body>
  <?php include '../../_header.php'; ?>
  <div class="util_fullscreen_container">
    <div class="util_fullscreen util_fullscreen--small">
      <h1 class="util_login_title util_login_title--long">新規管理者登録</h1>
      <div class="signup_done">
        <p class="signup_done_text">新規管理者登録が完了いたしました。</p>
        <a class="util_login_link" href="./login.php">ログイン画面に戻る</a>
      </div>
    </div>
  </div>
</body>


<?php require("../../_footer.php"); ?>


</html>