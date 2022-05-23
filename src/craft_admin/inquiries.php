<?php
session_start();
require('../dbconnect.php');

// 画像 & エージェント名表示用
$stmt = $db->query("SELECT students_agent.id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, delete_student_application.agent_name, delete_student_application.response, delete_student_application.time
                    FROM students_contact 
                    JOIN students_agent ON students_contact.id = students_agent.student_id
                    JOIN delete_student_application on delete_student_application.application_id = students_agent.id

                    -- FROM delete_student_application
                    -- JOIN students_contact ON delete_student_application.student_id = students_contact.id
                    -- JOIN students_agent ON delete_student_application.student_id = students_agent.student_id
                    ;");
$results = $stmt->fetchAll();

// 削除関連
if (isset($_POST['delete']) && $_POST["delete"]) {
  $button = key($_POST['delete']); //$buttonには押された番号が入る

  $sql = "START TRANSACTION;
          
          UPDATE students_agent
          SET 
          deleted_at = CURRENT_TIMESTAMP,
          status = '無効（削除済み）' 
          WHERE id = ?;

          UPDATE delete_student_application
          SET response = '削除済み'
          WHERE application_id = ?;

          COMMIT;
          ";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($button, $button));

  header('Location: http://localhost/craft_admin/inquiries.php');
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
      </div>
      <!-- 並び替え結果 -->
      <div class="table_container">
        <table border=1; style=border-collapse:collapse;>
          <tr>
            <th>申請ID</th>
            <th>対応状況</th>
            <th>申請日時</th>
            <th>氏名</th>
            <th>メールアドレス</th>
            <th>申込エージェント名</th>
            <th>削除</th>
          </tr>

          <form action="" method="POST">
          <?php
            foreach ($results as $result) { ?>

            

          <?php

              echo "<tr>";


              echo "<th>";
              echo $result['id'];
              echo "</th>";

              echo "<th>";
              echo $result['response'];
              echo "</th>";

              echo "<th>";
              echo $result['time'];
              echo "</th>";

              echo "<th>";
              echo $result['name'];
              echo "</th>";

              echo "<th>";
              echo $result['email'];
              echo "</th>";

              echo "<th>";
              echo $result['agent_name'];
              echo "</th>";

              echo "<th>"

          ?>

            <input type="hidden" name="hidden[<?= $result['id']; ?>]" value="削除">
            <input type="submit" class='util_action_button util_action_button--delete' name="delete[<?= $result['id']; ?>]" value="削除">
          
          <?php

              echo "</th>";

          ?>

          <?php

              echo "</tr>";
          };
              echo "</table>";

              echo "</div>";
          ?>
                </form>

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