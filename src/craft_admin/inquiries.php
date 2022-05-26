<?php
session_start();
require('../dbconnect.php');

$stmt = $db->query("SELECT students_agent.id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, delete_student_application.agent_name, delete_student_application.response, delete_student_application.time
                    FROM students_contact 
                    JOIN students_agent ON students_contact.id = students_agent.student_id
                    JOIN delete_student_application on delete_student_application.application_id = students_agent.id
                    -- WHERE delete_student_application.display = 1;

                    -- FROM delete_student_application
                    -- JOIN students_contact ON delete_student_application.student_id = students_contact.id
                    -- JOIN students_agent ON delete_student_application.student_id = students_agent.student_id
                    ;");
$results = $stmt->fetchAll();

// // 削除関連
// if (isset($_POST['delete']) && $_POST["delete"]) {
//   $button_delete = key($_POST['delete']); //$button_deleteには押された番号が入る

//   $sql = "START TRANSACTION;
          
//           UPDATE students_agent
//           SET 
//           deleted_at = CURRENT_TIMESTAMP,
//           status = '削除済み' 
//           WHERE id = ?;

//           UPDATE delete_student_application
//           SET response = '削除済み'
//           WHERE application_id = ?;

//           COMMIT;
//           ";
//   $stmt = $db->prepare($sql);
//   $stmt->execute(array($button_delete, $button_delete));

//   header('Location: http://localhost/craft_admin/inquiries.php');
// }

// //削除したくない場合
// if (isset($_POST['keep']) && $_POST["keep"]) {
//   $button_keep = key($_POST['keep']); //$buttonには押された番号が入る

//   $sql = "START TRANSACTION;
          
//           UPDATE students_agent
//           SET 
//           status = '有効' 
//           WHERE id = ?;

//           UPDATE delete_student_application
//           SET response = '削除なし'
//           WHERE application_id = ?;

//           COMMIT;";
//   $stmt = $db->prepare($sql);
//   $stmt->execute(array($button_keep, $button_keep));

//   header('Location: http://localhost/craft_admin/inquiries.php');
// }




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
      <div class="manageinquiries">
        <div class="cont_for_scroll">
        <table class="manageinquiries_table" border=1; style=border-collapse:collapse;>
          <tr>
            <th>申請ID</th>
            <th>対応状況</th>
            <th>申請日時</th>
            <th>氏名</th>
            <th>メールアドレス</th>
            <th>申込エージェント名</th>
            <th>操作</th>
          </tr>

          <form action="delete_student_application.php?id=" method="POST">
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
            <div class="manageinquiries_table--control">
              <!-- <input type="hidden" name="hidden[<?= $result['id']; ?>]" value="削除"> -->
              <input type="submit" class='util_action_button util_action_button--delete space_for_inquiries' name="delete[<?= $result['id']; ?>]" value="削除">
              <!-- <input type="hidden" name="hidden_keep[<?= $result['id']; ?>]" value="キープ"> -->
              <input type="submit" class='util_action_button util_action_button--list' name="keep[<?= $result['id']; ?>]" value="削除しない">
            </div>
            
          
          <?php

              echo "</th>";

          ?>

          <?php

              echo "</tr>";
          };
              echo "</table>";

              echo "</div>";

              echo "</div>";

          ?>
                </form>

            </div>
  </div>

  

  <?php require('../_footer.php'); ?>

</body>

</html>