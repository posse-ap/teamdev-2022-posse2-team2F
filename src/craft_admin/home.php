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
  <?php require ('../_header.php'); ?>
  <div class="agent_container">
    <div class="agent_leftcontainer">
      <div class="agent_manage">
        エージェント管理
      </div>
      <div class="agent_add">
        <a href="">エージェント追加</a>
      </div>
      <div class="tag_manage">
        <a href="">タグ編集・追加</a>
      </div>
      <div class="usersite">
        <a href="">ユーザー用サイトへ</a>
      </div>
      <div class="sample5-2">サンプル</div>
    </div>
    <div class="agent_rightcontainer">
      <h2>エージェント管理</h2>
      <div class="agent_smallrightcontainer">

        <?php foreach ($results as $result) : ?>
          <p><?= $result['agent_name'] ?></p>
          <img src="./images/<?= $result['agent_pic'] ?>" alt="" style="width: 500px">
          <a href="./edit.php?id=<?= $result['id'] ?>" style="text-decoration: none">
            <button>編集</button>
          </a>
          <a href="" style="text-decoration: none">
            <button>削除</button>
          </a>
          <a href="" style="text-decoration: none">
            <button>申込一覧</button>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
      <?php require ('../_footer.php'); ?>
</body>
</html>