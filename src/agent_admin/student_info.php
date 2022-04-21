<?php
require('../dbconnect.php');

// 今日の勉強時間
// $day = 'SELECT * FROM students WHERE `id` = ?';
$all_info = 'SELECT * FROM students  LIMIT 100';
$info_prepare = $db->prepare($all_info);
$info_prepare->execute();
$all_students_info = $info_prepare->fetchAll();

?>

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
    echo "</div>";
    ?>