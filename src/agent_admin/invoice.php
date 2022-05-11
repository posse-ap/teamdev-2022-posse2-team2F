<?php
session_start();
include('../_header.php');
require('../dbconnect.php');

$month_id = filter_input(INPUT_GET, 'id');
if (!isset($month_id)) {
    $month_id = date('Ym');
    echo $month_id;
}

/*
//全ての請求件数 エージェントごと
$sql = "SELECT COUNT(*) FROM students WHERE agent = ?";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute(array($_SESSION['name']));
$all_students_info = $sql_prepare->fetchAll();
*/

/*
月末締め → 4月のページを見ている場合、3/1 0:00 ~ 3/31 23:59 の申し込み件数を表示
（4月入ってから最初の営業日に送信）
TODO
表示しているページの月を取得 → showing_dateの中に入れる
*/

// // 表示しているページはいつか？
$showing_date = date('Y-m-d'); //ここをパラメータに変更する
// echo $showing_date;
$dt = \DateTimeImmutable::createFromFormat('Y-m-d', $showing_date);

// その月の月初めを取得
$first_day = $dt->modify('first day of this month')->format('Y-m-d');
// その月の月末を取得
$last_day = $dt->modify('first day of this month')->modify('last day of')->format('Y-m-d');



// 合計件数
$sql = "SELECT count(name) FROM students_contact WHERE created_at BETWEEN ? AND ?";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute(array($first_day, $last_day));
$all_students_info = $sql_prepare->fetchAll();


// *5000円
$sql_valid = "SELECT count(name) * 5000 FROM students_contact";
$sql_valid_prepare = $db->prepare($sql_valid);
$sql_valid_prepare->execute();
$all_valid_students = $sql_valid_prepare->fetchAll();

/*
// 削除依頼件数 わからない！
$sql = "SELECT count(name) FROM students";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute();
$all_students_info = $sql_prepare->fetchAll();
// 削除依頼件数： "わかりません > . <" 件
*/

// 削除件数
$sql_deleted = "SELECT (max(id) - count(name)) FROM students_contact";
$sql_deleted_prepare = $db->prepare($sql_deleted);
$sql_deleted_prepare->execute();
$deleted_students = $sql_deleted_prepare->fetchAll();
?>


<div class="util_container">
    <div class="util_sidebar">
        <div class="util_sidebar_button util_sidebar_button-selected">
            <a class="util_sidebar_link util_sidebar_link-selected" href="/craft_admin/home.php">エージェント管理</a>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/craft_admin/add_agent.php">エージェント追加</a>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/craft_admin/tag.php">タグ編集・追加</a>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
        </div>
    </div>


    <div class="util_content">

        <h2>合計請求金額確認</h2>

        <h3>
            <?= '<a href="invoice.php?id=' . $month_id - 1 . '">＜ </a>' ?>
            <?php
            /*
        TODO
        ここで前の月、次の月に遷移できるようにしたい。
        リンクにしようかな
        */

            // echo date('Y年m月');
            // echo $month_id;
            echo substr($month_id, 0,4);
            echo '年';
            echo substr($month_id, 4,2);
            echo '月';

            ?>
            <?= '<a href="invoice.php?id=' . $month_id + 1 . '">＞ </a>' ?>
        </h3>

        <table class="invoice__table">
            <tr>
                <th colspan="2" class="invoice__table__title">
                    明細概観
                </th>
            </tr>

            <tr class="invoice__table__big-item">
                <th>
                    〇〇年〇〇月合計申し込み件数
                </th>
                <th>
                    <?php print_r($all_students_info[0][0]); ?>件
                </th>
            </tr>
            <tr>
                <td>
                    請求件数
                </td>
                <td class="invoice__table__small-item">
                    〇〇件
                </td>
            </tr>
            <tr>
                <td>
                    削除依頼件数
                </td>
                <td class="invoice__table__small-item">
                    <?php
                    print_r($all_students_info[0][0]);
                    ?>件
                </td>
            </tr>
            <tr>
                <td>
                    削除件数
                </td>
                <td class="invoice__table__small-item">
                    <?php
                    print_r($deleted_students[0][0]);
                    ?>件
                </td>
            </tr>
            <tr class="invoice__table__big-item">
                <th>
                    〇〇年〇〇月ご請求金額合計
                </th>
                <th>
                    <?php print_r($all_valid_students[0][0]); ?>円
                </th>
            </tr>
        </table>
        <div class="invoice__buttons__section">
            <div class="login_button">
                <a href="">請求書発行</a>
            </div>
        </div>
    </div>
</div>