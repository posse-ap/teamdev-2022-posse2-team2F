<?php
session_start();
require('../dbconnect.php');

// ログインしていないままアクセスしようとしている場合エラーページに飛ばす
if (!isset($_SESSION['id'])) {
  header('Location: ./login/login_error.php');
}

// タグカテゴリーを表示
$stmt = $db->query("SELECT * FROM tag_categories");
// $stmt = $db->query("SELECT * FROM tags;");
$categories = $stmt->fetchAll();


// カテゴリー用
// 表示する処理
if (isset($_POST['show'])) {
  $show = key($_POST['show']); 
  // $_SESSION['id'] = key($_POST['show']); 

  $sql = "UPDATE tag_categories
          SET hide = 0
          WHERE id = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($show));
} 

// 隠す処理
if (isset($_POST['hide'])) {
  $hide = key($_POST['hide']); 

  $sql = "UPDATE tag_categories
          SET hide = 1
          WHERE id = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($hide));
} 

// オプション用
// 表示する処理
if (isset($_POST['show_option'])) {
  $show_option = key($_POST['show_option']); 

  $sql = "UPDATE tag_options
          SET hide = 0
          WHERE id = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($show_option));
} 

// 隠す処理
if (isset($_POST['hide_option'])) {
  $hide_option = key($_POST['hide_option']); 

  $sql = "UPDATE tag_options
          SET hide = 1
          WHERE id = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($hide_option));
} 


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

