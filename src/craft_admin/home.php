<?php
session_start();
require('../dbconnect.php');

// 画像 & エージェント名表示用
$stmt = $db->query("SELECT * FROM agents");
$results = $stmt->fetchAll();

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
  <?php foreach ($results as $result) : ?>
    <p><?= $result['agent_name'] ?></p>
    <img src="./<?= $result['agent_pic'] ?>" alt="" style="width: 500px">
    <a href="./edit.php" style="text-decoration: none">
      <button>編集</button>
    </a>
    <a href="" style="text-decoration: none">
      <button>削除</button>
    </a>
    <a href="" style="text-decoration: none">
      <button>申込一覧</button>
    </a>
  <?php endforeach; ?>
</body>
</html>