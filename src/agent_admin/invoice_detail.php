<?php
session_start();
include('../_header.php');
require('../dbconnect.php');

// 全ての請求件数
$sql = "SELECT count(name) FROM students";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute();
$all_students_info = $sql_prepare->fetchAll();
?>

<h2>請求画面明細</h2>

<h3>明細概観</h3>

<p>
〇〇年〇〇月合計件数：<?php print_r($all_students_info[0][0]); ?>件
</p>

<?php
/*
// 削除依頼件数 わからない！
$sql = "SELECT count(name) FROM students";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute();
$all_students_info = $sql_prepare->fetchAll();
*/
?>

<p>
削除依頼件数：<?= "わかりません > . <" ?>件
</p>

<?php
// 削除件数
$sql_deleted = "SELECT (max(id) - count(name)) FROM students";
$sql_deleted_prepare = $db->prepare($sql_deleted);
$sql_deleted_prepare->execute();
$deleted_students = $sql_deleted_prepare->fetchAll();
?>

<p>
    削除件数：<?php print_r($deleted_students[0][0]); ?>件
</p>

<?php
// 合計件数
$sql = "SELECT count(name) FROM students";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute();
$all_students_info = $sql_prepare->fetchAll();

/*
//全ての請求件数 エージェントごと
$sql = "SELECT COUNT(*) FROM students WHERE agent = ?";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute(array($_SESSION['name']));
$all_students_info = $sql_prepare->fetchAll();
*/
?>

<p>
    合計件数：<?php print_r($all_students_info[0][0]) ?>件
</p>

<?php
// *5000円
$sql_valid = "SELECT count(name) * 5000 FROM students";
$sql_valid_prepare = $db->prepare($sql_valid);
$sql_valid_prepare->execute();
$all_valid_students = $sql_valid_prepare->fetchAll();
?>

<p>
〇〇年〇〇月合計額：<?php print_r($all_valid_students[0][0]); ?>円
</p>

<!-- 緑の背景つけたかったからとりあえず適当にクラスつけた -->
<div class="login_button">
    <a href="invoice.php">戻る</a>
</div>
<div class="login_button">
    <a href="">請求書発行</a>
</div>