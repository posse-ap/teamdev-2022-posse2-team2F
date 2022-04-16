<?php
session_start();
require('../dbconnect.php');

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
    header('Location: http://localhost/craft_admin/register.php');
    exit;
  }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザ登録画面</title>
</head>
<body>
  <h1>新規管理者登録</h1>
  <form action="" method="POST">
    <p>
      <label for="email">メールアドレス：</label>
      <input type="email" name="email" required>
    </p>
    <p>
      <label for="password">パスワード：</label>
      <input type="password" name="password" required>
    </p>
    <p>
      <label for="password_conf">パスワード確認：</label>
      <input type="password" name="password_conf" required>
    </p>
    <p>
      <input type="submit" name="signup" value="新規登録">
    </p>
  </form>
</body>
</html>