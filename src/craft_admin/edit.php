<?php

require('../dbconnect.php');

// URLからIDを取得
$id = $_GET['id'];


// 既存データの表示
$stmt = $db->query("SELECT * FROM agents WHERE id = '$id'");

$result = $stmt->fetch();



if (isset($_POST['submit'])) {

  // 画像以外の更新
  $agent_name = $_POST['agent_name'];
  // $agent_tag = $_POST['agent_tag'];
  // $agent_pic = $_POST['agent_pic'];
  $agent_info = $_POST['agent_info'];
  // $agent_display = $_POST['agent_display'];
  if (isset($_POST['agent_display'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_display = $_POST['agent_display'];
  }

  $sql = 'UPDATE agents
        SET agent_name = ?, agent_info = ?, agent_display = ?
        WHERE id = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_name, $agent_info, $agent_display, $id));

  // 画像更新
  $target_dir = "images/";
  $target_file = $target_dir . basename($_FILES["agent_pic"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  if (move_uploaded_file($_FILES["agent_pic"]["tmp_name"], $target_file)) {
    // echo "The file ". htmlspecialchars( basename( $_FILES["agent_pic"]["name"])). " has been uploaded.";
    // 既存データの表示
    $sql = "UPDATE agents SET agent_pic = '" . $_FILES['agent_pic']['name'] . "' WHERE id = '$id'";
    $stmt = $db->query($sql);
  } else {
    // echo "Sorry, there was an error uploading your file.";
  }


  header('Location: home.php');
  exit;
}
?>
<?php
// タグ表示

//既存データの表示
$stmt = $db->query('SELECT * FROM tag_categories');

$categories = $stmt->fetchAll();

// 更新処理
if (isset($_POST['tag']) && is_array($_POST['tag'])) {
  $tag = implode("、", $_POST["tag"]);

  $sql = "UPDATE agents SET agent_tag = ? WHERE id = '$id'";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($tag));
  $reload = "edit.php?id=" . $id;
  header("Location:" . $reload);
} else {
  // echo 'チェックボックスの値を受け取れていません';
}

?>

<!DOCTYPE html>
<html>

<body>
  <?php require('../_header.php'); ?>

  <div class="agent_container">
    <div class="agent_leftcontainer">
      <div class="agent_manage">
        <a href="/craft_admin/home.php">エージェント管理</a>
      </div>
      <div class="agent_add">
        <a href="/craft_admin/add.php">エージェント追加</a>
      </div>
      <div class="tag_manage">
        <a href="">タグ編集・追加</a>
      </div>
      <div class="usersite">
        <a href="">ユーザー用サイトへ</a>
      </div>
    </div>
    <div class="agent_rightcontainer">
      <h2>
        <div class="agent_title">
          エージェント編集
        </div>
      </h2>
      <div class="agent_informarion">

        <form action="" method="post" enctype="multipart/form-data">
          <p>
            <label for="agent_name">エージェント名</label>
            <input type="text" name="agent_name" value="<?= $result['agent_name'] ?>" required>
          </p>
          <p>
            <label for="agent_tag">エージェントタグ</label>
            <input type="text" name="agent_tag" value="<?= $result['agent_tag'] ?>" required onclick="tag_modalOpen()">
          </p>
          <p class="agent_img">


            <label for="agent_pic">エージェント画像</label>

            <!-- <div class="agent_image"> -->

            <img src="images/<?= $result['agent_pic'] ?>" alt="" style="height: 20.8vh" id="agent_image">
            <!-- </div> -->
            <!-- <input type="image" src=" ?>" style="width: 500px"> -->
            <img id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
            <label for="image" class="file_upload_button" onclick="upload_file()">+ ファイルをアップロード</label>
            <input id="image" type="file" name="agent_pic"  accept='image/*' onchange="previewImage(this);">
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
            <label for="agent_info">エージェント説明</label>
            <textarea name="agent_info"><?= $result['agent_info'] ?></textarea>
          </p>
          <p class="agent_term">
            <label for="agent_display">エージェント掲載期間</label>
            <select name="agent_display">
              <option value="1">1ヶ月</option>
              <option value="3">3ヶ月</option>
              <option value="6">6ヶ月</option>
              <option value="12">12ヶ月</option>
            </select>
            <!-- <span>ヶ月</span> -->
          </p>

          <input type="submit" value="変更を保存" name="submit" class="manage_button">
        </form>
      </div>

    </div>
  </div>

  <!-- ここからtag_modal -->
  <div id="tag_modal">
    <form action="" method="POST">

      <div class="tag_modal_container">
        <?php foreach ($categories as $category) : ?>
          <div id="no<?= $category['id'] ?>" class="tag_modal_container--tag">
            <h2>

              <?= $category['tag_category'] ?>
            </h2>
            <?php
            $stmt = $db->prepare("SELECT * FROM tag_options WHERE category_id = ?");

            $stmt->execute(array($category['id']));
            $tags = $stmt->fetchAll();

            ?>

            <div class="tag_modal_container--tag_tags">
              <?php foreach ($tags as $tag) : ?>

                <input type="checkbox" name="tag[]" id="<?= $tag['id'] ?>" value="<?= $tag['tag_option'] ?>">
                <label for="tag">

                  <?= $tag['tag_option'] ?>
                </label>

              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="tag_modal_container--buttons">
          <button onclick="tag_modalClose()" class="tag_modalClose">戻る</button>
          <input type="submit" value="決定" class="tag_decision">
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