<?php
session_start();
include('../_header.php');
require('../dbconnect.php');
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
                <a class="util_sidebar_link" href="/craft_admin/contact_management.php">お問合せ管理</a>
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
            <div class="info">
                <form method="POST" action="students_info.php">
                    <!-- 並び替え結果 -->
                    <div class="table_cont">

                        <div class="info_control">

                            <!-- 並び替え方法選択 -->
                            <select class="info_select" name="sort">
                                <?php
                                // POST を受け取る変数を初期化
                                $sort = '';

                                // セレクトボックスの値を格納する配列
                                $orders_list = array(
                                    "申込日時（古い順）",
                                    "申込日時（新しい順）"
                                );

                                // 戻ってきた場合
                                if (isset($_POST['sort'])) {
                                    $sort = $_POST['sort'];
                                } else if (isset($_SESSION['sort_select'])) {
                                    $sort = $_SESSION['sort_select'];
                                }

                                foreach ($orders_list as $value) {
                                    if ($value === $sort) {
                                        // ① POST データが存在する場合はこちらの分岐に入る
                                        echo "<option value='$value' selected>" . $value . "</option>";
                                    } else {
                                        // ② POST データが存在しない場合はこちらの分岐に入る
                                        echo "<option value='$value'>" . $value . "</option>";
                                    }
                                }
                                ?>
                            </select>

                            <!-- 並び替えボタン -->
                            <input class="info_button" type="submit" name="sort_button" value="並び替える">


                        </div>


                        <!-- ここから並び替えの分岐 -->
                        <?php
                        if ($sort == "申込日時（古い順）") {
                            $sort_sql = " ORDER BY students_contact.created_at ASC";
                            $_SESSION['sort_select'] = "申込日時（古い順）";
                        } elseif ($sort == "申込日時（新しい順）") {
                            $sort_sql = " ORDER BY students_contact.created_at DESC";
                            $_SESSION['sort_select'] = "申込日時（新しい順）";
                        } else {
                            $sort_sql = " ";
                        }
                        $_SESSION['sort'] = $sort_sql;
                        $sql = "SELECT students_agent.id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, students_agent.agent, students_agent.deleted_at, students_agent.status FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id" . $_SESSION['sort'];

                        // print_r($sql);
                        $sql_prepare = $db->prepare($sql);
                        $sql_prepare->execute();
                        $all_students_info = $sql_prepare->fetchAll();

                        if (!$all_students_info) {
                            // echo $all_students_info->error;
                            exit();
                        }
                        ?>

                        <div class="cont_for_scroll">
                            <table class="table" border=1; style=border-collapse:collapse;>
                                <tr>
                                    <th>申込ID</th>
                                    <th>名前</th>
                                    <th>メールアドレス</th>
                                    <th>大学</th>
                                    <th>学部・学科</th>
                                    <th>卒業年</th>
                                    <th>エージェント</th>
                                    <th>状態</th>
                                    <th>操作</th>
                                </tr>


                                <?php
                                foreach ($all_students_info as $student_info) { ?>

                                <?

                                    echo "<tr>";

                                    echo "<th name='id'>";
                                    echo $student_info['id'];
                                    echo "</th>";

                                    echo "<th name='name'>";
                                    echo $student_info['name'];
                                    echo "</th>";

                                    echo "<th name='email'>";
                                    echo $student_info['email'];
                                    echo "</th>";

                                    // echo "<th>";
                                    // echo $student_info['phone'];
                                    // echo "</th>";

                                    echo "<th>";
                                    echo $student_info['university'];
                                    echo "</th>";

                                    echo "<th>";
                                    echo $student_info['faculty'];
                                    echo "</th>";

                                    // echo "<th>";
                                    // echo $student_info['address'];
                                    // echo "</th>";

                                    echo "<th>";
                                    echo $student_info['grad_year'];
                                    echo "</th>";

                                    echo "<th>";
                                    echo $student_info['agent'];
                                    echo "</th>";

                                    echo "<th>";
                                    echo $student_info['status'];
                                    echo "</th>";

                                    echo "<th>";

                                    echo "<a class='util_action_button util_action_button--list center_list' href='students_info_more.php?id=";
                                    echo $student_info['id'];
                                    echo "'> 詳細";
                                    echo "</a>";


                                    echo "</th>";

                                    echo "</tr>";
                                };
                                echo "</table>";

                                echo "</div>";

                                echo "</div>";


                                ?>
                        </div>
                </form>
            </div>
        </div>
    </div>


    <?php require('../_footer.php'); ?>
</body>