<?php
session_start();
include('../_header.php');
require('../dbconnect.php');

// ============================どの学生の情報を表示するか？id取得============================
$student_id = filter_input(INPUT_GET, 'id');
if (!isset($student_id)) {
    // エラーページ？
}

$sql = "SELECT students_contact.id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, students_agent.agent FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id WHERE students_agent.agent = ? AND students_contact.id = ?";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute(array($_SESSION['agent_name'], $student_id));
$all_students_info = $sql_prepare->fetchAll();
?>

<div class="util_container">
    <div class="util_sidebar">
        <div class="util_sidebar_button util_sidebar_button--selected">
            <a class="util_sidebar_link  util_sidebar_link--selected" href="/agent_admin/students_info.php">学生申し込み一覧</a>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/agent_admin/edit_info.php">担当者情報編集</a>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="">お問合せ</a>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/agent_admin/invoice.php">請求金額確認</a>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
        </div>
    </div>

    <div class="util_content">
        <h2 class="no-print-area">学生情報詳細</h2>

        <?php foreach ($all_students_info as $student_info) : ?>
            <p>名前</p>
            <p>
                <?= $student_info['name']; ?>
            </p>
            <p>メールアドレス</p>
            <p>
                <?= $student_info['email']; ?>
            </p>
            <p>電話番号</p>
            <p>
                <?= $student_info['id']; ?>
            </p>
            <p>大学名</p>
            <p>
                <?= $student_info['university']; ?>
            </p>
            <p>学部学科</p>
            <p>
                <?= $student_info['faculty']; ?>
            </p>
            <p>住所</p>
            <p>
                <?= $student_info['address']; ?>
            </p>
            <p>卒業年</p>
            <p>
                <?= $student_info['grad_year']; ?>
            </p>

            <?php  $student_id = intval($student_info['id']); ?>
            <?= $student_id ?>

        <?php endforeach; ?>

        <button type="button" class="tag_modalClose"><a href='students_info.php'>戻る</a></button>
        <!-- <a href='students_info.php'>戻る</a>z -->
        <button onclick="tag_modalOpen()" type="button" class="tag_modalClose">削除申請</button>
    </div>

    <!-- ============================ここからモーダル============================ -->

    <!-- ここからtag_modal 〜コピペ〜 -->
    <script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js?ver=1.12.2'></script>

    <!-- <script>
        $(function() {
            $('#confirm_button').on('click', function() {
                // モーダルで選択した内容を反映させる処理
                let string = [];
                let id = [];

                $("input[name=tags]:checked").each(function() {
                    string.push($(this).val());
                    // 選択した値の id を保存する処理
                    id.push($(this).attr('id'));
                    $('#showid').val(id);
                });
                $("#input").val(string.join('、'));
            });
        });
    </script> -->

    <div id="tag_modal" class="tag_modal">
        <form action="students_info_more.php" method="POST">
            <div class="tag_modal_container">
                    <?php
                    // foreach ($split_ids as $index => $tag_id) {
                        // $sql = "INSERT INTO agent_tag_options(tag_option_id, agent_id) VALUES (?, ?)";
                        // $stmt->execute(array($tag_id, $id));
                        foreach ($all_students_info as $student_info) {
                        $sql = "INSERT INTO delete_student_application(student_id, name, agent_id) VALUES (?, ?, ?);";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(array($student_id, $student_info['name'], $_SESSION['agent_name']));
                        echo "<p>";
                        echo $student_info['name'];
                        echo "さんの情報の削除依頼を実行しますか？</p>" ;
                    }
                    ?>
                <div class="tag_modal_container--buttons">
                    <button onclick="tag_modalClose()" type="button" class="tag_modalClose">戻る</button>
                    <button onclick="tag_modalClose()" type="button" id="confirm_button" class="tag_decision" type="submit">決定</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const tag_modal = document.getElementById('tag_modal');

    function tag_modalOpen() {
        tag_modal.style.display = 'block';
    }

    function tag_modalClose() {
        tag_modal.style.display = 'none';
    }
</script>