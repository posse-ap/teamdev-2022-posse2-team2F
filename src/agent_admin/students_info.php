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
            <div class="util_sidebar_button util_sidebar_button--selected">
                <a class="util_sidebar_link  util_sidebar_link--selected" href="/agent_admin/students_info.php">学生申し込み一覧</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/agent_admin/edit_info.php">担当者情報編集</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="">お問合せ</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/agent_admin/invoice.php">請求金額確認</a>
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

                    <!-- <h2>検索結果</h2> -->

                    <!-- 並び替え方法選択 -->
                    <select name="sort">
                        <?php
                        // POST を受け取る変数を初期化
                        $sort = '';

                        // セレクトボックスの値を格納する配列
                        $orders_list = array(
                            "電話番号の小さい順",
                            "電話番号の大きい順",
                            "名前順",
                            "申込順"
                        );

                        // 戻ってきた場合
                        if (isset($_POST['sort'])) {
                            $sort = $_POST['sort'];
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
                    <input type="submit" name="sort_button" value="並び替える">


                    <!-- ここから並び替えの分岐 -->
                    <?php
                    if (isset($_POST['sort_button'])) {
                        if ($_POST['sort'] == '電話番号の小さい順') {
                            $sort_sql = " ORDER BY phone ASC";
                        } elseif ($_POST['sort'] == '電話番号の大きい順') {
                            $sort_sql = " ORDER BY phone DESC";
                        } elseif ($_POST['sort'] == '名前順') {
                            $sort_sql = " ORDER BY name ASC";
                        } elseif ($_POST['sort'] == '申込順') {
                            $sort_sql = " ORDER BY created_at ASC";
                        } else {
                            $sort_sql = " ORDER BY phone ASC";
                        }
                        $_SESSION['sort'] = $sort_sql;
                        // $sql = "SELECT * FROM students_contact WHERE agent = ? " . $_SESSION['sort'];
                        $sql = "SELECT students_agent.id AS application_id, students_contact.id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, students_agent.agent, students_agent.deleted_at FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id WHERE students_agent.deleted_at IS NULL AND students_agent.agent = ?" . $_SESSION['sort'];
                    } else {
                        // $sql = "SELECT * FROM students_contact WHERE agent = ? ORDER BY phone ASC";
                        $sql = "SELECT students_agent.id AS application_id, students_contact.id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, students_agent.agent FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id WHERE students_agent.deleted_at IS NULL AND students_agent.agent = ? ORDER BY phone ASC";
                    }

                    // print_r($sql);
                    $sql_prepare = $db->prepare($sql);
                    $sql_prepare->execute(array($_SESSION['agent_name']));
                    $all_students_info = $sql_prepare->fetchAll();

                    if (!$all_students_info) {
                        // echo $all_students_info->error;
                        exit();
                    }
                    ?>

                    <!-- ここからmodal -->
                    <div id="util_delete_application<?= $category['id'] ?>" class="util_modalcont">
                        <div class="util_delete_application util_deletemodal">

                            <p class="util_delete_application_alert util_deletemodal">学生情報削除申請</p>
                            <div class="util_deletebuttons util_deletemodal">
                                <button class="util_deletebuttons_item util_deletebuttons_item--no" onclick="closeFunction(<?= $category['id'] ?>)">キャンセル</button>
                                <a href="./delete_tag.php?id=<?= $category['id'] ?>" style="text-decoration: none">
                                    <!-- <button class="yes" onclick="deleteAgent()">はい -->
                                    <button class="util_deletebuttons_item util_deletebuttons_item--yes" onclick="deleteFunction(<?= $category['id'] ?>)">送信

                                            <path d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z" />
                                        </svg>
                                    </button>
                                </a>
                            </div>

                        </div>
                    </div>


                    <!-- 並び替え結果 -->
                    <div class="table_container">
                        <table border=1; style=border-collapse:collapse;>
                            <tr>
                                <th></th>

                                <th>
                                    名前
                                </th>

                                <th>
                                    メールアドレス
                                </th>

                                <th>
                                    電話番号
                                </th>

                                <th>
                                    大学
                                </th>

                                <th>
                                    学部・学科
                                </th>

                                <th>
                                    住所
                                </th>

                                <th>
                                    卒業年
                                </th>

                                <th>
                                    申し込みエージェント
                                </th>

                                <th>
                                    〇〇
                                </th>
                            </tr>

                            <?php
                            foreach ($all_students_info as $student_info) {
                                echo "<tr>";

                                echo "<th>";
                                echo $student_info['id'];
                                echo "</th>";

                                echo "<th>";
                                echo $student_info['name'];
                                echo "</th>";

                                echo "<th>";
                                echo $student_info['email'];
                                echo "</th>";

                                echo "<th>";
                                echo $student_info['phone'];
                                echo "</th>";

                                echo "<th>";
                                echo $student_info['university'];
                                echo "</th>";

                                echo "<th>";
                                echo $student_info['faculty'];
                                echo "</th>";

                                echo "<th>";
                                echo $student_info['address'];
                                echo "</th>";

                                echo "<th>";
                                echo $student_info['grad_year'];
                                echo "</th>";

                                echo "<th>";
                                echo $student_info['agent'];
                                echo "</th>";

                                echo "<th><a class='util_action_button util_action_button--list' href='students_info_more.php?id=" ;
                                echo $student_info['application_id'];
                                echo "'> 詳細";
                                echo "</a></th>";

                                echo "</tr>";
                            };
                            echo "</table>";

                            echo "</div>";

                            /*
                            以下、コピペでわからなかったところ
                            結果セットを解放？
                            $all_students_info->free();

                            データベース切断？
                            $mysqli->close();
                            $i = 0;
                            foreach ($rows as $row) {

                            データの破棄？
                            if (isset($_SESSION['sort'])) {
                                session_destroy();
                                unset($_SESSION['sort']);
                            }
                            */
                            ?>
                </form>

            </div>
        </div>
    </div>



</body>


<?php require('../_footer.php'); ?>

</html>