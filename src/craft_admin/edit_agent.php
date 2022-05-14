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
  $agent_tagname = $_POST['agent_tag'];
  $agent_tag = $_POST['tag_id'];
  // $agent_pic = $_POST['agent_pic'];
  $agent_info = $_POST['agent_info'];
  // $agent_display = $_POST['agent_display'];
  if (isset($_POST['agent_display'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_display = $_POST['agent_display'];
  }

  $sql = 'UPDATE agents
        SET agent_name = ?, agent_tag = ?,agent_tagname = ?, agent_info = ?, agent_display = ?
        WHERE id = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_name, $agent_tag,$agent_tagname, $agent_info, $agent_display, $id));

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

  $delete_sql = "DELETE FROM agent_tag_options 
        WHERE agent_id = ?";
  $stmt = $db->prepare($delete_sql);
  $stmt->execute(array($id));
  // $stmt->execute(array($id, $agent_id));

  $tag_ids = $_POST['tag_id'];

  $split_ids = explode(',', $tag_ids);

  // $id_stmt = $db->query('SELECT id FROM agents ORDER BY id DESC LIMIT 1');
  // $agent_id = $id_stmt->fetch();

  foreach ($split_ids as $index => $tag_id) {

    $sql = "INSERT INTO agent_tag_options(tag_option_id, agent_id) 
          VALUES (?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($tag_id, $id));
  }

// タグ更新処理
if (isset($_POST['edit_tag_id']) && is_array($_POST['edit_tag_id'])) {
  $tag = implode(",", $_POST["edit_tag_id"]);

  $sql = "UPDATE agents SET agent_tag = ? WHERE id = '$id'";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($tag));
  // $reload = "edit_agent.php?id=" . $id;
  // header("Location:" . $reload);
} else {
  // echo 'チェックボックスの値を受け取れていません';
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

// // 更新処理
// if (isset($_POST['edit_tag_id']) && is_array($_POST['edit_tag_id'])) {
//   $tag = implode(",", $_POST["edit_tag_id"]);

//   $sql = "UPDATE agents SET agent_tag = ? WHERE id = '$id'";
//   $stmt = $db->prepare($sql);
//   $stmt->execute(array($tag));
//   // $reload = "edit_agent.php?id=" . $id;
//   // header("Location:" . $reload);
// } else {
//   // echo 'チェックボックスの値を受け取れていません';
// }

?>

<!DOCTYPE html>
<html>

<body>
  <?php require('../_header.php'); ?>

  <div class="util_container">
    <div class="util_sidebar">
      <div class="util_sidebar_button util_sidebar_button--selected">
        <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/home.php">エージェント管理</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/add_agent.php">エージェント追加</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href=/craft_admin/tag.php>タグ編集・追加</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/students_info.php">学生申し込み一覧</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
      </div>
    </div>
    <div class="util_content">
      <div class="util_title">
        <h2 class="util_title--text">
          エージェント編集
        </h2>
      </div>
      <div class="change">
        <form action="" method="post" enctype="multipart/form-data">
          <div class="change_item">
            <label class="change_item--label" for="agent_name">エージェント名</label>
            <input class="change_item--input" type="text" name="agent_name" value="<?= $result['agent_name'] ?>" required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_tag">エージェントタグ</label>
            <input class="change_item--input" type="text" name="agent_tag" value="<?= $result['agent_tagname'] ?>" required onclick="tag_modalOpen()" id="input">
            <input type="hidden" id="showid" name="tag_id">
          </div>
          <div class="change_item preview">
            <label class="change_item--label" for="agent_pic">エージェント画像</label>
            <img class="preview_img" src="images/<?= $result['agent_pic'] ?>" alt="" style="height: 15vh" id="agent_image">
            <img class="preview_img preview_img--hide" id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
            <label class="change_item--button" for="image" onclick="upload_file()">+ ファイルをアップロード</label>
            <input class="change_item--image preview_input" id="image" type="file" name="agent_pic" accept='image/*' onchange="previewImage(this);">
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
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_info">エージェント説明</label>
            <textarea class="change_item--textarea" name="agent_info"><?= $result['agent_info'] ?></textarea>
          </div>
          <div class="change_item dropdown">
            <label class="change_item--label" for="agent_display">エージェント掲載期間</label>
            <select class="change_item--select" name="agent_display">
              <option value="1">1ヶ月</option>
              <option value="3">3ヶ月</option>
              <option value="6">6ヶ月</option>
              <option value="12">12ヶ月</option>
            </select>
            <!-- <span>ヶ月</span> -->
          </div>

          <input class="change_button" type="submit" value="変更を保存" name="submit">
        </form>
      </div>

    </div>
  </div>

  <!-- ここからtag_modal -->
  <script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js?ver=1.12.2'></script>
  <script>
    $(function() {
      $('#confirm_button').on('click', function() {
        // モーダルで選択した内容を反映させる処理
        let string = [];
        let id = [];

        $("input[name=tags]:checked").each(function() {
          string.push($(this).val());
          // 選択した値の id を保存する処理
          id.push($(this).attr('id'));
          $('#showid').val(id);
        });
        $("#input").val(string.join('、'));
      });
    });
  </script>

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

                <input type="checkbox" name="tags" id="<?= $tag['id'] ?>" value="<?= $tag['tag_option'] ?>">
                <label for="tag">

                  <?= $tag['tag_option'] ?>
                </label>

              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="tag_modal_container--buttons">
          <button onclick="tag_modalClose()" type="button" class="tag_modalClose">戻る</button>
          <button onclick="tag_modalClose()" type="button" id="confirm_button"  class="tag_decision">決定</button>
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