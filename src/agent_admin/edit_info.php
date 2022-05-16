<?php
session_start();
require('../dbconnect.php');

// URLからIDを取得
$id = $_SESSION['id'];


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
      <div class="util_sidebar_button util_sidebar_button--selected">
        <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/home.php">担当者情報</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/add_agent.php"></a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href=/craft_admin/tag.php></a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href=""></a>
      </div>
    </div>
    <div class="util_content">
      <h2>
        <div class="util_title">
        エージェント編集
        </div>
      </h2>
      <div class="edit_agent_information">

        <form action="" method="post" enctype="multipart/form-data">
          <p>
            <label for="name">担当者名前</label>
            <input type="text" name="name" value="<?= $result['name'] ?>" required>
          </p>
          <p>
            <label for="dept">部署</label>
            <input type="text" name="dept" value="<?= $result['dept'] ?>" required>
          </p>
          <p class="agent_img">
            <label for="image">エージェント画像</label>
            
            <!-- <div class="agent_image"> -->

              <img src="images/<?= $result['image'] ?>" alt="" style="height: 20.8vh" id="agent_image">
              <img id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
            <!-- </div> -->
            <!-- <input type="image" src=" ?>" style="width: 500px"> -->
            <label for="image" class="file_upload_button" onclick="upload_file()">+ ファイルをアップロード</label>
            <input id="image" type="file" name="image"  accept='image/*' onchange="previewImage(this);">
            <script>
              function previewImage(obj) {
                var fileReader = new FileReader();
                fileReader.onload = (function() {
                  document.getElementById('preview').src = fileReader.result;
                });
                fileReader.readAsDataURL(obj.files[0]);
              }

              const preview = document.getElementById('preview');
              const agent_image = document.getElementById('agent_image');

              function upload_file() {
                preview.style.display = 'block';
                agent_image.style.display = 'none';

              }
            </script>
          </p>
          <p class="agent_info_container">
            <label for="message">担当者から一言</label>
            <textarea name="message" ><?= $result['message'] ?></textarea>
          </p>
          
          <input type="submit" value="変更を保存" name="submit" class="manage_button">
        </form>
      </div>

    </div>
</div>



<?php require('../_footer.php'); ?>


</body>
</html>

