<?php
session_start();
require('../dbconnect.php');

if (isset($_POST['signup'])) {
  $email = $_POST['email'];
  $password = sha1($_POST['password']);
  $password_conf = sha1($_POST['password_conf']);
  // $agent_name = $_POST['agent_name'];
  if(isset($_POST['agent_name'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_name = $_POST['agent_name'];
  }
  
  if ($password !== $password_conf) {
    echo 'パスワードが一致していません。';
  } else {
    $sql = 'INSERT INTO agent_users(email, password, password_conf, agent_name)
          VALUES(?, ?, ?, ?)';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($email, $password, $password_conf, $agent_name));
    $stmt = null;
    $db = null;
    header('Location: http://localhost/agent_admin/signup_done.php');
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
  <h1>新規担当者登録</h1>
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
      <label for="agent_name">所属エージェント：</label>
      <select name="agent_name">
        <option value="agent1">agent1</option>
        <option value="agent2">agent2</option>
        <option value="agent3">agent3</option>
        <option value="agent4">agent4</option>
        <option value="agent5">agent5</option>
        <option value="agent6">agent6</option>
        <option value="agent7">agent7</option>
        <option value="agent8">agent8</option>
        <option value="agent9">agent9</option>
        <option value="agent10">agent10</option>
      </select>
    </p>
    <p>
      <input type="submit" name="signup" value="新規登録">
    </p>
  </form>
</body>
</html>