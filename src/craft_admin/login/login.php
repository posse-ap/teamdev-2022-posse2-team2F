<?php
session_start();
require('../../dbconnect.php');
// require('/dbconnect.php');


$err_msg = "";

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = sha1($_POST['password']);

  $sql = 'SELECT count(*) FROM craft_users WHERE email = ? AND password = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($email, $password));
  $result = $stmt->fetch();

  $sql_session = "SELECT * FROM craft_users WHERE email = ? AND password = ?";
  $stmt = $db->prepare($sql_session);
  $stmt->execute(array($email, $password));
  $login_info = $stmt->fetch();



  // result に一つでも値が入っているなら、ログイン情報が存在するということ
  if ($result[0] != 0) {
    // 成功した場合管理画面に遷移

    $_SESSION['id'] = $login_info['id'];
    $_SESSION['email'] = $login_info['email'];

    header('Location: http://localhost/craft_admin/home.php');
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
  <link rel="stylesheet" href="/css/normalize.css">
  <link rel="stylesheet" href="/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
  <title>管理者ログイン</title>
</head>

<body>
  <?php include '../../_header.php'; ?>
  <div class="util_fullscreen_container">
    <div class="util_fullscreen util_login">
      <h1 class="util_login_title">管理者ログイン</h1>
      <?php if ($err_msg !== null && $err_msg !== '') {
        echo $err_msg .  "<br>";
      } ?>
      <form action="/craft_admin/login/login.php" method="POST">
        <div class="util_login_text">
          <label class="util_login_text--label" for="email">メールアドレス</label>
          <input class="util_login_text--box" type="email" name="email" required>
        </div>
        <div class="util_login_text">
          <label class="util_login_text--label" for="password">パスワード</label>
          <input class="util_login_text--box" type="password" name="password" id="password" required>
          <i class="fas fa-eye-slash" id="togglePassword"></i>
        </div>
        <input type="submit" name="login" value="ログイン" class="util_login_button">
      </form>
      <div>
        <a class="util_login_link" href="./signup.php">新規登録はこちら</a>
      </div>
      <br>
      <div>
        <a class="util_login_link" href="./forget.php">パスワードをお忘れの方はこちら</a>
      </div>
    </div>
  </div>
</body>

<?php require("../../_footer.php"); ?>

<script>
  const togglePassword = document.getElementById("togglePassword");
  const password = document.getElementById("password");

  togglePassword.addEventListener("click", function() {
    // toggle the type attribute
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);

    // toggle the icon
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });
</script>

</html>