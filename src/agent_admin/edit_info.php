<?php

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
  $target_file = $target_dir . basename($_FILES["agent_pic"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  if (move_uploaded_file($_FILES["agent_pic"]["tmp_name"], $target_file)) {
    // echo "The file ". htmlspecialchars( basename( $_FILES["agent_pic"]["name"])). " has been uploaded.";
    // 既存データの表示
    $sql = "UPDATE agents SET agent_pic = '".$_FILES['agent_pic']['name']."' WHERE id = '$id'";
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
            <label for="agent_name">担当者情報</label>
            <input type="text" name="agent_name" value="<?= $result['agent_name'] ?>" required>
          </p>
          <p>
            <label for="agent_tag">エージェントタグ</label>
            <input type="text" name="agent_tag" value="<?= $result['agent_tag'] ?>" required onclick="tag_modalOpen()">
          </p>
          <p class="agent_img">
            <label for="agent_pic">エージェント画像</label>
            
            <!-- <div class="agent_image"> -->

              <img src="images/<?= $result['agent_pic'] ?>" alt="" style="height: 20.8vh">
            <!-- </div> -->
            <!-- <input type="image" src=" ?>" style="width: 500px"> -->
            <label for="image" class="file_upload_button">+ ファイルをアップロード</label>
            <input id="image" type="file" name="agent_pic">
          </p>
          <p class="agent_info_container">
            <label for="agent_info">エージェント説明</label>
            <textarea name="agent_info" ><?= $result['agent_info'] ?></textarea>
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
  <div class="tag_modal_container">

  <div class="tag_modal_buttons">
    <button onclick="tag_modalClose()" class="tag_modalClose">戻る</button>
    <button class="tag_decision">決定</button>

  </div>
  </div>
  
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

