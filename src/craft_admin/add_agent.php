<?php

// とりあえず edit_agent.php をコビペしてきただけ

require('../dbconnect.php');


// URLからIDを取得 = 必要なし


// 既存データの表示 = 必要なし


// 画像以外の更新

if (isset($_POST['submit'])) {


  $agent_name = $_POST['agent_name'];
  $agent_tag = $_POST['agent_tag'];
  $agent_info = $_POST['agent_info'];
  // $agent_display = $_POST['agent_display'];
  if(isset($_POST['agent_display'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_display = $_POST['agent_display'];
  }


  $sql = 'INSERT INTO agents(agent_name, agent_tag, agent_info, agent_display) 
          VALUES (?, ?, ?, ?)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_name, $agent_tag, $agent_info, $agent_display));

  // 画像更新
  $target_dir = "images/";
  $target_file = $target_dir . basename($_FILES["agent_pic"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // 予想
  // INSERT INTO文 は一回で書かないとだから、編集画面みたいに分けて書けない
  // 画像をアップロードして、さらに登録ボタンが押されたら SQL文を書く仕組みにした！ （どうせ画像の登録は必要になるから）

  if (move_uploaded_file($_FILES["agent_pic"]["tmp_name"], $target_file)) {
    $sql = 'INSERT INTO agents(agent_name, agent_pic, agent_tag, agent_info, agent_display) 
            VALUES (?, ?, ?, ?, ?)';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($agent_name, $_FILES['agent_pic']['name'], $agent_tag, $agent_info, $agent_display));
  } else {
    // echo "Sorry, there was an error uploading your file.";
  }


  header('Location: http://localhost/craft_admin/home.php');
  exit;
}


// }

?>
<?php
// タグ表示

//既存データの表示
$stmt = $db->query('SELECT * FROM tag_categories');

$categories = $stmt->fetchAll();

// 更新処理
error_reporting(0);
if (isset($_POST['tag']) && is_array($_POST['tag'])) {
  $tag = implode("、", $_POST["tag"]);

//   $sql = "UPDATE agents SET agent_tag = ? WHERE id = '$id'";
//   $stmt = $db->prepare($sql);
//   $stmt->execute(array($tag));
//   $reload = "edit.php?id=" . $id;
//   header("Location:" . $reload);
// } else {
  // echo 'チェックボックスの値を受け取れていません';
}

?>


<!DOCTYPE html>
<html>
<body>
<?php require('../_header.php'); ?>



<!-- haiuoiahjksldf -->

<div class="util_container">
    <div class="util_sidebar">
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/home.php">エージェント管理</a>
      </div>
      <div class="util_sidebar_button  util_sidebar_button--selected">
        <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/add_agent.php">エージェント追加</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/tag.php">タグ編集・追加</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
      </div>
    </div>
    <div class="util_content">
      <h2>
        <div class="util_title">
        エージェント追加
        </div>
      </h2>
      <div class="edit_agent_information">

        <form action="" method="post" enctype="multipart/form-data">
          <p>
            <label for="agent_name">エージェント名：</label>
            <input  type="text" name="agent_name" required id="agent_name"/>
          </p>
          <p>
            <label for="agent_tag">エージェントタグ</label>
            <input type="text" name="agent_tag" required onclick="tag_modalOpen()" class="trigger" value="<?= $tag?>" id="input">
          </p>
          <p class="agent_img">
            <label for="agent_pic">エージェント画像</label>
            <!-- </div> -->
            <!-- <input type="image" src=" ?>" style="width: 500px"> -->
            <textarea id="add_image" name="agent_pic_blank" style="width: 48vw;
    height: 20.8vh;
    overflow-x: scroll;"></textarea>
            <img id="add_preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
            <label for="image" class="file_upload_button" onclick="upload_file()">+ ファイルをアップロード</label>
            <input id="image" type="file" name="agent_pic" accept='image/*' onchange="previewImage(this);">
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
          </p>
          <p class="agent_info_container">
            <label for="agent_info">エージェント説明</label>
            <textarea name="agent_info" ></textarea>
          </p>
          <p class="agent_term">
            <label for="agent_display">エージェント掲載期間</label>
              <select name="agent_display">
                <option value="1">1ヶ月</option>
                <option value="3">3ヶ月</option>
                <option value="6">6ヶ月</option>
                <option value="12">12ヶ月</option>
              </select>
          </p>
          
          <input type="submit" value="変更を保存" name="submit" class="manage_button">
        </form>
          
      </div>

    </div>
</div>

<!-- ここからtag_modal -->
<div id="tag_modal" class="tag_modal">
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


</body>
</html>