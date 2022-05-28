<?php
session_start();
require('../dbconnect.php');

$stmt = $db->query("SELECT * FROM agent_inquiries");
$results = $stmt->fetchAll();




if (isset($_POST['send_response'])) {

  $agent_id = key($_POST['send_response']);

  $sql = "SELECT * FROM agents WHERE id = ?";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_id));
  $agent = $stmt->fetch();


  $agent_name = $agent['agent_name'];

  // var_dump($agent_id);

  $sql_email = "SELECT * FROM agent_users WHERE id = ?";
  $stmt = $db->prepare($sql_email);
  $stmt->execute(array($agent_id));
  $email = $stmt->fetch();

  $to = $email['notify_email'];


  // $sql = "SELECT * FROM agent_users JOIN students_agent ON students_agent.agent = agent_users.agent_name WHERE students_agent.agent_id = ? LIMIT 1";
  $sql = "SELECT * FROM agent_users JOIN students_agent ON students_agent.agent_id = agent_users.id WHERE students_agent.agent_id = ? LIMIT 1";
  $mail_stmt = $db->prepare($sql);
  $mail_stmt->execute(array($agent_id));

  $email = $mail_stmt->fetch();

  $message_info = $_POST['reply'];

  // $to      = "craft@boozer.com";
  $subject = "お問合せの新規返信がございます";
  $message = "

    ${agent_name}様

    ${message_info} 
    
    以下でご確認ください
    http://localhost/agent_admin/login/login.php
    ";
  // 文字列の中で変数を展開
  // $moji = "apple"
  // echo "${moji}"
  // ${変数名}で展開されます
  $headers = "From: craft@boozer.com";

  mb_send_mail($to, $subject, $message, $headers);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
  <title>お問い合わせ管理</title>
</head>

<body>
  <?php require('../_header.php'); ?>
  <div class="util_container">
    <div class="util_sidebar">
      <div class="util_sidebar_button">
        <a class="util_sidebar_link" href="/craft_admin/home.php">エージェント管理</a>
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
      <div class="util_sidebar_button  util_sidebar_button--selected">
        <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/inquiries.php">お問合せ管理</a>
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
          お問合せ管理
        </h2>
        <div class="tab_container">
          <div class="tab-area">
            <div class="tab">
              <a class="tab__link" href="contact_management.php">学生から</a>
            </div>
            <div class="tab  active">
              <a class="tab__link__active" href="inquiries_agent.php">
                エージェントから
              </a>
            </div>
            <div class="tab">
              <a class="tab__link" href="inquiries_delete.php">
                削除依頼
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- 並び替え結果 -->
      <div class="manageinquiries">
        <div class="cont_for_scroll">
          <table class="manageinquiries_table" border=1; style=border-collapse:collapse;>
            <tr>
              <th class="dontwrap">ID</th>
              <th class="dontwrap">エージェント</th>
              <th>氏名</th>
              <th>メールアドレス</th>
              <th>項目</th>
              <th>詳細</th>
              <th>操作</th>
            </tr>

            <form action="delete_student_application.php?id=" method="POST">
              <?php

              foreach ($results as $result) {

                echo "<tr>";

                echo "<th>";
                echo $result['id'];
                echo "</th>";

                echo "<th>";
                echo $result['agent_name'];
                echo "</th>";

                echo "<th class='dontwrap'>";
                echo $result['name'];
                echo "</th>";

                echo "<th>";
                echo $result['email'];
                echo "</th>";

                echo "<th style='width: 80px'>";
                echo $result['content'];
                echo "</th>";

                echo "<th>";
                echo $result['details'];
                echo "</th>";

                echo "<th>"

              ?>
                <div class="moreinfo_buttons">
                  <button onclick="modalOpen(<?= $result['id'] ?>)" type="button" class="moreinfo_buttons--reply">返信</button>
                </div>


              <?php

                echo "</th>";



                echo "</tr>";
              };
              ?>
          </table>

        </div>

      </div>

      </form>

    </div>
  </div>
  <!-- ============================ここからモーダル============================ -->
  <div id="modal_bg" class="replymodal_bg">

    <?php foreach ($results as $result) {  ?>
      <div id="modal<?= $result['id'] ?>" class="replymodal_container">
        <form action="" method="POST">
          <div class="replymodal">
            <p class="replymodal_text" for="message">返信内容</p>
            <textarea class="replymodal_textarea" name="reply"></textarea>
            <div class="replymodal_buttons">
              <button onclick="modalClose(<?= $result['id'] ?>)" type="button" class="replymodal_buttons--back">戻る</button>
              <button onclick="close()" type="submit" name="send_response[<?= $result['agent_id'] ?>]" id="confirm_button" class="replymodal_buttons--confirm">メール送信</button>
            </div>
          </div>
        </form>
      </div>
      <!-- ここから削除完了画面 -->
      <div id="modal_done<?= $result['id'] ?>" class="replymodal_container">
        <div class="replymodal">
          <p class="util_deletemodal_message">返信メールの送信が完了しました。</p>
        </div>
      </div>
    <?php

    };
    ?>
  </div>


  <script>
    // const modal = document.getElementById('modal');
    // const modaldone = document.getElementById('modal_done')
    const bg = document.getElementById('modal_bg');

    //削除ボタンをクリックした時の処理
    modalOpen = function(id) {
      let modal = document.getElementById(`modal${id}`);

      function open() {
        modal.style.display = 'block';
        bg.style.display = 'block';
      };
      open();
    }

    modalClose = function(id) {
      let modal = document.getElementById(`modal${id}`);

      function close() {
        modal.style.display = 'none';
        bg.style.display = 'none';
      };
      close();
    }


    function modalDelete() {
      modal.style.display = 'none';
      modaldone.style.display = 'block';
    }

    window.onclick = function(event) {
      if (event.target == bg) {
        // let modal = document.getElementById(`modal${id}`);
        // modal.style.display = "none";
        bg.style.display = 'none';
      }
    }
  </script>



      <?php require('../_footer.php'); ?>

</body>

</html>