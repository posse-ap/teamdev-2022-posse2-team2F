<?php
session_start();
require('../dbconnect.php');


// タグカテゴリーを表示
$stmt = $db->query("SELECT * FROM tag_categories");
// $stmt = $db->query("SELECT * FROM tags;");
$categories = $stmt->fetchAll();


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
  <div class="craftall_container">
    <div class="craftall_leftcontainer">
      <div class="craftall_manage">
        <a href="/craft_admin/home.php">エージェント管理</a>

      </div>
      <div class="craftall_agent_add">
        <a href="/craft_admin/add.php">エージェント追加</a>
      </div>
      <div class="craftall_tag_manage box_selected">
        <a class="selected" href="/craft_admin/tag.php">タグ編集・追加</a>
      </div>
      <div class="craftall_usersite">
        <a href="">ユーザー用サイトへ</a>
      </div>
    </div>
    <div class="craftall_rightcontainer">
      <h2>
        <div class="agent_title">
        タグの編集・追加
        </div>
      </h2>
      
      <div class="tag_content">
        <div class="tag_content_labels">
          <div class="tag_content_title">タグのカテゴリー名</div>
          <div class="tag_content_control">操作</div>
        </div>

        <?php foreach ($categories as $category) : ?>
        <div class="tag_content_categories">
          <div class="tag_content_ind">
            <p><?= $category['tag_category'] ?></p>
          </div>
          <div class="tag_content_buttons">
            <a href="./edit_tag.php?id=<?= $category['id'] ?>" style="text-decoration: none">
              <button class="hensyu">編集</button>
            </a>
            <button class="sakujyo" onclick="modalOpen()">削除</button>
            <button class="shousai" onclick="clickfunction(<?= $category['id'] ?>)">詳細</button>
          </div>
        </div>
        <div id="no<?= $category['id'] ?>" class="none">
            <p>タグのカテゴリーの説明：</p>
            <p style="color: red"><?= $category['tag_category_desc'] ?></p>
            
            <p>このカテゴリーのタグの一覧：</p>
            <?php
            // タグ内容を表示
            $stmt = $db->prepare("SELECT * FROM tag_options WHERE category_id = ?");
            $stmt->execute(array($category['id']));
            $tags = $stmt->fetchAll();
            ?>
            <?php foreach ($tags as $tag) : ?>
            <p style="color: red"><?= $tag['tag_option'] ?></p>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  


  
  
  
  

  <?php require('../_footer.php'); ?>

<script>

//ボタンをクリックした時の処理
let clickfunction = function (id) {
      let more = document.getElementById(`no${id}`);
      if (more.classList.contains("none")) {
              more.classList.add("block");
              more.classList.remove("none");
          } else {
              more.classList.add("none");
              more.classList.remove("block");
          }
  }
// }

  
</script>

<style>
  .none {
    display: none;
  }

  .block {
    display: block;
  }
</style>

</body>

</html>