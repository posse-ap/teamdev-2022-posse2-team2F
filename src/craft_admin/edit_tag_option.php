<?php

session_start();

require('../dbconnect.php');

// ログインしていないままアクセスしようとしている場合エラーページに飛ばす
if (!isset($_SESSION['id'])) {
  header('Location: ./login/login_error.php');
}

// タグのオプションを編集したい場合
if (isset($_GET['option']) && isset($_GET['id'])) {

  $mode = "edit";

  

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

  
} elseif (!isset($_GET['option']) && isset($_GET['id'])) {

  $mode = "add";

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

      $sql = 'INSERT INTO tag_options(category_id, tag_option, tag_color, hide)
                VALUES (?, ?, ?, 0)';
      $stmt = $db->prepare($sql);
      $stmt->execute(array($category_id, $tag_name_new, $tag_color_new));    

      header('Location: tag.php');
      exit;
    }
} else {

  header('Location: warning.php');
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
    

      <?php if ($mode == "edit") { ?>
      <div class="util_title">
        <h2 class="util_title--text">
          タグのオプションの編集
        </h2>
      </div>
      <!-- 編集 -->
      <div class="changetag">
        <form action="" method="post" enctype="multipart/form-data">
          <div class="changetag_item">
            <label class="change_item--label" for="tag_category">カテゴリー名</label>
            <input class="changetag_item--input" type="text" name="tag_category" value="<?= $result['tag_category'] ?>" required readonly="readonly">
          </div>
          <div class="changetag_item">
            <label class="change_item--label" for="tag_name">タグ名</label>
            <input class="changetag_item--input" type="text" name="tag_name" value="<?= $option['tag_option'] ?>" required>
          </div>
          <div class="changetag_item">
            <label class="change_item--label" for="tag_color">タグ色</label>
            <input class="changetag_item--color" type="color" name="tag_color" value="<?= $option['tag_color'] ?>" required>
          </div>
          <input type="submit" value="変更を保存" name="save" class="changetag_button">
        </form>
      </div>

      <?php } else if ($mode == "add") { ?>
      <div class="util_title">
        <h2 class="util_title--text">
          タグのオプションの追加
        </h2>
      </div>
      <!-- タグのオプションを追加 -->
      <div class="changetag">
        <form action="" method="post" enctype="multipart/form-data">
          <div class="changetag_item">
            <label class="change_item--label" for="tag_category">カテゴリー名</label>
            <input class="changetag_item--input" type="text" name="tag_category" value="<?= $result['tag_category'] ?>" required readonly="readonly" >
          </div>
          <div class="changetag_item">
            <label class="change_item--label" for="tag_name_new">タグ名</label>
            <input class="changetag_item--input" type="text" name="tag_name_new" required>
          </div>
          <div class="changetag_item">
            <label class="change_item--label" for="tag_color_new">タグ色</label>
            <input class="changetag_item--color" type="color" name="tag_color_new" required>
          </div>
          <input type="submit" value="追加" name="add" class="changetag_button">
        </form>
      </div>

      <?php } ?>

    </div>
</div>

<?php require('../_footer.php'); ?>

</body>
</html>

