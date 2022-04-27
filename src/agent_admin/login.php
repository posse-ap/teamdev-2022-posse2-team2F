<?php
session_start();
require('../dbconnect.php');

$err_msg = "";

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = sha1($_POST['password']);
  
  $sql = 'SELECT count(*) FROM agent_users WHERE email = ? AND password = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($email, $password));
  $result = $stmt->fetch();
  $stmt = null;
  $sql_for_session = 'SELECT * FROM agent_users WHERE email = ? AND password = ?';
  $stmt_for_session = $db->prepare($sql_for_session);
  $stmt_for_session->execute(array($email, $password));
  $login_info = $stmt_for_session->fetch();
  $db = null;

  // result に一つでも値が入っているなら、ログイン情報が存在するということ
  if ($result[0] != 0) {
    // 成功した場合管理画面に遷移
    header('Location: http://localhost/agent_admin/home.php');
     //DBのユーザー情報をセッションに保存
    $_SESSION['id'] = $login_info['id'];
    $_SESSION['name'] = $login_info['agent_name'];
    exit;
  } else {
    $err_msg = "ユーザー名またはパスワードが間違っています";
  }

}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <title>担当者ログイン</title>
</head>

<body>
  <div class="login_box">
    <h1 class="login_title">担当者ログイン</h1>
    <form action="/agent_admin/login.php" method="POST">
    <?php if ($err_msg !== null && $err_msg !== '') { echo $err_msg .  "<br>";} ?>
    <p>
      <label for="email">メールアドレス</label>
      <input class="login_textbox" type="email" name="email" required>
    </p>
    <p>
      <label for="password">パスワード</label>
      <input class="login_textbox" type="password" name="password" required>
    </p>
    <input type="submit" name="login" value="ログイン" class="login_button">
    </form>
    <a class="login_new" href="./signup.php">パスワードをお忘れの方はこちら</a>
    <br>
    <a class="login_new" href="./signup.php">新規登録はこちら</a>
  </div>
</body>

</html>