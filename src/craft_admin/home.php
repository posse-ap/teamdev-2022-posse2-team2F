<?php
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
  <div class="util_container">
    <div class="util_sidebar">
      <div class="util_sidebar_button util_sidebar_button--selected">
        <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/home.php">エージェント管理</a>

      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/add_agent.php">エージェント追加</a>
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
          エージェント管理
        </div>
      </h2>
      <div class="home_list">
        <div class="home_list_labels">
          <div class="home_list_labels--left">エージェント</div>
          <div class="home_list_labels--right">操作</div>

        </div>

        <?php foreach ($results as $result) : ?>
          <div class="home_list_agents">

            <div class="home_list_agents_info">

              <img class="home_list_agents_info--img" src="./images/<?= $result['agent_pic'] ?>" alt="" style="height: 18.7vh">
              <p class="home_list_agents_info--name"><?= $result['agent_name'] ?></p>
            </div>
            <div class="home_list_agents_buttons">
              <a href="./edit_agent.php?id=<?= $result['id'] ?>" style="text-decoration: none">

                <button class="home_list_agents_buttons_item home_list_agents_buttons--edit">編集</button>
              </a>

              <!-- <button class="sakujyo" onclick="modalOpen()">削除</button> -->
              <button class="home_list_agents_buttons_item home_list_agents_buttons--delete" onclick="deleteModal(<?= $result['id'] ?>)">削除</button>



              <a href="" style="text-decoration: none">
                <button class="home_list_agents_buttons_item home_list_agents_buttons--list">申込一覧</button>
            </div>
            </a>
          </div>
          <!-- ここからmodal -->
          <div id="util_deletemodal<?= $result['id'] ?>" class="util_deletemodal">
            <div class="util_deletemodal_cont">

              <p class="util_deletemodal_cont--alert">本当に削除しますか？</p>
              <div class="util_deletemodal_cont_buttons">
                <button class="util_deletemodal_cont_buttons--item util_deletemodal_cont_buttons--no" onclick="closeFunction(<?= $result['id'] ?>)">いいえ</button>
                <a href="./delete_agent.php?id=<?= $result['id'] ?>" style="text-decoration: none">
                  <!-- <button class="yes" onclick="deleteAgent()">はい -->
                  <button class="util_deletemodal_cont_buttons--item util_deletemodal_cont_buttons--yes" onclick="deleteFunction(<?= $result['id'] ?>)">はい
                  
                  </button>
                </a>
              </div>
            </div>
          </div>
          <!-- ここから削除完了画面 -->
          <div id="util_deletemodal_complete<?= $result['id'] ?>" class="util_deletemodal_complete">
            <p class="util_deletemodal_complete--text">削除されました。</p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  

  <?php require('../_footer.php'); ?>


  <script>
    //ボタンをクリックした時の処理
    let deleteModal = function (id) {
          let modal = document.getElementById(`util_deletemodal${id}`);
          function modalOpen() {
            modal.style.display = 'block';
          };
          modalOpen();
    }

    let deleteFunction = function (id) {
          let modal = document.getElementById(`util_deletemodal${id}`);
          let modalComplete = document.getElementById(`util_deletemodal_complete${id}`);
          function deleteAgent() {
            modal.style.display = 'none';
            modalComplete.style.display = 'block';
          };
          deleteAgent();
    }

    let closeFunction = function (id) {
          let modal = document.getElementById(`util_deletemodal${id}`);
          
          function modalClose() {
            modal.style.display = 'none';
          };
          modalClose();
    }

    // const modal = document.getElementById('modal');

    // const modalComplete = document.getElementById('modal_complete');

    
  </script>

</body>

</html>