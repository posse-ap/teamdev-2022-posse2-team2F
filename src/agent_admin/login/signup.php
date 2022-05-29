<?php
session_start();
require('../../dbconnect.php');

$sql = 'SELECT * FROM agents';
$stmt = $db->query($sql);
$results = $stmt->fetchAll();


if (isset($_POST['signup'])) {
  $login_email = $_POST['login_email'];
  $contract_email = $_POST['contract_email'];
  $notify_email = $_POST['notify_email'];
  $password = sha1($_POST['password']);
  $password_conf = sha1($_POST['password_conf']);
  // $agent_name = $_POST['agent_name'];
  if (isset($_POST['agent_name'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_name = $_POST['agent_name'];
  }

  if ($password !== $password_conf) {
    echo 'パスワードが一致していません。';
  } else {
    $sql = 'INSERT INTO agent_users(login_email, contract_email, notify_email, password, password_conf, agent_name)
          VALUES(?, ?, ?, ?, ?, ?);
          ';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($login_email, $contract_email, $notify_email, $password, $password_conf, $agent_name));
    $stmt = null;
    $db = null;
    header('Location: http://localhost/agent_admin/login/signup_done.php');
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
  <title>ユーザ登録画面</title>
</head>

<body>
  <?php include '../../_header.php'; ?>
  <div class="util_fullscreen_container">
    <div class="util_fullscreen util_login">
      <h1 class="util_login_title">新規担当者登録</h1>
      <form action="" method="POST">
        <div class="agent_signup">
          <label class="agent_signup--label" for="login_email">ログイン用メールアドレス：</label>
          <input class="agent_signup--input" type="email" name="login_email" required>
        </div>
        <div class="agent_signup">
          <label class="agent_signup--label" for="contract_email">契約用メールアドレス：</label>
          <input class="agent_signup--input" type="email" name="contract_email" required>
        </div>
        <div class="agent_signup">
          <label class="agent_signup--label" for="notify_email">通知用メールアドレス：</label>
          <input class="agent_signup--input" type="email" name="notify_email" required>
        </div>
        <div class="agent_signup">
          <label class="agent_signup--label" for="password">パスワード：</label>
          <input class="agent_signup--input" type="password" name="password" id="password" required>
          <i class="fas fa-eye-slash" id="togglePassword"></i>
        </div>
        <div class="agent_signup">
          <label class="agent_signup--label" for="password_conf">パスワード確認：</label>
          <input class="agent_signup--input" type="password" name="password_conf" id="password_conf" required>
          <i class="fas fa-eye-slash" id="togglePassword_conf"></i>
        </div>
        <div class="agent_signup">
          <label class="agent_signup--label" for="agent_name">所属エージェント：</label>
          <select class="agent_signup--select" name="agent_name">
            <?php foreach ($results as $result) : ?>
              <option value="<?= $result['agent_name']; ?>"><?= $result['agent_name']; ?></option>
              <!-- <option value="agent1">agent1</option>
            <option value="agent2">agent2</option>
            <option value="agent3">agent3</option>
            <option value="agent4">agent4</option>
            <option value="agent5">agent5</option>
            <option value="agent6">agent6</option>
            <option value="agent7">agent7</option>
            <option value="agent8">agent8</option>
            <option value="agent9">agent9</option>
            <option value="agent10">agent10</option> -->
            <?php endforeach; ?>
          </select>
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