<?php
session_start();
require('../dbconnect.php');

// ログインしていないままアクセスしようとしている場合エラーページに飛ばす
if (!isset($_SESSION['id'])) {
  header('Location: ./login/login_error.php');
}



if (isset($_GET['id'])) {


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
    $agent_title = $_POST['agent_title'];
    $agent_info = $_POST['agent_info'];
    $agent_point1 = $_POST['agent_point1'];
    $agent_point2 = $_POST['agent_point2'];
    $agent_point3 = $_POST['agent_point3'];
    $start_display = $_POST['agent_display_start'];
    $end_display = $_POST['agent_display_end'];

    $sql = 'UPDATE agents
          SET agent_name = ?, agent_tag = ?, agent_tagname = ?, agent_title = ?, agent_info = ?, agent_point1 = ?, agent_point2 = ?, agent_point3 = ?, start_display = ?, end_display = ?
          WHERE id = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($agent_name, $agent_tag, $agent_tagname, $agent_title, $agent_info, $agent_point1, $agent_point2, $agent_point3, $start_display, $end_display, $id));

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


    // タグ関連の処理 （すでに入っているものをまず消して、その後新しく INSERT する）

    $delete_sql = "DELETE FROM agent_tag_options 
          WHERE agent_id = ?";
    $stmt = $db->prepare($delete_sql);
    $stmt->execute(array($id));

    // タグの id を hidden の input から取得
    $tag_ids = $_POST['tag_id'];
    // これらを、個別の値に分けていく（ "2, 3, 4" -> "2" "3" "4" ) 
    $split_ids = explode(',', $tag_ids);

    // 分けたものを一つ一つ $tag_id として loop 処理する
    foreach ($split_ids as $index => $tag_id) {
      $sql = "INSERT INTO agent_tag_options(tag_option_id, agent_id) 
            VALUES (?, ?)";
      $stmt = $db->prepare($sql);
      $stmt->execute(array($tag_id, $id));
    }

    header('Location: home.php');
    exit;
  }

  // リンクに id がない場合、無効リンクページに飛ばす
} else {

  header('Location: warning.php');
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
  <!-- ここでカレンダー読み込み -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <div class="util_logout">
    <p class="util_logout_email"><?= $_SESSION['email'] ?></p>
    <a href="./login/logout.php">
      ログアウト
      <i class="fas fa-sign-out-alt"></i>
    </a>
  </div>
  <div class="util_container">
    <div class="util_sidebar">
      <div class="util_sidebar_button util_sidebar_button--selected">
        <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/home.php">エージェント管理</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/add_agent.php">エージェント追加</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href=/craft_admin/tag.php>タグ編集・追加</a>
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
            <input class="change_item--input" type="text" name="agent_tag" value="<?= $result['agent_tagname'] ?>" required readonly="readonly" onclick="tag_modalOpen()" id="input">
            <input type="hidden" id="showid" name="tag_id" value="<?= $result['agent_tag'] ?>">
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
            <label class="change_item--label" for="agent_title">エージェントスローガン</label>
            <input class="change_item--input" type="text" name="agent_title" value="<?= $result['agent_title'] ?>" required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_info">エージェント説明</label>
            <textarea class="change_item--textarea" name="agent_info"><?= $result['agent_info'] ?></textarea>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_point1">エージェント特徴１</label>
            <input class="change_item--input" type="text" name="agent_point1" value="<?= $result['agent_point1'] ?>" required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_point2">エージェント特徴２</label>
            <input class="change_item--input" type="text" name="agent_point2" value="<?= $result['agent_point2'] ?>" required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_point3">エージェント特徴３</label>
            <input class="change_item--input" type="text" name="agent_point3" value="<?= $result['agent_point3'] ?>" required>
          </div>
          <div class="change_item dropdown">
            <label class="change_item--label" for="agent_display">エージェント掲載期間</label>
            <!-- ここからカレンダー -->
            <div class="dropdown_container">
              <p class="start_display_error"></p>
              <p class="end_display _error"></p>
              <input type="text" id="start_display" name="agent_display_start" value="<?= $result['start_display'] ?>">
              <p class="between"> 〜 </p>
              <input type="text" name="agent_display_end" id="end_display" value="<?= $result['end_display'] ?>">
            </div>

            <script>
              const config = {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
              }
              var start_calender = document.getElementById("start_display");
              var fp = flatpickr(start_calender, config);

              var end_calender = document.getElementById("end_display");
              var fd = flatpickr(end_calender, config);
            </script>

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

  <div id="tag_modal" class="tag_modal_container">
    <form action="" method="POST">

      <div class="tag_modal">
        <?php foreach ($categories as $category) : ?>
          <div id="no<?= $category['id'] ?>" class="tag_modal_categories">
            <h2>

              <?= $category['tag_category'] ?>
            </h2>
            <?php
            $stmt = $db->prepare("SELECT * FROM tag_options WHERE category_id = ?");

            $stmt->execute(array($category['id']));
            $tags = $stmt->fetchAll();

            ?>

            <div class="tag_modal_tags">
              <?php foreach ($tags as $tag) : ?>

                <input type="checkbox" name="tags" id="<?= $tag['id'] ?>" value="<?= $tag['tag_option'] ?>">
                <label for="tags">

                  <?= $tag['tag_option'] ?>
                </label>

              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="tag_modal_buttons">
          <button onclick="tag_modalClose()" type="button" class="tag_modalClose">戻る</button>
          <button onclick="tag_modalClose()" type="button" id="confirm_button" class="tag_decision">決定</button>
        </div>

    </form>

  </div>




  <?php require('../_footer.php'); ?>

  <script type="text/javascript">
    let tag_modal = document.getElementById('tag_modal');

    function tag_modalOpen() {

      tag_modal.style.display = 'block';
    }

    function tag_modalClose() {

      tag_modal.style.display = 'none';
    }
  </script>


</body>

</html>