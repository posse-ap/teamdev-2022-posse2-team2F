<?php

require('../dbconnect.php');

// URLからIDを取得
// if (isset($_GET['id'])) {
  $id = $_GET['id'];

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

  // タグを選択するための SQL文
  $stmt = $db->query("SELECT * FROM tag_options WHERE category_id = '$id'");
  $tags = $stmt->fetchAll();


  if (isset($_POST['save'])) {
    // 編集をしたい場合
    $tag_option = $_POST['tag_option'];
    
    $sql = 'UPDATE tag_options
          SET tag_option = ?
          WHERE id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($tag_option, $id));
    

    header('Location: tag.php');
    exit;
  }

  if (isset($_POST['add'])) {
    // 追加をしたい場合
    $tag_option = $_POST['tag_option2'];
    $category_id = $_GET['id'];

    $sql = 'INSERT INTO tag_options(tag_option, category_id)
              VALUES (?, ?)';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($tag_option, $category_id));    

    header('Location: tag.php');
    exit;
  }
// } else {
  // error_reporting(0);
  // if($_GET['act']=="add") {

  //   $result['tag_category'] = '';
  //   $result['tag_category_desc'] = '';

  //   // エージェントを追加したい場合、このページに飛ばす際の URLに act=add を入れて分岐
  //   if (isset($_POST['submit'])) {

  //     $tag_category = $_POST['tag_category'];
  //     $tag_category_desc = $_POST['tag_category_desc'];
    
  //     $sql = 'INSERT INTO tag_categories(tag_category, tag_category_desc)
  //             VALUES (?, ?)';
  //     $stmt = $db->prepare($sql);
  //     $stmt->execute(array($tag_category, $tag_category_desc));

  //     header('Location: tag.php');
  //     exit;
  //   }
  // }



?>
<?php
// タグ表示

//既存データの表示
// $stmt = $db->query('SELECT * FROM tag-categories');

// $categories = $stmt->fetchAll();

// 更新処理
if (isset($_POST['tag']) && is_array($_POST['tag'])) {
  $tag = implode("、", $_POST["tag"]);

  // $sql = "UPDATE agents SET agent_tag = ? WHERE id = '$id'";
  // $stmt = $db->prepare($sql);
  // $stmt->execute(array($tag));
  // $reload = "edit_tag_option.php?id=" . $id;
  // header("Location:" . $reload);
} else {
  // echo 'チェックボックスの値を受け取れていません';
}

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
            <input type="text" name="tag_category" value="<?= $result['tag_category'] ?>" required>
          </p>
          <p>
            <label for="tag-list">編集するタグを選択</label>
              <select name="tag-list">
                <?php foreach ($tags as $tag) : ?>
                <option value=""><?= $tag['tag_option'] ?></option>
                <?php endforeach; ?>
              </select>
          </p>
          <p>
            <label for="tag_option">新しいタグ名</label>
            <input type="text" name="tag_option" value="<?= $result['tag_option'] ?>" required>
          </p>
          <input type="submit" value="変更を保存" name="save" class="manage_button">
        </form>
      </div>
      
      <!-- タグのオプションを追加 -->
      <div class="tag_information">
        <h1>タグのオプションを追加</h1>
        <form action="" method="post" enctype="multipart/form-data">
          <p>
            <label for="tag_option2">タグ名</label>
            <input type="text" name="tag_option2" required>
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

                <input type="checkbox" name="tag[]" id="<?= $tag['id'] ?>" value="<?= $tag['tag_option'] ?>">
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

