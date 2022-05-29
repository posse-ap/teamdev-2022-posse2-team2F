<?php
session_start();


// ログインしていないままアクセスしようとしている場合エラーページに飛ばす
if (!isset($_SESSION['id'])) {
    header('Location: ./login/login_error.php');
}

include('../_header.php');
require('../dbconnect.php');


// ============================どの学生の情報を表示するか？id取得============================
$application_id = filter_input(INPUT_GET, 'id');
if (!isset($application_id)) {
    // エラーページ？
}

$sql = "SELECT students_contact.id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, students_agent.agent_id, students_agent.status FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id WHERE students_agent.agent_id = ? AND students_agent.id = ?";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute(array($_SESSION['agent_id'], $application_id));
// $all_students_info = $sql_prepare->fetchAll();
$student_info = $sql_prepare->fetch();

// if (isset($_POST['delete_request'])) {

//     $sql = "START TRANSACTION;

//             INSERT INTO delete_student_application(application_id, agent_name) VALUES (?, ?);

//             UPDATE students_agent SET status = ? WHERE id = ?;

//             COMMIT;";
//     $stmt = $db->prepare($sql);
//     $stmt->execute(array($application_id, $student_info['agent'], '削除申請中', $application_id));

//     header('Location: students_info.php');
// }
?>
<div class="util_logout">
    <p class="util_logout_email"><?= $_SESSION['login_email'] ?></p>
    <a href="./login/logout.php">
    ログアウト
    <i class="fas fa-sign-out-alt"></i>
    </a>
</div>

<div class="util_container">
    <div class="util_sidebar">
        <div class="util_sidebar_button util_sidebar_button--selected">
            <a class="util_sidebar_link  util_sidebar_link--selected" href="/agent_admin/students_info.php">学生申し込み一覧</a>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/agent_admin/edit_info.php">担当者情報管理</a>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/agent_admin/inquiries.php">お問合せ</a>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/agent_admin/invoice.php">請求金額確認</a>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
        </div>
    </div>

    <div class="util_content">
        <div class="util_title">
            <h2 class="util_title--text">
                学生情報詳細
            </h2>
        </div>
        <div class="moreinfo">
            <table class="moreinfo_table" border=1; style=border-collapse:collapse;>
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
                    <td><?= $student_info['agent'] ?></td>
                </tr>
                <tr>
                    <th>状態</th>
                    <td><?= $student_info['status'] ?></td>
                </tr>
            </table>
            <div class="moreinfo_buttons">
                <a href='students_info.php' class="moreinfo_buttons--back">戻る</a>
                <button onclick="modalOpen()" type="button" class="moreinfo_buttons--delete">削除申請</button>
            </div>
        </div>

        <!-- <button type="button" class="tag_back"><a href='students_info.php'>戻る</a></button> -->
        <!-- <a href='students_info.php'>戻る</a>z -->
        <!-- <button onclick="tag_modalOpen()" type="button" class="tag_back">削除申請</button> -->
    </div>
    <!-- ============================ここからモーダル============================ -->
    <div id="moreinfo_modal_bg" class="util_deletemodal_bg">
        <div id="moreinfo_modal" class="util_deletemodal_container">
            <form action="delete_student_application.php?id=<?= $application_id ?>&agent=<?= $student_info['agent'] ?>" method="POST">
                <div class="util_deletemodal">
                    <p class="util_deletemodal_text">「<?= $student_info['name'] ?>」さんの情報の削除申請を実行しますか？</p>
                    <div class="util_deletemodal_buttons">
                        <button onclick="modalClose()" type="button" class="util_deletemodal_back">戻る</button>
                        <button onclick="modalDelete()" type="submit" name="delete_request" id="confirm_button" class="util_deletemodal_confirm">決定</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- ここから削除完了画面 -->
        <div id="moreinfo_modaldone" class="util_deletemodal_container">
            <div class="util_deletemodal">
                <p class="util_deletemodal_message">削除申請が完了しました。</p>
            </div>
        </div>
    </div>


    <?php require('../_footer.php'); ?>

    <script>
        const modal = document.getElementById('moreinfo_modal');
        const modaldone = document.getElementById('moreinfo_modaldone')
        const bg = document.getElementById('moreinfo_modal_bg');

        function modalOpen() {
            modal.style.display = 'block';
            bg.style.display = 'block';
        }

        function modalClose() {
            modal.style.display = 'none';
            bg.style.display = 'none';
        }

        function modalDelete() {
            modal.style.display = 'none';
            modaldone.style.display = 'block';
        }


        window.onclick = function(event) {
            if (event.target == bg) {
                modal.style.display = "none";
                bg.style.display = 'none';
            }
        }
    </script>
</div>