<?php
session_start();
require('../dbconnect.php');

// URLからIDを取得
$id = $_GET['id'];

if (isset($_POST['save'])) {
  $agent_name = $_POST['agent_name'];
  $agent_tag = $_POST['agent_tag'];
  $agent_pic = $_POST['agent_pic'];
  $agent_info = $_POST['agent_info'];
  // $agent_display = $_POST['agent_display'];
  if(isset($_POST['agent_display'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_display = $_POST['agent_display'];
  }
  
  
  $sql = 'UPDATE agents
        SET agent_name = ?, agent_tag = ?, agent_pic = ?, agent_info = ?, agent_display = ?
        WHERE id = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_name, $agent_tag, $agent_pic, $agent_info, $agent_display, $id));
  $stmt = null;
  $db = null;
  header('Location: http://localhost/craft_admin/home.php');
  exit;
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
  <h1>エージェント編集画面</h1>
  <form action="" method="POST">
    <p>
      <label for="agent_name">エージェント名：</label>
      <input type="text" name="agent_name" required>
    </p>
    <p>
      <label for="agent_tag">エージェントタグ：</label>
      <input type="text" name="agent_tag" required>
    </p>
    <p>
      <label for="agent_pic">エージェント画像：</label>
      <input type="file" name="agent_pic" required>
    </p>
    <p>
      <label for="agent_info">エージェント説明：</label>
      <input type="textarea" name="agent_info" required>
    </p>
    <p>
      <label for="agent_display">掲載期間：</label>
      <select name="agent_display">
        <option value="1">1</option>
        <option value="3">3</option>
        <option value="6">6</option>
        <option value="12">12</option>
      </select>
      <span>ヶ月</span>
    </p>
    <p>
      <input type="submit" name="save" value="変更を保存">
    </p>
  </form>
</body>
</html>