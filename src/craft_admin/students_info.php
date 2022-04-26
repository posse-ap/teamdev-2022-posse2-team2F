<?php
session_start();
// とりあえずagent_adminのやつコピーしてきた
include('../_header.php');
require('../dbconnect.php');
?>

<form method="POST" action="students_info.php">

    <h2>検索結果</h2>

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
            );

            // 戻ってきた場合
            if(isset($_POST['sort'])){
            $sort = $_POST['sort'];
            }

            foreach($orders_list as $value){
                if($value === $sort){
                        // ① POST データが存在する場合はこちらの分岐に入る
                        echo "<option value='$value' selected>".$value."</option>";
                }else{
                        // ② POST データが存在しない場合はこちらの分岐に入る
                        echo "<option value='$value'>".$value."</option>";
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
            $sort_sql = " ORDER BY name DESC";
        } else {
            $sort_sql = " ORDER BY phone ASC";
        }
        $_SESSION['sort'] = $sort_sql;
        $sql = "SELECT * FROM students" . $_SESSION['sort'];

        /*
        TODO
        ここからエージェントごとに出す情報を分ける
        */
        // if (isset($_POST['sort_button']))
        
    }else{
        $sql = "SELECT * FROM students ORDER BY phone ASC";
    }

    // print_r($sql);
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