<?php
session_start();
include('../_header.php');
require('../dbconnect.php');

// 合計件数
$sql = "SELECT count(name) FROM students";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute();
$all_students_info = $sql_prepare->fetchAll();
?>

<h2>合計請求金額確認</h2>

<h3>〇〇年〇〇月分</h3>

<p>
合計申し込み件数：<?php print_r($all_students_info[0][0]); ?>件
</p>


<?php
// *5000円
$sql_valid = "SELECT count(name) * 5000 FROM students";
$sql_valid_prepare = $db->prepare($sql_valid);
$sql_valid_prepare->execute();
$all_valid_students = $sql_valid_prepare->fetchAll();
?>

<p>
ご請求金額合計：<?php print_r($all_valid_students[0][0]); ?>円
</p>

<div class="login_button">
    <a href="invoice_detail.php">ご請求明細</a>
</div>
<div class="login_button">
    <a href="">請求書発行</a>
</div>