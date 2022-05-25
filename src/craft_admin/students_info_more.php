<?php
session_start();
include('../_header.php');
require('../dbconnect.php');

// ============================どの学生の情報を表示するか？id取得============================
$application_id = filter_input(INPUT_GET, 'id');
if (!isset($application_id)) {
    // エラーページ？
}

// $sql = "SELECT students_contact.id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, students_agent.agent FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id WHERE students_agent.agent = ? AND students_agent.id = ?";
$sql = "SELECT students_agent.id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, students_agent.agent, students_agent.deleted_at, students_agent.status FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id WHERE students_agent.agent = ? AND students_agent.id = ?";

$sql_prepare = $db->prepare($sql);
$sql_prepare->execute(array($_SESSION['agent_name'], $application_id));
$all_students_info = $sql_prepare->fetchAll();
?>

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
            <div class="util_sidebar_button util_sidebar_button--selected">
                <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/students_info.php">学生申し込み一覧</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/craft_admin/inquiries.php">お問合せ管理</a>
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
          学生情報一覧
        </h2>
      </div>

        <?php foreach ($all_students_info as $student_info) : ?>
          <table class="table" border=1; style=border-collapse:collapse;>
            <tr>
              <th>申込ID</th>
              <td><?= $student_info['id'] ?></td>
            </tr>
            <tr>
              <th>名前</th>
              <td><?= $student_info['name'] ?></td>
            </tr>
            <tr>
              <th>メールアドレス</th>
              <td><?= $student_info['email'] ?></td>
            </tr>
            <tr>
              <th>電話番号</th>
              <td><?= $student_info['phone'] ?></td>
            </tr>
            <tr>
              <th>大学</th>
              <td><?= $student_info['university'] ?></td>
            </tr>
            <tr>
              <th>学部・学科</th>
              <td><?= $student_info['faculty'] ?></td>
            </tr>
            <tr>
              <th>住所</th>
              <td><?= $student_info['address'] ?></td>
            </tr>
            <tr>
              <th>卒業年</th>
              <td><?= $student_info['grad_year'] ?></td>
            </tr>
            <tr>
              <th>申し込みエージェント</th>
              <td><?= $student_info['id'] ?></td>
            </tr>
            <tr>
              <th>状態</th>
              <td><?= $student_info['status'] ?></td>
            </tr>
          </table>
        <?php endforeach; ?>

        <!-- <button type="button" class="tag_back"><a href='students_info.php'>戻る</a></button> -->
        <a class="tag_back" style="color: #000;" href='students_info.php'>戻る</a>
        <!-- <a href='students_info.php'>戻る</a>z -->
        <button onclick="tag_modalOpen()" type="button" class="tag_back">削除申請</button>

        </div>

        
    </div>

    <!-- ============================ここからモーダル============================ -->

    <!-- ここからtag_modal 〜コピペ〜 -->

    <div id="tag_modal" class="tag_modal_container">
        <form action="students_info_more.php" method="POST">
            <div class="tag_modal">
                <?php
                // foreach ($split_ids as $index => $tag_id) {
                // $sql = "INSERT INTO agent_tag_options(tag_option_id, agent_id) VALUES (?, ?)";
                // $stmt->execute(array($tag_id, $id));
                foreach ($all_students_info as $student_info) {
                    $sql = "
                    START TRANSACTION;

                    INSERT INTO delete_student_application(application_id, agent_name) VALUES (?, ?);

                    UPDATE students_agent SET status = ? WHERE id = ?;

                    COMMIT;
                    ";
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array($application_id, $_SESSION['agent_name'], '削除申請中', $application_id));
                    echo "<p>";
                    echo $student_info['name'];
                    echo "さんの情報の削除依頼を実行しますか？</p>";
                }
                ?>
                <div class="tag_modal_buttons">
                    <button onclick="tag_modalClose()" type="button" class="tag_modalClose">戻る</button>
                    <button onclick="tag_modalClose()" type="button" id="confirm_button" class="tag_decision" type="submit">決定</button>
                </div>
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
</div>