<?php

// とりあえず edit_agent.php をコビペしてきただけ
require('../dbconnect.php');

// 画像以外の更新
if (isset($_POST['submit'])) {
  $agent_name = $_POST['agent_name'];
  $agent_tag = $_POST['agent_tag'];
  $agent_info = $_POST['agent_info'];
  if (isset($_POST['agent_display'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_display = $_POST['agent_display'];
  }

  // 画像更新
  $target_dir = "images/";
  $target_file = $target_dir . basename($_FILES["agent_pic"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  // 予想
  // INSERT INTO文 は一回で書かないとだから、編集画面みたいに分けて書けない
  // 画像をアップロードして、さらに登録ボタンが押されたら SQL文を書く仕組みにした！ （どうせ画像の登録は必要になるから）

  $sql = 'INSERT INTO agents(agent_name, agent_pic, agent_info, agent_display) 
          VALUES (?, ?, ?, ?)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_name, $_FILES['agent_pic']['name'], $agent_info, $agent_display));

  /**
   * ここからタグ系の処理イメージ記述します
   */
  // input[type=hidden]のtag_id
  /**
   * ex. '1,3,4,5'
   *
   * @var string $tag_ids
   */
  $tag_ids = $_POST['tag_id'];
  // タグはカンマ区切りの文字列でフォームから送信されてくる
  // まずカンマ区切りで切り離してあげて配列に入れたい
  // explodeを利用すると便利 @see https://www.php.net/manual/ja/function.explode.php
  // explode(区切り文字, 区切りたい文字, 何番目の区切り文字から区切るか);
  // explode(',', $tag_ids);
  // 今回は上記で上手く動く
  /**
   * ex. [1,3,4,5]
   * 
   * @var string[] $split_ids
   */
  $split_ids = explode(',', $tag_ids);

  // print_r($split_ids);

  
  // 数字の配列が手に入ったのでそれぞれのカラムに保存していく

  // ここからパターンがわかれます パターン1はメンテナンス性などを考えると辛いので、パターン2がおすすめです
  // パターン1 agents テーブルにどのタグを選択中かカラムを追加する ALTER TABLE テーブル名 ADD (tag_1 tinyint(1), tag_2 tinyint(1), ...); tinyint(1)は0か1のみ受け付けるtrue, falseのDB版です
  // パターン2 agents と tag_options を紐付ける間のテーブルを作成する テーブル構成は以下参照
  /**
   * CREATE TABLE agent_tag_options (
   *   id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
   *   tag_option_id INT NOT NULL
   *   agent_id INT NOT NULL,
   * );
   */
  // どちらのテーブルですすめる場合でもforeachで数字分回してください
  // パターン1
  // foreach($split_ids as $index => $id) {
  //   // パターン1であれば $tag_id_1 ~ $tag_id_XX まで変数を先に用意して全ての値をfalse
       // あとはswitch文を記述して case id === 1 だったら $tag_id_1 = true; に変える
  // }

  $id_stmt = $db->query('SELECT id FROM agents ORDER BY id DESC LIMIT 1');
  $agent_id = $id_stmt->fetch();
  // print_r($agent_id);

  // パターン2
  foreach($split_ids as $index => $id) {
      // パターン2はagentsがinsertされた後に
      // agent_tag_optionsに tag_option_id = $id, $agent_id = 新しく作ったエージェントID のinsertを行うだけ

      

      $sql = "INSERT INTO agent_tag_options(tag_option_id, agent_id) 
          VALUES (?, ?)";
      $stmt = $db->prepare($sql);
      $stmt->execute(array($id, $agent_id['id']));
      // $stmt->execute(array($id, $agent_id));
  }
  // パターン2でどのタグを選択したか表示するときは以下のSQLで取れます
  // select * from agents inner join agent_tag_options on agent_tag_options.agent_id = agents.id where agents.id = 指定ID;

  // こっちは全部載ってる
  // select agent_tag_options.id, agent_tag_options.agent_id, agents.agent_name, agent_tag_options.tag_option_id, tag_options.tag_option from agent_tag_options inner join tag_options on agent_tag_options.tag_option_id = tag_options.id inner join agents on agent_tag_options.agent_id = agents.id;

  // header('Location: http://localhost/craft_admin/home.php');
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
          <input class="change_button" type="submit" value="追加" name="submit">
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
