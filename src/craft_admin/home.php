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
  <?php require('../_header.php'); ?>
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
    </div>
    <div class="agent_rightcontainer">
      <h2>
        <div class="agent_titile">

          エージェント管理
        </div>
      </h2>
      <div class="agent_smallrightcontainer">
        <div class="agent_smallrightcontainer_container">
          <div class="agent_smallcontainer_title">エージェント</div>
          <div class="agent_smallcontainer_control">操作</div>

        </div>

        <?php foreach ($results as $result) : ?>
          <div class="agent_all">

            <div class="agent_ind">

              <img src="./images/<?= $result['agent_pic'] ?>" alt="" style="height: 150px">
              <p><?= $result['agent_name'] ?></p>
            </div>
            <div class="agent_buttons">
              <a href="./edit.php?id=<?= $result['id'] ?>" style="text-decoration: none">

                <button class="hensyu">編集</button>
              </a>
              <a href="" style="text-decoration: none">
                <button class="sakujyo">削除</button>
              </a>
              <a href="" style="text-decoration: none">
                <button class="moushikomi">申込一覧</button>
            </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php require('../_footer.php'); ?>
</body>

</html>