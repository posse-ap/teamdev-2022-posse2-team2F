<?php

// とりあえず edit_agent.php をコビペしてきただけ

require('../dbconnect.php');



// 画像以外の更新

if (isset($_POST['submit'])) {


  $agent_name = $_POST['agent_name'];
  $agent_tag = $_POST['agent_tag'];
  $agent_info = $_POST['agent_info'];
  // $agent_display = $_POST['agent_display'];
  if (isset($_POST['agent_display'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_display = $_POST['agent_display'];
  }


  // $sql_tag = 'INSERT INTO agents_tags(agent_id, tag_id) 
  // VALUES (?, ?)';
  // $stmt = $db->prepare($sql_tag);
  // $stmt->execute(array($agent_name, $agent_info, $agent_display));

  // $sql = 'INSERT INTO agents(agent_name, agent_tag, agent_info, agent_display) 
  //         VALUES (?, ?, ?, ?)';
  // $stmt = $db->prepare($sql);
  // $stmt->execute(array($agent_name, $agent_tag, $agent_info, $agent_display));

  // 画像更新
  $target_dir = "images/";
  $target_file = $target_dir . basename($_FILES["agent_pic"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

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

  $tag_id = $_POST['tag_id'];
  echo $tag_id;



  // header('Location: http://localhost/craft_admin/home.php');
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
// error_reporting(0);
// if (isset($_POST['tag']) && is_array($_POST['tag'])) {
//   $tag = implode("、", $_POST["tag"]);

// //   $sql = "UPDATE agents SET agent_tag = ? WHERE id = '$id'";
// //   $stmt = $db->prepare($sql);
// //   $stmt->execute(array($tag));
// //   $reload = "edit.php?id=" . $id;
// //   header("Location:" . $reload);
// // } else {
//   // echo 'チェックボックスの値を受け取れていません';
// }

?>




<!DOCTYPE html>
<html>

<body>
  <?php require('../_header.php'); ?>





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
        <a class="util_sidebar_link" href="/craft_admin/students_info.php">学生申し込み一覧</a>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
      </div>
    </div>
    <div class="util_content">
      <div class="util_title">
        <h2 class="util_title--text">
          エージェント追加
        </h2>
      </div>


      <!-- <div class="change">
        <form action="" method="post" enctype="multipart/form-data">
          <div class="agent_img">
            <label for="agent_pic">エージェント画像</label>
            <textarea id="add_image" name="agent_pic_blank" readonly="readonly" style="width: 48vw; height: 15vh; overflow-x: scroll;"></textarea>
            <img id="add_preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
            <label for="image" class="file_upload_button" onclick="upload_file()">+ ファイルをアップロード</label>
            <input id="image" type="file" name="agent_pic" accept='image/*' onchange="previewImage(this);">
            
          </div>
        </form>
      </div> -->

      <div class="change">
        <form action="" method="post" enctype="multipart/form-data">
          <div class="change_item">
            <label class="change_item--label" for="agent_name">エージェント名</label>
            <input class="change_item--input" type="text" name="agent_name" required>
          </div> 
          <div class="change_item">
            <label class="change_item--label" for="agent_tag">エージェントタグ</label>
            <input class="change_item--input" type="text" name="agent_tag" required readonly="readonly" onclick="tag_modalOpen()" id="input">
            <input type="hidden" id="showid" name="tag_id">
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
            <label class="change_item--label" for="agent_info">エージェント説明</label>
            <textarea class="change_item--textarea" name="agent_info"></textarea>
          </div>
          <div class="change_item dropdown">
            <label class="change_item--label" for="agent_display">エージェント掲載期間</label>
            <select class="change_item--select" name="agent_display">
              <option value="1">1ヶ月</option>
              <option value="3">3ヶ月</option>
              <option value="6">6ヶ月</option>
              <option value="12">12ヶ月</option>
            </select>
          </div>
          <input class="change_button" type="submit" value="追加" name="submit" >
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
          // id.toString().split("");

          console.log(id);
          

          // console.log(id);
          $('#showid').val(id);

        });

        $("#input").val(string.join('、'));


          
          // console.log(this);


          // 何をしないといけない？？
          // タグ表示 input の下に value (id) を置いておく input type="hidden" を作る DONE
          // その hidden input の value と php を繋ぐ DONE
          // id = tag_options.id の時 agents に追加 


        
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


</body>

</html>