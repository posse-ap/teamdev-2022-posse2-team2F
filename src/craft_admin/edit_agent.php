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
  $agent_tag = $_POST['agent_tag'];
  // $agent_pic = $_POST['agent_pic'];
  $agent_info = $_POST['agent_info'];
  // $agent_display = $_POST['agent_display'];
  if(isset($_POST['agent_display'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_display = $_POST['agent_display'];
  }

  $sql = 'UPDATE agents
        SET agent_name = ?, agent_tag = ?, agent_info = ?, agent_display = ?
        WHERE id = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_name, $agent_tag, $agent_info, $agent_display, $id));

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
      <div class="util_sidebar_button util_sidebar_button-selected">
        <a class="util_sidebar_link util_sidebar_link-selected" href="/craft_admin/home.php">エージェント管理</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/add_agent.php">エージェント追加</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href=/craft_admin/tag.php>タグ編集・追加</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
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

