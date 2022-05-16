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
    $sql = 'UPDATE agent_users
            SET password = ?, password_conf = ?
            WHERE email = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($password, $password_conf, $email));
    $stmt = null;
    $db = null;
    header('Location: http://localhost/agent_admin/login/reset_done.php');
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
  <title>パスワードリセット</title>
</head>

<body>
  <?php include '../../_header.php'; ?> 
  <div class="util_login_container">
    <div class="util_login reset">
      <h1 class="util_login_title">パスワード再発行</h1>
      <form action="/agent_admin/login/reset.php" method="POST">
        <p class="reset_text">メールアドレス：<?= $email ?></p>
        <div class="util_login_text reset_input">
          <label class="util_login_text--label" for="password">パスワード：</label>
          <input class="util_login_text--box" type="password" name="password" required>
        </div>
        <div class="util_login_text reset_input">
          <label class="util_login_text--label" for="password_conf">パスワード確認：</label>
          <input class="util_login_text--box" type="password" name="password_conf" required>
        </div>
        <div class="util_login_text reset_input">
          <input type="submit" name="reset" value="リセット" class="util_login_button">
        </div>
      </form>
    </div>
  </div>



  
</body>
<?php require("../../_footer.php"); ?>

</html>