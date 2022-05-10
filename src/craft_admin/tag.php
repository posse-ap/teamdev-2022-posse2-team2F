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
  <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
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
      <div class="util_sidebar_button util_sidebar_button--selected">
        <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/tag.php">タグ編集・追加</a>
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
      
      <div class="tag-list">
        <div class="tag-list_labels">
          <div class="tag-list_labels--left">タグのカテゴリー名</div>
          <div class="tag-list_labels--right">操作</div>
        </div>

        <?php foreach ($categories as $category) : ?>
        <div class="tag-categories">
          <div class="tag-categories_info">
            <p class="tag-categories_info--name"><?= $category['tag_category'] ?></p>
          </div>
          <div class="tag-categories_buttons">
            <a href="./edit_tag_category.php?id=<?= $category['id'] ?>">
              <button class="util_action_button util_action_button--edit">編集</button>
            </a>
            <button class="util_action_button util_action_button--delete" onclick="deleteModal(<?= $category['id'] ?>)">削除</button>
            <button class="util_action_button util_action_button--list" onclick="clickfunction(<?= $category['id'] ?>)">詳細</button>
          </div>
          <!-- ここからmodal -->
          <div id="util_deletemodal<?= $category['id'] ?>" class="util_modalcont">
            <div class="util_deletemodal">

              <p class="util_deletemodal_alert">本当に削除しますか？</p>
              <div class="util_deletebuttons">
                <button class="util_deletebuttons_item util_deletebuttons_item--no" onclick="closeFunction(<?= $category['id'] ?>)">いいえ</button>
                <a href="./delete_tag.php?id=<?= $category['id'] ?>" style="text-decoration: none">
                  <!-- <button class="yes" onclick="deleteAgent()">はい -->
                  <button class="util_deletebuttons_item util_deletebuttons_item--yes" onclick="deleteFunction(<?= $category['id'] ?>)">はい
                  
                  </button>
                </a>
              </div>
            </div>
          </div>
          <!-- ここから削除完了画面 -->
          <div id="util_modalcont<?= $category['id'] ?>" class="util_modalcont">
            <p class="util_modalcont_text">削除されました。</p>
          </div>
        </div>
        <div id="no<?= $category['id'] ?>" class="tag-more none">
            <p class="tag-more_label">タグのカテゴリーの説明：</p>
            <div class="tag-more_desc">
              <p class="tag-more_desc--text"><?= $category['tag_category_desc'] ?></p>
            </div>
            
            <p class="tag-more_label">このカテゴリーのタグの一覧：</p>
            <?php
            // タグ内容を表示
            $stmt = $db->prepare("SELECT * FROM tag_options WHERE category_id = ?");
            $stmt->execute(array($category['id']));
            $tags = $stmt->fetchAll();
            ?>
            <div class="tag-items">
              <?php foreach ($tags as $tag) : ?>
                <div class="tag-item">
                  <p class="tag-item_text" style="color: <?= $tag['tag_color'] ?>; border: 1px solid <?= $tag['tag_color'] ?>;"><?= $tag['tag_option'] ?></p>
                  <a class="tag-item_edit" href="./edit_tag_option.php?id=<?= $tag['category_id'] ?>&option=<?= $tag['id'] ?>">
                    <i class="fas fa-edit"></i>
                  </a>
                  <div class="tag-item_delete" onclick="deleteModal_option(<?= $tag['id'] ?>)" >
                    <i class="fas fa-trash"></i>
                  </div>
                </div>
              <!-- ここからオプション用の削除modal -->
              <div id="option_modal<?= $tag['id'] ?>" class="util_modalcont">
                <div class="util_deletemodal">

                  <p class="util_deletemodal_alert">本当に削除しますか？</p>
                  <div class="util_deletebuttons">
                    <button class="util_deletebuttons_item util_deletebuttons_item--no" onclick="closeFunction_option(<?= $tag['id'] ?>)">いいえ</button>
                    <a href="./delete_tag_option.php?id=<?= $tag['id'] ?>" style="text-decoration: none">
                      <button class="util_deletebuttons_item util_deletebuttons_item--yes" onclick="deleteFunction_option(<?= $tag['id'] ?>)">はい</button>
                    </a>
                  </div>
                </div>
              </div>
              <!-- ここから削除完了画面 -->
              <div id="option_modal_complete<?= $tag['id'] ?>" class="util_modalcont">
                <p class="util_modalcont_text">削除されました。</p>
              </div>
              <?php endforeach; ?>
            </div>
            <a href="./edit_tag_option.php?id=<?= $tag['category_id'] ?>" class="tag-more_add">+ タグのオプションを追加</a>            
          </div>
        <?php endforeach; ?>
        <a href="./edit_tag_category.php?act=add" class="tag_category_add">+ カテゴリーを追加</a>
      </div>
    </div>
  </div>



  <?php require('../_footer.php'); ?>

<script>

//詳細ボタンをクリックした時の処理
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

//削除ボタンをクリックした時の処理
let deleteModal = function (id) {
          let modal = document.getElementById(`util_deletemodal${id}`);
          let modalComplete = document.getElementById(`util_modalcont${id}`);
          function modalOpen() {
            modal.style.display = 'block';
          };
          modalOpen();
}

let deleteFunction = function (id) {
          let modal = document.getElementById(`util_deletemodal${id}`);
          let modalComplete = document.getElementById(`util_modalcont${id}`);
          function deleteAgent() {
            modal.style.display = 'none';
            modalComplete.style.display = 'block';
          };
          deleteAgent();
    }

    let closeFunction = function (id) {
          let modal = document.getElementById(`util_deletemodal${id}`);
          let modalComplete = document.getElementById(`util_modalcont${id}`);
          
          function modalClose() {
            modal.style.display = 'none';
          };
          modalClose();
    }

//削除ボタンをクリックした時の処理
let deleteModal_option = function (id) {
          let modal = document.getElementById(`option_modal${id}`);
          function modalOpen() {
            modal.style.display = 'block';
          };
          modalOpen();
}

let deleteFunction_option = function (id) {
          let modal = document.getElementById(`option_modal${id}`);
          let modalComplete = document.getElementById(`option_modal_complete${id}`);
          function deleteAgent() {
            modal.style.display = 'none';
            modalComplete.style.display = 'block';
          };
          deleteAgent();
    }

    let closeFunction_option = function (id) {
          let modal = document.getElementById(`option_modal${id}`);
          
          function modalClose() {
            modal.style.display = 'none';
          };
          modalClose();
    }

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