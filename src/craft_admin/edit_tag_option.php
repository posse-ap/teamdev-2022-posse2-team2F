<?php

require('../dbconnect.php');

// URLからIDを取得
if (isset($_GET['option'])) {
  $id = $_GET['id'];
  $option_id = $_GET['option'];

  // 既存データの表示
  // $stmt = $db->query("SELECT * FROM tag_options WHERE category_id = '$id'");
  $stmt = $db->query(
  "SELECT 
    tag_options.id, tag_options.category_id, tag_categories.tag_category, tag_options.tag_option
  FROM 
    tag_options
  JOIN 
    tag_categories ON tag_options.category_id = tag_categories.id
  AND 
    tag_options.category_id = '$id'");
  $result = $stmt->fetch();

  // 編集するタグを選択するための SQL文
  $stmt = $db->query("SELECT * FROM tag_options WHERE category_id = '$id' AND id = '$option_id'");
  $option = $stmt->fetch();


  if (isset($_POST['save'])) {
    // タグの初期値等設定

    $tag_name = $_POST['tag_name'];
    $tag_color = $_POST['tag_color'];

    $sql = "UPDATE tag_options 
            SET tag_option = ?, tag_color = ?
            WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($tag_name, $tag_color, $option_id));
    
    header('Location: tag.php');
    exit;
  } else {
    // echo "失敗だね";
  }

  
} else {
  $id = $_GET['id'];
  $option['tag_option'] = '';
  $option['tag_color'] = '';

  // 既存データの表示
  // $stmt = $db->query("SELECT * FROM tag_options WHERE category_id = '$id'");
  $stmt = $db->query(
    "SELECT 
      tag_options.id, tag_options.category_id, tag_categories.tag_category, tag_options.tag_option
    FROM 
      tag_options
    JOIN 
      tag_categories ON tag_options.category_id = tag_categories.id
    AND 
      tag_options.category_id = '$id'");
    $result = $stmt->fetch();

    // タグのオプション追加

    if (isset($_POST['add'])) {
      // 追加をしたい場合
      $category_id = $_GET['id'];
      $tag_name_new = $_POST['tag_name_new'];
      $tag_color_new = $_POST['tag_color_new'];

      $sql = 'INSERT INTO tag_options(category_id, tag_option, tag_color)
                VALUES (?, ?, ?)';
      $stmt = $db->prepare($sql);
      $stmt->execute(array($category_id, $tag_name_new, $tag_color_new));    

      header('Location: tag.php');
      exit;
    }
}

?>
<?php
// タグ表示

//既存データの表示
// $stmt = $db->query('SELECT * FROM tag-categories');

// $categories = $stmt->fetchAll();

// 更新処理
// if (isset($_POST['tag']) && is_array($_POST['tag'])) {
//   $tag = implode("、", $_POST["tag"]);

//   // $sql = "UPDATE agents SET agent_tag = ? WHERE id = '$id'";
//   // $stmt = $db->prepare($sql);
//   // $stmt->execute(array($tag));
//   // $reload = "edit_tag_option.php?id=" . $id;
//   // header("Location:" . $reload);
// } else {
//   // echo 'チェックボックスの値を受け取れていません';
// }

?>

<!DOCTYPE html>
<html>
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
        <a class="util_sidebar_link util_sidebar_link--selected" href="">タグ編集・追加</a>
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
      <div class="tag_information">
        <h1>タグのオプションを編集</h1>
        <form action="" method="post" enctype="multipart/form-data">
          <p>
            <label for="tag_category">カテゴリー名</label>
            <input type="text" name="tag_category" value="<?= $result['tag_category'] ?>" required readonly="readonly">
          </p>
          <p>
            <label for="tag_name">タグ名</label>
            <input type="text" name="tag_name" value="<?= $option['tag_option'] ?>" required>
          </p>
          <p>
            <label for="tag_color">タグ色</label>
            <input type="color" name="tag_color" value="<?= $option['tag_color'] ?>" required>
          </p>
          <input type="submit" value="変更を保存" name="save" class="manage_button">
        </form>
      </div>
      
      <!-- タグのオプションを追加 -->
      <div class="tag_information">
        <h1>タグのオプションを追加</h1>
        <form action="" method="post" enctype="multipart/form-data">
          <p>
            <label for="tag_category">カテゴリー名</label>
            <input type="text" name="tag_category" value="<?= $result['tag_category'] ?>" required readonly="readonly">
          </p>
          <p>
            <label for="tag_name_new">タグ名</label>
            <input type="text" name="tag_name_new" required>
          </p>
          <p>
            <label for="tag_color_new">タグ色</label>
            <input type="color" name="tag_color_new" required>
          </p>
          <input type="submit" value="追加" name="add" class="manage_button">
        </form>
      </div>

    </div>
</div>

<!-- ここからtag_modal -->
<div id="tag_modal" class="tag_modal">
    <form action="" method="POST">

      <div class="tag_modal_container">
          <div class="tag_modal_container--tag">
            
            <div class="tag_modal_container--tag_tags">
              <?php 
            $stmt = $db->prepare("SELECT * FROM tag_options WHERE category_id = ?");
            
            $stmt->execute(array($id));

            $tags = $stmt->fetchAll();

            ?>

             
              <?php foreach ($tags as $tag) : ?>

                <input type="radio" name="tag" id="<?= $tag['id'] ?>" value="<?= $tag['tag_option'] ?>">
                <label for="tag">

                  <?= $tag['tag_option'] ?>
                </label>

              <?php endforeach; ?>
            </div>
          </div>
        
        <div class="tag_modal_container--buttons">
          <button onclick="tag_modalClose()" class="tag_modalClose">戻る</button>
          <input type="submit" value="決定" class="tag_decision">
        </div>
      </div>
    </form>
  </div>

<?php require('../_footer.php'); ?>

<script>
    const tag_modal = document.getElementById('tag_modal');

    function tag_modalOpen() {
      tag_modal.style.display = 'block';
    }

    function tag_modalClose() {
      tag_modal.style.display = 'none';
    }
  </script>

</body>
</html>

