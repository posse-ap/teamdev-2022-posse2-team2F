<?php
session_start();
require('../../dbconnect.php');

if (isset($_POST['signup'])) {
  $email = $_POST['email'];
  $password = sha1($_POST['password']);
  $password_conf = sha1($_POST['password_conf']);
  
  if ($password !== $password_conf) {
    echo 'パスワードが一致していません。';
  } else {
    $sql = 'INSERT INTO craft_users(email, password, password_conf)
          VALUES(?, ?, ?)';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($email, $password, $password_conf));
    $stmt = null;
    $db = null;
    header('Location: http://localhost/craft_admin/login/signup_done.php');
    exit;
  }
}

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
  <title>管理者新規登録</title>
</head>
<body>
  <?php include '../../_header.php'; ?>

  <div class="util_login_container">
    <div class="util_login">
      <h1 class="util_login_title">新規管理者登録</h1>
      <form action="" method="POST">
        <div class="util_login_text craft_signup">
          <label class="util_login_text--label" for="email">メールアドレス：</label>
          <input class="util_login_text--box" type="email" name="email" required>
        </div>
        <div class="util_login_text craft_signup">
          <label class="util_login_text--label" for="password">パスワード：</label>
          <input class="util_login_text--box" type="password" name="password" required>
        </div>
        <div class="util_login_text craft_signup">
          <label class="util_login_text--label" for="password_conf">パスワード確認：</label>
          <input class="util_login_text--box" type="password" name="password_conf" required>
        </div>
        <div>
          <input type="submit" name="signup" value="新規登録" class="util_login_button">
        </div>
      </form>
    </div>
  </div>
</body>


<?php require("../../_footer.php"); ?>

</html>