<form action="" method="POST">
  <?php require('../_header.php'); ?>
  <div id="modal_bg" class="deletemodal_overlay"></div>
  <div class="util_logout">
    <p class="util_logout_email"><?= $_SESSION['email'] ?></p>
    <a href="./login/logout.php">
      ログアウト
      <i class="fas fa-sign-out-alt"></i>
    </a>
  </div>
  <div class="util_container">
    <div class="util_sidebar">
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/home.php">エージェント管理</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/add_agent.php">エージェント追加</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button util_sidebar_button--selected">
        <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/tag.php">タグ編集・追加</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/students_info.php">学生申し込み一覧</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/contact_management.php">お問合せ管理</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/invoice.php">合計請求金額確認</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
        <i class="fas fa-angle-right"></i>
      </div>
    </div>
    <div class="util_content">
      <div class="util_title">
        <h2 class="util_title--text">
          タグ管理
        </h2>
      </div>
      
      <div class="tag-list">
        <div class="tag-list_labels">
          <div class="tag-list_labels--left">タグのカテゴリー名</div>
          <div class="tag-list_labels--middle">表示状態</div>
          <div class="tag-list_labels--right">操作</div>
        </div>

        <?php foreach ($categories as $category) : ?>
        <div class="tag-categories">
          <div class="tag-categories_info">
            <p class="tag-categories_info--name"><?= $category['tag_category'] ?></p>
          </div>

          <div class="tag-categories_display">
            <?php 
            // hide = 0 ： 表示されている
            $sql = 'SELECT count(*) FROM tag_categories WHERE id = ? AND hide = 0';
            $stmt = $db->prepare($sql);
            $stmt->execute(array($category['id']));
            $display = $stmt->fetch();
            // 表示されているなら、隠すオプションを表示
            if ($display[0] == 1) {
            ?>
            
            <input type="submit" value="&#xf06e;" class="fas tag-categories_display--eye" name="hide[<?= $category['id'] ?>]" >
            
            <!-- 表示されていないなら、見せるオプションを表示 -->
            <?php } else { ?>

            <input type="submit" value="&#xf070;" class="fas tag-categories_display--eye" name="show[<?= $category['id'] ?>]">

            <?php } ?>
              
              
          </div>

          <div class="tag-categories_buttons">
            <a href="./edit_tag_category.php?id=<?= $category['id'] ?>">
              <button type="button" class="util_action_button util_action_button--edit">編集</button>
            </a>
            <button type="button" class="util_action_button util_action_button--delete" onclick="deleteModal(<?= $category['id'] ?>)">削除</button>
            <button type="button" name="more" class="util_action_button util_action_button--list" id="<?= $category['id'] ?>" onclick="clickfunction(<?= $category['id'] ?>)">詳細</button>
          </div>
          <!-- ここからmodal -->
          <div id="util_deletemodal<?= $category['id'] ?>" class="util_deletemodal_container fixmodaltomiddle">
            <div class="util_deletemodal">
              <p class="util_deletemodal_text">本当に削除しますか？</p>
              <div class="util_deletemodal_buttons">
                <button type="button" class="util_deletemodal_back" onclick="closeFunction(<?= $category['id'] ?>)">いいえ</button>
                <a href="./delete_tag_category.php?id=<?= $category['id'] ?>" style="text-decoration: none">
                  <!-- <button class="yes" onclick="deleteAgent()">はい -->
                  <button type="button" class="util_deletemodal_confirm" onclick="deleteFunction(<?= $category['id'] ?>)">はい</button>
                </a>
              </div>
            </div>
          </div>
          <!-- ここから削除完了画面 -->
          <div id="util_modalcont<?= $category['id'] ?>" class="util_deletemodal_container fixmodaltomiddle">
            <p class="util_deletemodal_message">削除されました。</p>
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
                  <div class="tag-item_display">
                    <?php 
                    // hide = 0 ： 表示されている
                    $sql = 'SELECT count(*) FROM tag_options WHERE id = ? AND hide = 0';
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array($tag['id']));
                    $display_options = $stmt->fetch();
                    // 表示されているなら、隠すオプションを表示
                    if ($display_options[0] == 1) {
                    ?>
                    
                    <input type="submit" value="&#xf06e;" class="fas home-agents_display--eye" name="hide_option[<?= $tag['id'] ?>]" >
                    
                    
                    <!-- 表示されていないなら、見せるオプションを表示 -->
                    <?php } else { ?>

                    <input type="submit" value="&#xf070;" class="fas home-agents_display--eye" name="show_option[<?= $tag['id'] ?>]">

                    <?php } ?>
                  </div>
                </div>
              <!-- ここからオプション用の削除modal -->
              <div id="option_modal<?= $tag['id'] ?>" class="util_deletemodal_container fixmodaltomiddle">
                <div class="util_deletemodal">

                  <p class="util_deletemodal_text">本当に削除しますか？</p>
                  <div class="util_deletemodal_buttons">
                    <button type="button" class="util_deletemodal_back" onclick="closeFunction_option(<?= $tag['id'] ?>)">いいえ</button>
                    <a href="./delete_tag_option.php?id=<?= $tag['id'] ?>" style="text-decoration: none">
                      <button type="button" class="util_deletemodal_confirm" onclick="deleteFunction_option(<?= $tag['id'] ?>)">はい</button>
                    </a>
                  </div>
                </div>
              </div>
              <!-- ここから削除完了画面 -->
              <div id="option_modal_complete<?= $tag['id'] ?>" class="util_deletemodal_container fixmodaltomiddle">
                <p class="util_deletemodal_message">削除されました。</p>
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

  </form>



  <?php require('../_footer.php'); ?>

<script>

const bg = document.getElementById('modal_bg');

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
            bg.style.display = 'block';
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
            bg.style.display = 'none';
          };
          modalClose();
    }

//削除ボタンをクリックした時の処理
let deleteModal_option = function (id) {
          let modal = document.getElementById(`option_modal${id}`);
          function modalOpen() {
            modal.style.display = 'block';
            bg.style.display = 'block';
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
            bg.style.display = 'none';
          };
          modalClose();
    }

    // リロードした時詳細ページを押したままにする
    <?php if (isset($_POST['show_option'])) { ?>

    $(window).load(function() { 
      $('button[name="more"]').click(function(event){
        // クリックした要素の id を "id" に保存
        localStorage.setItem("id", event.target.id);
      });
      // 詳細ページを出す clickfunction の引数に保存された id を代入
      clickfunction(localStorage.getItem("id"));
    });

    <?php } ?>


    <?php if (isset($_POST['hide_option'])) { ?>

      $(window).load(function() { 
      $('button[name="more"]').click(function(event){
        // alert(event.target.id);
        localStorage.setItem("id", event.target.id);
      });
      clickfunction(localStorage.getItem("id"));
    });

    <?php } ?>



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