<?php
session_start();

// ログインしていないままアクセスしようとしている場合エラーページに飛ばす
if (!isset($_SESSION['id'])) {
    header('Location: ./login/login_error.php');
}



include('../_header.php');
require('../dbconnect.php');



// ============================表示しているページの月を取得============================
$month_id = filter_input(INPUT_GET, 'id');
if (!isset($month_id)) {
    $month_id = date('Ym'); //202205
}

$year = substr($month_id, 0, 4);
$month = substr($month_id, 4, 2);
$date_for_sql = $year . '-' . $month;

/*
月末締め → 4月のページを見ている場合、3/1 0:00 ~ 3/31 23:59 の申し込み件数を表示
（4月入ってから最初の営業日に送信）
1月と12月だけ特殊なのでif文で分岐
*/

if ($month == 12) {
    $next_month_id = $month_id + 89; //2022 12 → 2023 01 年度を変えて月を1にする +100-12+1
    $last_month_id = $month_id - 1;
} elseif ($month == 1) {
    $next_month_id = $month_id + 1;
    $last_month_id = $month_id - 89; //2022 01 → 2021 12 年度を変えて月を12にする -100-1+12
} else {
    $next_month_id = $month_id + 1;
    $last_month_id = $month_id - 1;
}


// 表示しているのはいつのページ？
$dt = \DateTimeImmutable::createFromFormat('Y-m', $date_for_sql);
// その月の月初めを取得
$first_day = $dt->modify('first day of this month')->format('Y-m-d');
// その月の月末を取得
$last_day = $dt->modify('first day of this month')->modify('last day of')->format('Y-m-d');

/*
//エージェントごとの場合WHERE以下追記する
$sql = "SELECT COUNT(*) FROM students WHERE agent = ?";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute(array($_SESSION['name']));
$all_students_info = $sql_prepare->fetchAll();
*/

// ============================SELECT文============================

// 合計件数 有効な件数;
// $sql_valid = "SELECT count(students_agent.id) FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id WHERE created_at BETWEEN ? AND ?";
$sql_valid = "SELECT count(*) FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id WHERE deleted_at IS NULL AND created_at BETWEEN ? AND ?";
$sql_valid_prepare = $db->prepare($sql_valid);
$sql_valid_prepare->execute(array($first_day, $last_day));
$all_valid_students = $sql_valid_prepare->fetchAll();

// 請求件数 idの最大値とってます（idは間の何件かが削除されてもそのまま変わらないイメージ）
// $sql_all = "SELECT max(students_agent.id) FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id WHERE created_at BETWEEN ? AND ?";
$sql_all = "SELECT count(*) FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id WHERE created_at BETWEEN ? AND ?";
$sql_all_prepare = $db->prepare($sql_all);
$sql_all_prepare->execute(array($first_day, $last_day));
$all_students_number = $sql_all_prepare->fetchAll();

// 削除依頼件数 
$sql_applydelete = "SELECT count(*) FROM students_agent JOIN delete_student_application ON delete_student_application.application_id = students_agent.id JOIN students_contact ON students_contact.id = students_agent.student_id WHERE created_at BETWEEN ? AND ?";
$sql_applydelete_prepare = $db->prepare($sql_applydelete);
$sql_applydelete_prepare->execute(array($first_day, $last_day));
$all_applydelete_student = $sql_applydelete_prepare->fetch();


// 削除件数 idの最大値から、残った実際の数を引いています
$sql_deleted =
    // "SELECT  (SELECT COUNT(id) FROM students_contact WHERE created_at BETWEEN ? AND ?)  - (SELECT COUNT(id) FROM students_contact_delete WHERE created_at BETWEEN ? AND ?)";
    "SELECT count(*) FROM students_contact JOIN students_agent ON students_contact.id = students_agent.student_id WHERE deleted_at IS NOT NULL AND created_at BETWEEN ? AND ?";
$sql_deleted_prepare = $db->prepare($sql_deleted);
$sql_deleted_prepare->execute(array($first_day, $last_day));
$deleted_students = $sql_deleted_prepare->fetchAll();
?>

<div class="util_logout">
    <p class="util_logout_email"><?= $_SESSION['email'] ?></p>
    <a href="./login/logout.php">
        ログアウト
        <i class="fas fa-sign-out-alt"></i>
    </a>
</div>
<div class="util_container">
    <div class="util_sidebar no-print-area">
        <div class="util_sidebar_button">
            <a class="util_sidebar_link " href="/craft_admin/home.php">エージェント管理</a>
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
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/craft_admin/students_info.php">学生申し込み一覧</a>
            <i class="fas fa-angle-right"></i>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/craft_admin/contact_management.php">お問合せ管理</a>
            <i class="fas fa-angle-right"></i>
        </div>
        <div class="util_sidebar_button util_sidebar_button--selected">
            <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/invoice.php">合計請求金額確認</a>
            <i class="fas fa-angle-right"></i>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
            <i class="fas fa-angle-right"></i>
        </div>
    </div>

    <div class="util_content">
        <div class="util_title no-print-area">
            <h2 class="util_title--text no-print-area">
                合計請求金額確認
            </h2>
        </div>
        <!-- <h2 class="no-print-area">合計請求金額確認</h2> -->
        <h3 class="no-print-area invoice_title">
            <?php //月遷移
            echo '<a href="invoice.php?id=' . $last_month_id . '">＜ </a>';
            echo $year . '年' . $month . '月';
            echo '<a href="invoice.php?id=' . $next_month_id . '">＞ </a>'; ?>
        </h3>
        <!-- ＝＝＝＝＝＝＝＝＝ここから印刷用＝＝＝＝＝＝＝＝＝ -->
        <div class="print-only-area">
            <section class="print-only-area__head">
                <h1>請求書</h1>
                <div>
                    <img class="print-only-area__logo" src="/img/boozer_logo.png" alt="boozerのロゴ">
                    <p>
                        <?= date('Y年m月d日'); ?>
                    </p>
                </div>
            </section>
            <p>
                <!-- 期限 15日締めにしとく-->
                <?= '期限：   ' . $year . '年' . $month + 1 . '月15日' ?>
            </p>
            <section class="print-only-area__information">
                <div>
                    <p>boozerです！ <br> 住所！電話番号！メールアドレス！！</p>
                </div>
                <div>
                    <p>請求先 <br> agentです！メールアドレス！</p>
                </div>
            </section>
            <section class="print-only-area__deadline">
                <p>
                    <?php print_r($all_valid_students[0][0] * 5000); ?>円 の支払い期日は
                    <?= $year . '年' . $month + 1 . '月15日' ?>です
                </p>
            </section>
        </div>
        <!-- ＝＝＝＝＝＝＝＝＝ここまで印刷用＝＝＝＝＝＝＝＝＝ -->
        <table class="invoice__table">
            <tr>
                <th colspan="2" class="invoice__table__title no-print-area">
                    明細概観
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
                    print_r("$all_applydelete_student[0]");
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
                    合計申し込み件数
                </th>
                <th class="invoice__table__number">
                    <?php print_r($all_valid_students[0][0]); ?>件
                </th>
            </tr>
            <tr class="invoice__table__big-item">
                <th>
                    ご請求金額合計
                </th>
                <th class="invoice__table__number">
                    <?php print_r($all_valid_students[0][0] * 5000); ?>円
                </th>
            </tr>
        </table>
        <div class="invoice__buttons__section no-print-area">
            <input class="invoice_button" type="button" value="請求書発行" onclick="window.print();" />
        </div>
    </div>
</div>


<?php require('../_footer.php'); ?>