<?php
session_start();
require('../dbconnect.php');

// 画像 & エージェント名表示用
$stmt = $db->query("SELECT * FROM agents");
$results = $stmt->fetchAll();


// 表示する処理
if (isset($_POST['show'])) {
  $show = key($_POST['show']); 

  $sql = "UPDATE agents
          SET hide = 0
          WHERE id = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($show));
} 

// 隠す処理
if (isset($_POST['hide'])) {
  $hide = key($_POST['hide']); 

  $sql = "UPDATE agents
          SET hide = 1
          WHERE id = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($hide));
} 

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
</head>

<body>
  <?php require('../_header.php'); ?>
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
        <a class="util_sidebar_link" href="/craft_admin/tag.php">タグ編集・追加</a>
        <i class="fas fa-angle-right"></i>
      </div>
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/students_info.php">学生申し込み一覧</a>
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
          エージェント管理
        </h2>
      </div>
      <div class="home-list">
        <div class="home-list_labels">
          <div class="home-list_labels--left">エージェント</div>
          <div class="home-list_labels--middle">表示状態</div>
          <div class="home-list_labels--right">操作</div>

        </div>
        <form action="" method="POST">
        <?php foreach ($results as $result) : ?>
          <div class="home-agents">

            <div class="home-agents_info">
              <img class="home-agents_info--img" src="./images/<?= $result['agent_pic'] ?>" alt="" style="height: 6.5vh">
              <p class="home-agents_info--name"><?= $result['agent_name'] ?></p>
            </div>
            
            <div class="home-agents_display">
        
            <?php 
            // hide = 0 ： 表示されている
            $sql = 'SELECT count(*) FROM agents WHERE id = ? AND hide = 0';
            $stmt = $db->prepare($sql);
            $stmt->execute(array($result['id']));
            $display = $stmt->fetch();
            // 表示されているなら、隠すオプションを表示
            if ($display[0] == 1) {
            ?>
            
            <input type="submit" value="&#xf06e;" class="fas home-agents_display--eye" name="hide[<?= $result['id'] ?>]" >
            
            
            <!-- 表示されていないなら、見せるオプションを表示 -->
            <?php } else { ?>

            <input type="submit" value="&#xf070;" class="fas home-agents_display--eye" name="show[<?= $result['id'] ?>]">

            <?php } ?>
            
              

              
            </div>

            <div class="home-agents_buttons">
              <a href="./edit_agent.php?id=<?= $result['id'] ?>" class="util_action_button util_action_button--edit">編集</a>

              <!-- <button class="sakujyo" onclick="modalOpen()">削除</button> -->
              <button type="button" class="util_action_button util_action_button--delete" onclick="deleteModal(<?= $result['id'] ?>)">削除</button>

              <a href="./students_info.php" class="util_action_button util_action_button--list">申込一覧</a>
            </div>
          </div>
          <!-- ここからmodal -->
          <div id="util_deletemodal<?= $result['id'] ?>" class="util_modalcont">
            <div class="util_deletemodal">
              <p class="util_deletemodal_alert">本当に削除しますか？</p>
              <div class="util_deletebuttons">
                <button type="button" class="util_deletebuttons_item util_deletebuttons_item--no" onclick="closeFunction(<?= $result['id'] ?>)">いいえ</button>
                <a href="./delete_agent.php?id=<?= $result['id'] ?>">
                  <button type="button" class="util_deletebuttons_item util_deletebuttons_item--yes" onclick="deleteFunction(<?= $result['id'] ?>)">はい</button>
                </a>
              </div>
            </div>
          </div>
          <!-- ここから削除完了画面 -->
          <div id="util_modalcont<?= $result['id'] ?>" class="util_modalcont">
            <p class="util_modalcont_text">削除されました。</p>
          </div>

          
        <?php endforeach; ?>
        </form>
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
          let modalComplete = document.getElementById(`util_modalcont${id}`);
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

    


    
  </script>

  

</body>

</html>