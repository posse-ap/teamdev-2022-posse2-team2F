<?php
session_start();
require('../../dbconnect.php');

$err_msg = "";


if (isset($_POST['signup'])) {
  $email = $_POST['email'];
  $password = sha1($_POST['password']);
  $password_conf = sha1($_POST['password_conf']);

  if ($password !== $password_conf) {
    $err_msg = "パスワードが一致していません。";
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
  <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
  <title>管理者新規登録</title>
</head>

<body>
  <?php include '../../_header.php'; ?>

  <div class="util_fullscreen_container">
    <div class="util_fullscreen util_login">
      <h1 class="util_login_title">新規管理者登録</h1>
      <?php if ($err_msg !== null && $err_msg !== '') {
          echo "<p class='util_login_error'>" . $err_msg .  "</p>";
      } ?>
      <form action="" method="POST">
        <div class="util_login_text craft_signup">
          <label class="util_login_text--label" for="email">メールアドレス：</label>
          <input class="util_login_text--box" type="email" name="email" required>
        </div>
        <div class="util_login_text craft_signup">
          <label class="util_login_text--label" for="password">パスワード：</label>
          <input class="util_login_text--box" type="password" name="password" id="password" required>
          <i class="fas fa-eye-slash" id="togglePassword"></i>
        </div>
        <div class="util_login_text craft_signup">
          <label class="util_login_text--label" for="password_conf">パスワード確認：</label>
          <input class="util_login_text--box" type="password" name="password_conf" id="password_conf" required>
          <i class="fas fa-eye-slash" id="togglePassword_conf"></i>
        </div>
        <div>
          <input type="submit" name="signup" value="新規登録" class="util_login_button">
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