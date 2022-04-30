<?php

use LDAP\Result;

session_start();
require('../dbconnect.php');

// 画像 & エージェント名表示用
$stmt = $db->query("SELECT * FROM agents");
$results = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

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
        <a href="/craft_admin/tag.php">タグ編集・追加
        </a>
      </div>
      <div class="usersite">
        <a href="">ユーザー用サイトへ</a>
      </div>
    </div>
    <div class="agent_rightcontainer">
      <h2>
        <div class="agent_title">

          エージェント管理
        </div>
      </h2>
      <div class="agent_smallrightcontainer">
        <div class="agent_smallrightcontainer_container">
          <div class="agent_smallcontainer_title">エージェント</div>
          <div class="agent_smallcontainer_control">操作</div>

        </div>

        <?php foreach ($results as $result) : ?>
          <div class="agent_all" id="<?= $result['id']; ?>">

            <div class="agent_ind">

              <img src="./images/<?= $result['agent_pic'] ?>" alt="" style="height: 18.7vh">
              <p><?= $result['agent_name'] ?></p>
            </div>
            <div class="agent_buttons">
              <a href="./edit.php?id=<?= $result['id'] ?>" style="text-decoration: none">

                <button class="hensyu">編集</button>
              </a>



              <input name="delete" class="delete" />
              <label for="delete" class="sakujyo" id="<?= $result['id'] ?>" onclick="modalOpen()">削除</label>





              <a href="" style="text-decoration: none">
                <button class="moushikomi">申込一覧</button>
              </a>
            </div>
          </div>

        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- ここからmodal -->
  <div id="modal">
    <div class="modal_container">

      <p class="alert">本当に削除しますか？</p>
      <div class="delete_buttons">
        <button class="no" onclick="modalClose()">いいえ</button>
        <?php if(isset($_POST['id'])){
    $id = $_POST['id'];}?>
        <!-- <a href="./delete.php?id=" style="text-decoration: none"> -->
        <button class="yes" id="delete_modal" onclick="deleteAgent()">はい
        </button>
        <!-- </a> -->
      </div>
    </div>
  </div>
  <!-- ここから削除完了画面 -->
  <div id="modal_complete">
    <p>削除されました。</p>
  </div>

  <?php require('../_footer.php'); ?>
  <script>
    const modal = document.getElementById('modal');

    const modalComplete = document.getElementById('modal_complete');

    function modalOpen() {
      modal.style.display = 'block';

    };

    function modalClose() {
      modal.style.display = 'none';
    };
    
    let id = '<?= $id ?>';
    
    function deleteAgent() {
      window.location.href = "./delete.php?id=" + id;
      // console.log(id);
      modal.style.display = 'none';
      modalComplete.style.display = 'block';
    };
    
  </script>
</body>

</html>