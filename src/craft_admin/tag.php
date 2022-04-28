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
  <div class="util_container">
    <div class="util_sidebar">
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/home.php">エージェント管理</a>

      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/add_agent.php">エージェント追加</a>
      </div>
      <div class="util_sidebar_button util_sidebar_button-selected">
        <a class="util_sidebar_link util_sidebar_link-selected" href="/craft_admin/tag.php">タグ編集・追加</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
      </div>
    </div>
    <div class="util_content">
      <h2>
        <div class="util_title">
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
        <div id="no<?= $category['id'] ?>" class="tag_content_info none">
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
        <a href="./edit_tag.php?act=add" class="tag_category_add">+ カテゴリーを追加</a>
      </div>
    </div>
  </div>

  <!-- ここからmodal -->
  <div id="modal">
    <div class="modal_container">

      <p class="alert">本当に削除しますか？</p>
      <div class="delete_buttons">
        <button class="no" onclick="modalClose()">いいえ</button>
        <a href="./delete_tag.php?id=<?= $category['id'] ?>" style="text-decoration: none">
          <button class="yes" onclick="deleteAgent()">はい
          </button>
        </a>
      </div>
    </div>
  </div>
  <!-- ここから削除完了画面 -->
  <div id="modal_complete">
    <p>削除されました。</p>
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

  const modal = document.getElementById('modal');

  const modalComplete = document.getElementById('modal_complete');

  function modalOpen() {
    modal.style.display = 'block';
  };

  function modalClose() {
    modal.style.display = 'none';
  };

  function deleteAgent() {
    modal.style.display = 'none';
    modalComplete.style.display = 'block';
  };


  
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