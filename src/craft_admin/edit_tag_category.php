<?php

require('../dbconnect.php');

// URLからIDを取得
if (isset($_GET['id'])) {

  $mode = "edit";

  $id = $_GET['id'];

  // 既存データの表示
  $stmt = $db->query("SELECT * FROM tag_categories WHERE id = '$id'");
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
} elseif (isset($_GET['act'])) {

  $mode = "add";

    if ($_GET['act'] == "add") {
      $result['tag_category'] = '';
      $result['tag_category_desc'] = '';

      // エージェントを追加したい場合、このページに飛ばす際の URLに act=add を入れて分岐
      if (isset($_POST['submit'])) {

        $tag_category = $_POST['tag_category'];
        $tag_category_desc = $_POST['tag_category_desc'];

        $sql = 'INSERT INTO tag_categories(tag_category, tag_category_desc, hide)
                VALUES (?, ?, 0)';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($tag_category, $tag_category_desc));

        header('Location: tag.php');
        exit;
      }
    }

    

} else {

  header('Location: warning.php');
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

      <?php if ($mode == "edit") { ?>

      <div class="util_title">
        <h2 class="util_title--text">
        タグのカテゴリーの編集
        </h2>
      </div>

      <!-- 編集 -->
      <div class="changetag">
        <h1 class="changetag_title">タグのカテゴリーを編集</h1>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="change_item">
            <label class="change_item--label" for="tag_category">カテゴリー名</label>
            <input class="change_item--input" type="text" name="tag_category" value="<?= $result['tag_category'] ?>" required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="tag_category_desc">カテゴリーの説明</label>
            <textarea class="changetag_item--textarea" name="tag_category_desc"><?= $result['tag_category_desc'] ?></textarea>
          </div>
          <input type="submit" value="変更を保存" name="submit" class="changetag_button">
        </form>
      </div>

      <?php } else if ($mode == "add") { ?>
      
      <div class="util_title">
        <h2 class="util_title--text">
        タグのカテゴリーの追加
        </h2>
      </div>
        <!-- タグのカテゴリーを追加 -->
      <div class="changetag">
        <h1 class="changetag_title">タグのカテゴリーを追加</h1>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="change_item">
            <label class="change_item--label" for="tag_category">カテゴリー名</label>
            <input class="change_item--input" type="text" name="tag_category" required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="tag_category_desc">カテゴリーの説明</label>
            <textarea class="change_item--textarea" name="tag_category_desc"></textarea>
          </div>
          <input type="submit" value="変更を保存" name="submit" class="changetag_button">
        </form>
      </div>
      <?php } ?>

    </div>
  </div>

  <?php require('../_footer.php'); ?>

</body>

</html>