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
            WHERE login_email = ?';
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
  <div class="util_fullscreen_container">
    <div class="util_fullscreen reset">
      <h1 class="util_login_title">パスワード再発行</h1>
      <form action="/agent_admin/login/reset.php" method="POST">
        <p class="reset_text">メールアドレス：<?= $email ?></p>
        <div class="util_login_text reset_input">
          <label class="util_login_text--label" for="password">パスワード：</label>
          <input class="util_login_text--box" type="password" name="password" id="password" required>
          <i class="fas fa-eye-slash" id="togglePassword"></i>
        </div>
        <div class="util_login_text reset_input">
          <label class="util_login_text--label" for="password_conf">パスワード確認：</label>
          <input class="util_login_text--box" type="password" name="password_conf" id="password_conf" required>
          <i class="fas fa-eye-slash" id="togglePassword_conf"></i>
        </div>
        <div class="util_login_text reset_input">
          <input type="submit" name="reset" value="リセット" class="util_login_button">
        </div>
      </form>
    </div>
  </div>




</body>
<?php require("../../_footer.php"); ?>

<script>
  const togglePassword = document.getElementById("togglePassword");
  const togglePassword_conf = document.getElementById("togglePassword_conf");
  const password = document.getElementById("password");
  const password_conf = document.getElementById("password_conf");

  togglePassword.addEventListener("click", function() {
    // toggle the type attribute
    let type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);

    // toggle the icon
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });

  togglePassword_conf.addEventListener("click", function() {
    // toggle the type attribute
    let type = password_conf.getAttribute("type") === "password" ? "text" : "password";
    password_conf.setAttribute("type", type);

    // toggle the icon
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });
</script>

</html>