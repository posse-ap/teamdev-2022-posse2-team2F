<?php
session_start();
include('../_header.php');
require('../dbconnect.php');
?>

<form method="POST" action="students_info.php">

    <h2>検索結果</h2>

    <!-- 並び替え方法選択 -->
    <select name="sort">
        <option value="up">電話番号の小さい順</option>
        <option value="down">電話番号の大きい順</option>
        <option value="name">名前順</option>
    </select>

    <!-- 並び替えボタン -->
    <input type="submit" name="sort_button" value="並び替える">


    <!-- ここから並び替えの分岐 -->
    <?php
    if (isset($_POST['sort_button'])) {
        if ($_POST['sort'] == 'up') {
            $sort = " ORDER BY phone ASC";
        } elseif ($_POST['sort'] == 'down') {
            $sort = " ORDER BY phone DESC";
        } else {
            $sort = " ORDER BY name ASC";
        }
        $_SESSION['sort'] = $sort;

        $sql = "SELECT * FROM students";
        $sql .= $_SESSION['sort'];
        /*
        TODO
        ここからエージェントごとに出す情報を分ける
        */
        // if (isset($_POST['sort_button']))
    }else{
        $sql = "SELECT * FROM students ORDER BY phone ASC";
    }

    print_r($sql);
    $sql_prepare = $db->prepare($sql);
    $sql_prepare->execute();
    $all_students_info = $sql_prepare->fetchAll();

    if (!$all_students_info) {
        echo $all_students_info->error;
        exit();
    }
    ?>

    <!-- 並び替え結果 -->
    <h2>学生情報一覧</h2>
    <div class="table_container">
        <table border=1; style=border-collapse:collapse;>
            <tr>
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
            </tr>

            <?php
            foreach ($all_students_info as $student_info) {
                echo "<tr>";

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
            */
            ?>


            <?php
            if (isset($_SESSION['sort'])) {
                session_destroy();
                unset($_SESSION['sort']);
            }
            ?>
            </form>