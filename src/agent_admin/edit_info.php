<?php
session_start();
require('../dbconnect.php');

// ログインしていないままアクセスしようとしている場合エラーページに飛ばす
if (!isset($_SESSION['check'])) {
  header('Location: ./login/login_error.php');
}

// URLからIDを取得
$id = $_SESSION['id'];



// var_dump(($id));

// データがそもそもあるか検証
$stmt = $db->query("SELECT COUNT(*) FROM agent_users_info WHERE user_id = '$id'");
$count = $stmt->fetch();

// 既存データの表示
// $stmt = $db->query("SELECT * FROM agent_users_info WHERE id = '$id'");
$stmt = $db->query("SELECT * FROM agent_users_info WHERE user_id = '$id'");
$result = $stmt->fetch();

// 担当者情報が既に登録されている場合は編集
if ($count[0] != 0) {
  $mode = "edit";

  if (isset($_POST['submit'])) {

    // 画像以外の更新
    $name = $_POST['name'];
    $dept = $_POST['dept'];
    $message = $_POST['message'];

    $sql = 'UPDATE agent_users_info
          SET name = ?, dept = ?, message = ?
          WHERE user_id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($name, $dept, $message, $id));

    // 画像更新
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      // echo "The file ". htmlspecialchars( basename( $_FILES["agent_pic"]["name"])). " has been uploaded.";
      // 既存データの表示
      $sql = "UPDATE agent_users_info SET image = '" . $_FILES['image']['name'] . "' WHERE id = '$id'";
      $stmt = $db->query($sql);
    }

    header('Location: students_info.php');
    exit;
  }
} elseif ($count[0] == 0) {
  $mode = "add";

  if (isset($_POST['submit'])) {
    // 画像以外
    $name = $_POST['name'];
    $dept = $_POST['dept'];
    $message = $_POST['message'];

    // 画像更新
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // 予想
    // INSERT INTO文 は一回で書かないとだから、編集画面みたいに分けて書けない
    // 画像をアップロードして、さらに登録ボタンが押されたら SQL文を書く仕組みにした！ （どうせ画像の登録は必要になるから）

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $sql = "INSERT INTO agent_users_info(user_id, name, dept, image, message, agent_name) 
            VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $db->prepare($sql);
      $stmt->execute(array($id, $name, $dept, $_FILES['image']['name'], $message, $_SESSION['agent_name']));
    } else {
      // echo "Sorry, there was an error uploading your file.";
    }


    

    header('Location: students_info.php');
    exit;
  }
}


?>

<!DOCTYPE html>
<html>

<body>
  <?php require('../_header.php'); 
  
  // var_dump($mode);
  
  ?>

  
  <div class="util_logout">
      <p class="util_logout_email"><?= $_SESSION['check_email'] ?></p>
      <a href="./login/logout.php">
      ログアウト
      <i class="fas fa-sign-out-alt"></i>
      </a>
  </div>
  <div class="util_container">
    <div class="util_sidebar">
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/agent_admin/students_info.php">学生申し込み一覧</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button util_sidebar_button--selected">
        <a class="util_sidebar_link util_sidebar_link--selected" href="/agent_admin/edit_info.php">担当者情報管理</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/agent_admin/inquiries.php">お問合せ</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/agent_admin/invoice.php">請求金額確認</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/userpage/top.php" target="_blank">ユーザー用サイトへ</a>
        <i class="fas fa-angle-right"></i>
      </div>
    </div>
    <div class="util_content">
      <div class="util_title">
        <h2 class="util_title--text">
          担当者情報管理
        </h2>
      </div>

      <div class="change">

        <?php if ($mode == "add") { ?>

          <form action="" method="post" enctype="multipart/form-data">
            <div class="change_item">
              <label class="change_item--label" for="name">担当者氏名</label>
              <input class="change_item--input" type="text" name="name" required>
            </div>
            <div class="change_item">
              <label class="change_item--label" for="dept">担当者所属部署</label>
              <input class="change_item--input" type="text" name="dept" required>
            </div>
            <div class="change_item preview">
              <label class="change_item--label" for="image">担当者画像</label>
              <img class="preview_img" src="images/grey.png" id="add_image" style="height: 15vh;"></img>
              <img class="preview_img preview_img--hide" id="add_preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
              <label class="change_item--button" for="image" onclick="upload_file()">+ ファイルをアップロード</label>
              <input class="change_item--image preview_input" id="image" type="file" name="image" accept='image/*' onchange="previewImage(this);">
              <script>
                function previewImage(obj) {
                  var fileReader = new FileReader();
                  fileReader.onload = (function() {
                    document.getElementById('add_preview').src = fileReader.result;
                  });
                  fileReader.readAsDataURL(obj.files[0]);
                }

                const add_preview = document.getElementById('add_preview');
                const add_image = document.getElementById('add_image');

                function upload_file() {
                  add_preview.style.display = 'block';
                  add_image.style.display = 'none';

                }
              </script>
            </div>
            <div class="change_item">
              <label class="change_item--label" for="message">担当者からの一言</label>
              <textarea class="change_item--textarea" name="message"></textarea>
            </div>
            <input class="change_button" type="submit" value="変更を保存" name="submit">
          </form>



        <?php } elseif ($mode == "edit") { ?>

          <form action="" method="post" enctype="multipart/form-data">
            <div class="change_item">
              <label class="change_item--label" for="name">担当者氏名</label>
              <input class="change_item--input" type="text" name="name" value="<?= $result['name'] ?>" required>
            </div>
            <div class="change_item">
              <label class="change_item--label" for="dept">担当者所属部署</label>
              <input class="change_item--input" type="text" name="dept" value="<?= $result['dept'] ?>" required>
            </div>
            <div class="change_item preview">
              <label class="change_item--label" for="image">担当者画像</label>
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
              <label class="change_item--label" for="message">担当者からの一言</label>
              <textarea class="change_item--textarea" name="message"><?= $result['message'] ?></textarea>
            </div>
            <input class="change_button" type="submit" value="変更を保存" name="submit">
          </form>



        <?php } ?>
      </div>

    </div>
  </div>



  <?php require('../_footer.php'); ?>


</body>

</html>