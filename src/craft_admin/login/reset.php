<?php

session_start();
require('../../dbconnect.php');

$email = $_SESSION['email'];

if (isset($_POST['reset'])) {
  $password = sha1($_POST['password']);
  $password_conf = sha1($_POST['password_conf']);
  
  if ($password !== $password_conf) {
    echo 'パスワードが一致していません。';
  } else {
    $sql = 'UPDATE craft_users
            SET password = ?, password_conf = ?
            WHERE email = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($password, $password_conf, $email));
    $stmt = null;
    $db = null;
    header('Location: http://localhost/craft_admin/login/reset_done.php');
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
  <title>Document</title>
</head>
<body>
  <form action="/craft_admin/login/reset.php" method="POST">
    <p><?= $email ?></p>
    <div>
      <label for="password">パスワード：</label>
      <input type="password" name="password" required>
    </div>
    <div>
      <label for="password_conf">パスワード確認：</label>
      <input type="password" name="password_conf" required>
    </div>
    <div>
      <input type="submit" name="reset" value="パスワードリセット">
    </div>
  </form>
</body>
</html>