<?php

require('../dbconnect.php');

// URLからIDを取得
if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // 既存データの表示
  // $stmt = $db->query("SELECT * FROM tag_options WHERE category_id = '$id'");
  $stmt = $db->query("
  SELECT 
    tag_options.id, tag_options.category_id, tag_categories.tag_category, tag_options.tag_option
  FROM 
    tag_options
  JOIN 
    tag_categories ON tag_options.category_id = tag_categories.id
  AND 
    tag_options.category_id = '$id'");
  $result = $stmt->fetch();


  if (isset($_POST['submit'])) {
    // 編集をしたい場合
    $tag_category = $_POST['tag_category'];
    $tag_category_desc = $_POST['tag_category_desc'];
    
    $sql = 'UPDATE tag_categories
          SET tag_category = ?, tag_category_desc = ?
          WHERE id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($tag_category, $tag_category_desc, $id));

    header('Location: tag.php');
    exit;
  }
} else {
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
      <div class="util_sidebar_button util_sidebar_button-selected">
        <a class="util_sidebar_link util_sidebar_link-selected" href="">タグ編集・追加</a>
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
            <label for="tag_category">タグ名</label>
            <input type="text" name="tag_category" value="<?= $result['tag_option'] ?>" required>
          </p>
          <p class="agent_info_container">
            <label for="tag_category_desc">タグの説明</label>
            <textarea name="tag_category_desc" ><?= $result['tag_category_desc'] ?></textarea>
          </p>
          <input type="submit" value="変更を保存" name="submit" class="manage_button">
        </form>
      </div>
      
      <!-- タグのオプションを追加 -->
      <div class="tag_information">
        <h1>タグのオプションを追加</h1>
        <form action="" method="post" enctype="multipart/form-data">
          <p>
            <label for="tag_category">タグ名</label>
            <input type="text" name="tag_category" required>
          </p>
          <p class="agent_info_container">
            <label for="tag_category_desc">タグの説明</label>
            <textarea name="tag_category_desc" ></textarea>
          </p>
          <input type="submit" value="変更を保存" name="submit" class="manage_button">
        </form>
      </div>

    </div>
</div>

<?php require('../_footer.php'); ?>

</body>
</html>

