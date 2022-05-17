<?php
session_start();
require('../dbconnect.php');

// URLからIDを取得
$id = $_SESSION['id'];
// echo $_SESSION['id'];


// 既存データの表示
$stmt = $db->query("SELECT * FROM agent_users WHERE id = '$id'");
$result = $stmt->fetch();


if (isset($_POST['submit'])) {

  // 画像以外の更新
  $name = $_POST['name'];
  $dept = $_POST['dept'];
  $message = $_POST['message'];

  $sql = 'UPDATE agent_users
        SET name = ?, dept = ?, message = ?
        WHERE id = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($name, $dept, $message, $id));

  // 画像更新
  $target_dir = "images/";
  $target_file = $target_dir . basename($_FILES["image"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    // echo "The file ". htmlspecialchars( basename( $_FILES["agent_pic"]["name"])). " has been uploaded.";
    // 既存データの表示
    $sql = "UPDATE agent_users SET image = '".$_FILES['image']['name']."' WHERE id = '$id'";
    $stmt = $db->query($sql);
  } else {
    // echo "Sorry, there was an error uploading your file.";
  }


  header('Location: home.php');
  exit;
}


?>

<!DOCTYPE html>
<html>
<body>
<?php require('../_header.php'); ?>

<div class="util_container">
    <div class="util_sidebar">
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/agent_admin/students_info.php">学生申し込み一覧</a>
      </div>
      <div class="util_sidebar_button util_sidebar_button--selected">
        <a class="util_sidebar_link util_sidebar_link--selected" href="/agent_admin/edit_info.php">担当者情報</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="">お問合せ</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/agent_admin/invoice.php">請求金額確認</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
      </div>
    </div>
    <div class="util_content">
      <div class="util_title">
        <h2 class="util_title--text">
          担当者情報編集
        </h2>
      </div>

      <div class="change">
        <form action="" method="post" enctype="multipart/form-data">
          <div class="change_item">
            <label class="change_item--label" for="name">担当者氏名</label>
            <input class="change_item--input" type="text" name="name" value="<?= $result['name'] ?>" required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="dept">エージェントタグ</label>
            <input class="change_item--input" type="text" name="dept" value="<?= $result['dept'] ?>" required>
          </div>
          <div class="change_item preview">
            <label class="change_item--label" for="image">エージェント画像</label>
            <img class="preview_img" src="images/<?= $result['image'] ?>" alt="" style="height: 15vh" id="manager_image">
            <img class="preview_img preview_img--hide" id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
            <label class="change_item--button" for="image" onclick="upload_file()">+ ファイルをアップロード</label>
            <input class="change_item--image preview_input" id="image" type="file" name="image" accept='image/*' onchange="previewImage(this);">
            <script>
              function previewImage(obj) {
                var fileReader = new FileReader();
                fileReader.onload = (function() {
                  document.getElementById('preview').src = fileReader.result;
                });
                fileReader.readAsDataURL(obj.files[0]);
              }

              const preview = document.getElementById('preview');
              const manager_image = document.getElementById('manager_image');

              function upload_file() {
                preview.style.display = 'block';
                manager_image.style.display = 'none';

              }
            </script>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="message">エージェント説明</label>
            <textarea class="change_item--textarea" name="message"><?= $result['message'] ?></textarea>
          </div>
          <input class="change_button" type="submit" value="変更を保存" name="submit">
        </form>
      </div>

    </div>
</div>



<?php require('../_footer.php'); ?>


</body>
</html>

