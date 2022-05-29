<?php
session_start();
require('../dbconnect.php');

// ログインしていないままアクセスしようとしている場合エラーページに飛ばす
if (!isset($_SESSION['id'])) {
  header('Location: ./login/login_error.php');
}

// 画像以外の更新
if (isset($_POST['submit'])) {


  $agent_name = $_POST['agent_name'];
  // これはただタグを表示させるだけのもの
  $agent_tagname = $_POST['agent_tag'];
  $agent_tag = $_POST['tag_id'];
  $agent_title = $_POST['agent_title'];
  $agent_info = $_POST['agent_info'];
  $agent_point1 = $_POST['agent_point1'];
  $agent_point2 = $_POST['agent_point2'];
  $agent_point3 = $_POST['agent_point3'];
  $start_display = $_POST['agent_display_start'];
  $end_display = $_POST['agent_display_end'];
  $agent_sort1 = $_POST['agent_sort1'];
  $agent_sort2 = $_POST['agent_sort2'];
  $agent_sort3 = $_POST['agent_sort3'];


  // 画像更新
  $target_dir = "images/";
  $target_file = $target_dir . basename($_FILES["agent_pic"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  if (move_uploaded_file($_FILES["agent_pic"]["tmp_name"], $target_file)) {
    // 画像更新
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["agent_pic"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  } else {
    // echo "Sorry, there was an error uploading your file.";
  }

  // 予想
  // INSERT INTO文 は一回で書かないとだから、編集画面みたいに分けて書けない
  // 画像をアップロードして、さらに登録ボタンが押されたら SQL文を書く仕組みにした！ （どうせ画像の登録は必要になるから）

  $sql = 'INSERT INTO agents(agent_name, agent_pic, agent_tag, agent_tagname, agent_title, agent_info, agent_point1, agent_point2, agent_point3, start_display, end_display, hide) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_name, $_FILES['agent_pic']['name'], $agent_tag, $agent_tagname, $agent_title, $agent_info, $agent_point1, $agent_point2, $agent_point3, $start_display, $end_display));

  /* ここからタグ系の処理イメージ記述します */
  $tag_ids = $_POST['tag_id'];

  $split_ids = explode(',', $tag_ids);

  $id_stmt = $db->query('SELECT id FROM agents ORDER BY id DESC LIMIT 1');
  $agent_id = $id_stmt->fetch();

  foreach ($split_ids as $index => $id) {

    $sql = "INSERT INTO agent_tag_options(tag_option_id, agent_id) 
          VALUES (?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($id, $agent_id['id']));
    // $stmt->execute(array($id, $agent_id));
  }

  //sortの追加処理

  $sql = 'INSERT INTO sort_options(category_id, sort_option, hide) 
          VALUES (100, ?, 0)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_sort1));

  $stmt = $db->query("SELECT id FROM sort_options ORDER BY id DESC");
  $sort_id1 = $stmt->fetch();

  $sql = 'INSERT INTO agent_sort_options(sort_option_id, agent_id)
          VALUES (?, ?)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($sort_id1['id'], $agent_id['id']));


  $sql = 'INSERT INTO sort_options(category_id, sort_option, hide) 
          VALUES (101, ?, 0)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_sort2));

  $stmt = $db->query("SELECT id FROM sort_options ORDER BY id DESC");
  $sort_id2 = $stmt->fetch();

  $sql = 'INSERT INTO agent_sort_options(sort_option_id, agent_id)
          VALUES (?, ?)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($sort_id2['id'], $agent_id['id']));


  $sql = 'INSERT INTO sort_options(category_id, sort_option, hide) 
          VALUES (102, ?, 0)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_sort3));

  $stmt = $db->query("SELECT id FROM sort_options ORDER BY id DESC");
  $sort_id3 = $stmt->fetch();

  $sql = 'INSERT INTO agent_sort_options(sort_option_id, agent_id)
          VALUES (?, ?)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($sort_id3['id'], $agent_id['id']));



  header('Location: home.php');
  exit;
}
?>
<?php
// タグ表示

//既存データの表示
$stmt = $db->query('SELECT * FROM tag_categories');

$categories = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>

<head>
  <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
