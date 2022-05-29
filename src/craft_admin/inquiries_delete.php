<?php
session_start();
require('../dbconnect.php');

// ログインしていないままアクセスしようとしている場合エラーページに飛ばす
if (!isset($_SESSION['id'])) {
  header('Location: ./login/login_error.php');
}

$stmt = $db->query("SELECT students_agent.id, students_agent.agent_id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, delete_student_application.agent_name, delete_student_application.response, delete_student_application.time
                    FROM students_contact 
                    JOIN students_agent ON students_contact.id = students_agent.student_id
                    JOIN delete_student_application on delete_student_application.application_id = students_agent.id
                    -- WHERE delete_student_application.display = 1;

                    -- FROM delete_student_application
                    -- JOIN students_contact ON delete_student_application.student_id = students_contact.id
                    -- JOIN students_agent ON delete_student_application.student_id = students_agent.student_id
                    ;");
$results = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>お問い合わせ管理</title>
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
        <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/contact_management.php">お問合せ管理</a>
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
          お問合せ管理
        </h2>
        <div class="tab_container">
          <div class="tab-area">
            <div class="tab">
              <a class="tab__link" href="contact_management.php">学生から</a>
            </div>
            <div class="tab">
              <a class="tab__link" href="inquiries_agent.php">
                エージェントから
              </a>
            </div>
            <div class="tab  active">
              <a class="tab__link__active" href="inquiries_delete.php">
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

                ?>

                <th>
                  <div class="manageinquiries_table--control">
                    <button type="button" class="util_action_button util_action_button--delete space_for_inquiries" onclick="deleteModal(<?= $result['id'] ?>)">削除</button>
                    <button type="button" class="util_action_button util_action_button--list" onclick="keepModal(<?= $result['id'] ?>)">保留</button>
                  </div>

                  <!-- ここから削除用modal -->
                  <div id="util_deletemodal<?= $result['id'] ?>" class="util_deletemodal_container fixmodaltomiddle">
                    <div class="util_deletemodal">
                      <p class="util_deletemodal_text">本当に削除しますか？</p>
                      <div class="util_deletemodal_buttons">
                        <button type="button" class="util_deletemodal_back" onclick="closeFunction(<?= $result['id'] ?>)">いいえ</button>
                        <a href="./delete_student_application2.php?id=<?= $result['id'] ?>&agent=<?= $result['agent_name'] ?>" style="text-decoration: none">
                          <!-- <button class="yes" onclick="deleteAgent()">はい -->
                          <button type="button" class="util_deletemodal_confirm" onclick="deleteFunction(<?= $result['id'] ?>)">はい</button>
                        </a>
                      </div>
                    </div>
                  </div>
                  <!-- ここから削除完了画面 -->
                  <div id="util_modalcont<?= $result['id'] ?>" class="util_deletemodal_container fixmodaltomiddle">
                    <p class="util_deletemodal_message">削除されました。</p>
                  </div>

                  <!-- ここからキープ用modal -->
                  <div id="util_keepmodal<?= $result['id'] ?>" class="util_deletemodal_container fixmodaltomiddle">
                    <div class="util_deletemodal">
                      <p class="util_deletemodal_text">削除せずにキープしますか？</p>
                      <div class="util_deletemodal_buttons">
                        <button type="button" class="util_deletemodal_back" onclick="closeFunction(<?= $result['id'] ?>)">いいえ</button>
                        <a href="./keep_student_application.php?id=<?= $result['id'] ?>&agent=<?= $result['agent_name'] ?>" style="text-decoration: none">
                          <!-- <button class="yes" onclick="deleteAgent()">はい -->
                          <button type="button" class="util_deletemodal_confirm" onclick="keepFunction(<?= $result['id'] ?>)">はい</button>
                        </a>
                      </div>
                    </div>
                  </div>
                  <!-- ここからキープ完了画面 -->
                  <div id="util_modalkeep<?= $result['id'] ?>" class="util_deletemodal_container fixmodaltomiddle">
                    <p class="util_deletemodal_message">削除されました。</p>
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
    </div>


    <?php require('../_footer.php'); ?>

</body>

<script>
  let bg = document.getElementById('modal_bg');
  //削除ボタンをクリックした時の処理
  let deleteModal = function(id) {
    let modal = document.getElementById(`util_deletemodal${id}`);
    function modalOpen() {
      modal.style.display = 'block';
      bg.style.display = 'block';
    };
    modalOpen();
  }

  let keepModal = function(id) {
    let modal = document.getElementById(`util_keepmodal${id}`);
    function modalOpen() {
      modal.style.display = 'block';
      bg.style.display = 'block';
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

  let keepFunction = function(id) {
    let modal = document.getElementById(`util_keepmodal${id}`);
    let modalComplete = document.getElementById(`util_modalkeep${id}`);

    function keepAgent() {
      modal.style.display = 'none';
      modalComplete.style.display = 'block';
    };
    keepAgent();
  }

  let closeFunction = function(id) {
    let modal = document.getElementById(`util_deletemodal${id}`);
    let modal2 = document.getElementById(`util_keepmodal${id}`);

    function modalClose() {
      modal.style.display = 'none';
      modal2.style.display = 'none';
      bg.style.display = 'none';
    };
    modalClose();
  }
</script>

</html>