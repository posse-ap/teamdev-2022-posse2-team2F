<?php
session_start();
include('../_header.php');
require('../dbconnect.php');

/*
月末締め → 4月のページを見ている場合、3/1 0:00 ~ 3/31 23:59 の申し込み件数を表示
（4月入ってから最初の営業日に送信）
TODO
表示しているページの月を取得 → showing_dateの中に入れる
*/

// 表示しているページはいつか？
$showing_date = date('Y-m-d');
echo $showing_date;
$dt = \DateTimeImmutable::createFromFormat('Y-m-d', $showing_date);

// その月の月初めを取得
$first_day = $dt->modify('first day of this month')->format('Y-m-d');
// その月の月末を取得
$last_day = $dt->modify('first day of this month')->modify('last day of')->format('Y-m-d');

// 合計件数
$sql = "SELECT count(name) FROM students WHERE date BETWEEN ? AND ?";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute(array($first_day, $last_day));
$all_students_info = $sql_prepare->fetchAll();

// *5000円
$sql_valid = "SELECT count(name) * 5000 FROM students";
$sql_valid_prepare = $db->prepare($sql_valid);
$sql_valid_prepare->execute();
$all_valid_students = $sql_valid_prepare->fetchAll();
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

        <div class="kari-upper__content">
            <div class="kari-upper__content__date">
                <h3>
                    <?php
/*
TODO
ここで前の月、次の月に遷移できるようにしたい。
リンクにしようかな
echo date('Y年m月'); 
*/
                    ?>
                </h3>
            </div>

            <div class="kari-upper__content__number">
                <div class="kari-upper__content__number-box">
                    <p class="kari-upper__content__number-title">合計申し込み件数</p>
                    <p class="kari-upper__content__number-num">
                        <?php print_r($all_students_info[0][0]); ?>
                        <span class="kari-upper__content__number-unit">
                            件
                        </span>
                    </p>
                </div>

                <div class="kari-upper__content__number-box">
                    <p class="kari-upper__content__number-title"> ご請求金額合計</p>
                    <p class="kari-upper__content__number-num">
                        <?php print_r($all_valid_students[0][0]); ?>
                        <span class="kari-upper__content__number-unit">
                            円
                        </span>
                    </p>
                </div>
            </div>


        </div>
        <div class="invoice__buttons__section">
            <div class="login_button">
                <a href="invoice_detail.php">ご請求明細</a>
            </div>
            <div class="login_button">
                <a href="">請求書発行</a>
            </div>
        </div>
    </div>
</div>

</div>