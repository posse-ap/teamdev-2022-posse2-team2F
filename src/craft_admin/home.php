<?php
session_start();
require('../dbconnect.php');

// ログインしていないままアクセスしようとしている場合エラーページに飛ばす
if (!isset($_SESSION['id'])) {
  header('Location: ./login/login_error.php');
}

//agent自動更新
// 画像 & エージェント名表示用
$stmt = $db->query("SELECT * FROM agents");
$results = $stmt->fetchAll();

//現在時刻の取得
$now = time();
//掲載期間超えているエージェントは自動的に非表示に
foreach ($results as $rlt) {
  $id = $rlt['id'];
  $end_time = strtotime($rlt['end_display']);
  $start_time = strtotime($rlt['start_display']);
  if ($now <= $start_time) {
    //掲載前=2
    $sql = "UPDATE agents
          SET hide = 2
          WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($id));
  } elseif ($now >= $end_time) {
    //掲載後=3
    $sql = "UPDATE agents
          SET hide = 3
          WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($id));
  }elseif ($rlt['hide'] == 0){
    $sql = "UPDATE agents
          SET hide = 0
          WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($id));
  }elseif ($rlt['hide'] == 1){
    $sql = "UPDATE agents
          SET hide = 1
          WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($id));
  }elseif($now >= $start_time){
    $sql = "UPDATE agents
          SET hide = 0
          WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($id));
  }elseif($now <= $end_time){
    $sql = "UPDATE agents
    SET hide = 0
    WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($id));
  }
}


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
  <div id="modal_bg" class="deletemodal_overlay"></div>
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
        <a class="util_sidebar_link" href="/userpage/top.php">ユーザー用サイトへ</a>
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
                $sql = 'SELECT hide FROM agents WHERE id = ?';
                $stmt = $db->prepare($sql);
                $stmt->execute(array($result['id']));
                $display = $stmt->fetch();
                // var_dump($display);

                // 表示されているなら、隠すオプションを表示
                if ($display[0] == 1) {
                ?>

                  <input type="submit" value="&#xf070;" class="fas home-agents_display--eye" name="show[<?= $result['id'] ?>]">


                  <!-- 表示されていないなら、見せるオプションを表示 -->
                <?php } elseif ($display[0] == 0) { ?>

                  <input type="submit" value="&#xf06e;" class="fas home-agents_display--eye" name="hide[<?= $result['id'] ?>]">

                <?php } elseif ($display[0] == 2) { ?>
                  <p>掲載前</p>
                <?php } elseif ($display[0] == 3) { ?>
                  <p>掲載終了</p>
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
            <!-- <div id="util_deletemodal_bg" class="util_deletemodal_bg"> -->
              <div id="util_deletemodal<?= $result['id'] ?>" class="util_deletemodal_container fixmodaltomiddle">
                <div class="util_deletemodal">
                  <p class="util_deletemodal_text">本当に削除しますか？</p>
                  <div class="util_deletemodal_buttons">
                    <button type="button" class="util_deletemodal_back" onclick="closeFunction(<?= $result['id'] ?>)">いいえ</button>
                    <a href="./delete_agent.php?id=<?= $result['id'] ?>">
                      <button type="button" class="util_deletemodal_confirm" onclick="deleteFunction(<?= $result['id'] ?>)">はい</button>
                    </a>
                  </div>
                </div>
              </div>
              <!-- ここから削除完了画面 -->
              <div id="util_modalcont<?= $result['id'] ?>" class="util_deletemodal_container fixmodaltomiddle">
                <p class="util_deletemodal_message">削除されました。</p>
              </div>
          <!-- </div> -->
          <?php endforeach; ?>
        </form>
      </div>
    </div>
  </div>



  <?php require('../_footer.php'); ?>


  <script>


    const bg = document.getElementById('modal_bg');

    //ボタンをクリックした時の処理
    let deleteModal = function(id) {
      let modal = document.getElementById(`util_deletemodal${id}`);

      function modalOpen() {
        bg.style.display = 'block';
        modal.style.display = 'block';
      };
      modalOpen();
    }

    let deleteFunction = function(id) {
      let modal = document.getElementById(`util_deletemodal${id}`);
      let modalComplete = document.getElementById(`util_modalcont${id}`);

      function deleteAgent() {
        modal.style.display = 'none';
        modalComplete.style.display = 'block';
      };
      deleteAgent();
    }

    let closeFunction = function(id) {
      let modal = document.getElementById(`util_deletemodal${id}`);

      function modalClose() {
        modal.style.display = 'none';
        bg.style.display = 'none';
      };
      modalClose();
    }

    window.onclick = function(event) {
      if (event.target == bg) {
        for (i = 1; i <= 20; i++) {
            let modal = document.getElementById(`util_deletemodal${i}`);
            modal.style.display = "none";
          bg.style.display = 'none';
        }
      }
    }
  </script>



</body>

</html>