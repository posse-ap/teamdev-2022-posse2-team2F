<?php
session_start();
include('../_header.php');
require('../dbconnect.php');

// ============================表示している月の取得============================
$month_id = filter_input(INPUT_GET, 'id');
if (!isset($month_id)) {
    $month_id = date('Ym'); //202205
}

$year = substr($month_id, 0,4);
$month = substr($month_id, 4,2);
$date_for_sql = $year . '-' . $month;

//1月と12月だけ特殊
if($month == 12){
    $next_month_id = $month_id + 89; //2022 12 → 2023 01 年度を変えて月を1にする +100-12+1
    $last_month_id = $month_id - 1;
}elseif($month == 1){
    $next_month_id = $month_id + 1;
    $last_month_id = $month_id - 89; //2022 01 → 2021 12 年度を変えて月を12にする -100-1+12
}else{
    $next_month_id = $month_id + 1;
    $last_month_id = $month_id - 1;
}

/*
月末締め → 4月のページを見ている場合、3/1 0:00 ~ 3/31 23:59 の申し込み件数を表示
（4月入ってから最初の営業日に送信）

表示しているページの月を取得 → showing_dateの中に入れる
// echo $showing_date;
*/

// 表示しているのはいつのページ？
$dt = \DateTimeImmutable::createFromFormat('Y-m', $date_for_sql);
// その月の月初めを取得
$first_day = $dt->modify('first day of this month')->format('Y-m-d');
// その月の月末を取得
$last_day = $dt->modify('first day of this month')->modify('last day of')->format('Y-m-d');

/*
//全ての請求件数 エージェントごと
$sql = "SELECT COUNT(*) FROM students WHERE agent = ?";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute(array($_SESSION['name']));
$all_students_info = $sql_prepare->fetchAll();
*/

// ============================SELECT文============================

// 合計件数 有効な件数;
$sql_valid = "SELECT count(name) FROM students_contact WHERE created_at BETWEEN ? AND ?";
$sql_valid_prepare = $db->prepare($sql_valid);
$sql_valid_prepare->execute(array($first_day, $last_day));
$all_valid_students = $sql_valid_prepare->fetchAll();

// 請求件数 idの最大値とってます（idは間の何件かが削除されてもそのまま変わらないイメージ）
$sql_all = "SELECT max(id) FROM students_contact WHERE created_at BETWEEN ? AND ?";
$sql_all_prepare = $db->prepare($sql_all);
$sql_all_prepare->execute(array($first_day, $last_day));
$all_students_number = $sql_all_prepare->fetchAll();

/*
    削除依頼件数 わからない！
*/

// 削除件数 idの最大値から、残った実際の数を引いています
$sql_deleted = "SELECT (max(id) - count(name)) FROM students_contact WHERE created_at BETWEEN ? AND ?";
$sql_deleted_prepare = $db->prepare($sql_deleted);
$sql_deleted_prepare->execute(array($first_day, $last_day));
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
            <?php //月遷移
            echo '<a href="invoice.php?id=' . $last_month_id . '">＜ </a>'; 
            
            echo $year . '年' . $month . '月';
            
            echo '<a href="invoice.php?id=' . $next_month_id . '">＞ </a>'; ?>
        </h3>

        <table class="invoice__table">
            <tr>
                <th colspan="2" class="invoice__table__title">
                    明細概観
                </th>
            </tr>

            <tr class="invoice__table__big-item">
                <th>
                    合計申し込み件数
                </th>
                <th class="invoice__table__number">
                    <?php print_r($all_valid_students[0][0]); ?>件
                </th>
            </tr>
            <tr>
                <td>
                    請求件数
                </td>
                <td class="invoice__table__small-item invoice__table__number">
                    <?php print_r($all_students_number[0][0]); ?>件
                </td>
            </tr>
            <tr>
                <td>
                    削除依頼件数
                </td>
                <td class="invoice__table__small-item invoice__table__number">
                    <?php
                    print_r("?");
                    ?>件
                </td>
            </tr>
            <tr>
                <td>
                    削除件数
                </td>
                <td class="invoice__table__small-item invoice__table__number">
                    <?php
                    print_r($deleted_students[0][0]);
                    ?>件
                </td>
            </tr>
            <tr class="invoice__table__big-item">
                <th>
                    ご請求金額合計
                </th>
                <th class = "invoice__table__number">
                    <?php print_r($all_valid_students[0][0]*5000); ?>円
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