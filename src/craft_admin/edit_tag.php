<?php

require('../dbconnect.php');

// URLからIDを取得
$id = $_GET['id'];


// 既存データの表示
$stmt = $db->query("SELECT * FROM tag_categories WHERE id = '$id'");
$result = $stmt->fetch();


if (isset($_POST['submit'])) {

  // 画像以外の更新
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


?>

<!DOCTYPE html>
<html>
<body>
<?php require('../_header.php'); ?>

<div class="craftall_container">
    <div class="craftall_leftcontainer">
      <div class="craftall_manage">
        <a class="selected" href="/craft_admin/home.php">エージェント管理</a>
      </div>
      <div class="craftall_agent_add">
        <a href="/craft_admin/add.php">エージェント追加</a>
      </div>
      <div class="craftall_tag_manage">
        <a href="">タグ編集・追加</a>
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
      <div class="edit_tag_information">
        <form action="" method="post" enctype="multipart/form-data">
          <p>
            <label for="tag_name">タグ名</label>
            <input type="text" name="tag_name" value="<?= $result['tag_category'] ?>" required>
          </p>
          <p class="agent_info_container">
            <label for="tag_description">タグの説明</label>
            <textarea name="tag_description" ><?= $result['tag_category_desc'] ?></textarea>
          </p>
          <input type="submit" value="変更を保存" name="submit" class="manage_button">
        </form>
      </div>

    </div>
</div>

<?php require('../_footer.php'); ?>

</body>
</html>

