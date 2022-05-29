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
      <h1 class="util_warning_title">期限切れのリンク</h1>
      <div class="util_warning_body">
        <p class="util_warning_body--text">パスワードリセットURLの有効期限が切れてしまいました。</p>
        <p class="util_warning_body--text">恐れ入りますが、もう一度リセットの申請をお願いいたします。</p>
      </div>
      <a class="util_warning_link" href="./login.php">ログイン画面に戻る</a>
    </div>
  </div>
</body>


<?php require("../../_footer.php"); ?>


</html>