</head>

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
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/home.php">エージェント管理</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button  util_sidebar_button--selected">
        <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/add_agent.php">エージェント追加</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/tag.php">タグ編集・追加</a>
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
        <a class="util_sidebar_link" href="/userpage/top.php" target="_blank">ユーザー用サイトへ</a>
        <i class="fas fa-angle-right"></i>
      </div>
    </div>
    <div class="util_content">
      <div class="util_title">
        <h2 class="util_title--text">
          エージェント追加
        </h2>
      </div>

      <div class="change">
        <form action="" method="post" enctype="multipart/form-data" id="postForm">
          <div class="change_item">
            <label class="change_item--label" for="agent_name" id="agent_name" >エージェント名</label>
            <input class="change_item--input" type="text" name="agent_name" placeholder="30字以内" maxlength="30">
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_tag">エージェントタグ</label>
            <input class="change_item--input" type="text" name="agent_tag" required readonly="readonly" placeholder="クリック" onclick="tag_modalOpen()" id="input">
            <input type="hidden" id="showid" name="tag_id">
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_sort1">公開求人数</label>
            <input class="change_item--input" type="number" name="agent_sort1" placeholder="数字のみ" required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_sort2">非公開求人数</label>
            <input class="change_item--input" type="text" name="agent_sort2" placeholder="数字 or - " required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_sort3">利用者数</label>
            <input class="change_item--input" type="number" name="agent_sort3" placeholder="数字のみ" required>
          </div>
          <div class="change_item preview">
            <label class="change_item--label" for="agent_pic">エージェント画像</label>
            <img class="preview_img" src="images/grey.png" id="add_image" style="height: 15vh;"></img>
            <img class="preview_img preview_img--hide" id="add_preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
            <label class="change_item--button" for="image" onclick="upload_file()">+ ファイルをアップロード</label>
            <input class="change_item--image preview_input" id="image" type="file" name="agent_pic" accept='image/*' onchange="previewImage(this);">
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
            <label class="change_item--label" for="agent_title">エージェントスローガン</label>
            <input class="change_item--input" type="text" name="agent_title" maxlength="30" placeholder="30字以内" required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_info">エージェント説明</label>
            <textarea class="change_item--textarea" name="agent_info"></textarea>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_point1">エージェント特徴１</label>
            <input class="change_item--input" type="text" name="agent_point1" maxlength="30" placeholder="30字以内" required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_point2">エージェント特徴２</label>
            <input class="change_item--input" type="text" name="agent_point2" maxlength="30" placeholder="30字以内" required>
          </div>
          <div class="change_item">
            <label class="change_item--label" for="agent_point3">エージェント特徴３</label>
            <input class="change_item--input" type="text" name="agent_point3" maxlength="30" placeholder="30字以内" required>
          </div>
          <div class="change_item dropdown">
            <label class="change_item--label" for="agent_display">エージェント掲載期間</label>
            <div class="dropdown_container form_section">
              <input type="text" id="start_display" name="agent_display_start" value="" class="detepicker" pattern="^[a-zA-Z0-9]+$" required>
              <p class="between"> 〜 </p>
              <input type="text" name="agent_display_end" id="end_display" value="" pattern="^[a-zA-Z0-9]+$" required>
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
          <input class="change_button" class="add confirm-btn" type="submit" value="追加" name="submit">
        </form>
      </div>
    </div>
  </div>

  

  <!-- ここからバリデーション -->
  <script>
    // window.addEventListener('DOMContentLoaded', () => {

    // 「送信」ボタンの要素を取得
    const submit = document.querySelector('.submit');

    // 「送信」ボタンの要素にクリックイベントを設定する
    submit.addEventListener('click', (e) => {

      // 「お名前」入力欄の空欄チェック
      //要素取得
      const start_display = document.querySelector('#start_display');
      const end_display = document.querySelector('#end_display');
      // エラーメッセージを表示させる要素を取得
      const errMsgName1 = document.querySelector('.start_display_error');
      const errMsgName2 = document.querySelector('.end_display_error');

      if (!start_display.value) {

        // デフォルトアクションをキャンセル
        e.preventDefault();
        // クラスを追加(エラーメッセージを表示する)
        errMsgName1.classList.add('form-invalid');
        // エラーメッセージのテキスト
        errMsgName1.textContent = '掲載期間が選択されていません';
        // クラスを追加(フォームの枠線を赤くする)
        start_display.classList.add('input-invalid');
        // 後続の処理を止める
        return;
      } else {
        // エラーメッセージのテキストに空文字を代入
        errMsgName1.textContent = '';
        // クラスを削除
        start_display.classList.remove('input-invalid');
      }

      if (!end_display.value) {
        // デフォルトアクションをキャンセル
        e.preventDefault();
        // クラスを追加(エラーメッセージを表示する)
        errMsgName2.classList.add('form-invalid');
        // エラーメッセージのテキスト
        errMsgName2.textContent = '掲載期間が表示されていません';
        // クラスを追加(フォームの枠線を赤くする)
        end_display.classList.add('input-invalid');
        // 後続の処理を止める
        return;
      } else {
        // エラーメッセージのテキストに空文字を代入
        errMsgName2.textContent = '';
        // クラスを削除
        end_display.classList.remove('input-invalid');
      }

    }, false);
    // }, false);
  </script>

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
                <label for="tag">

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