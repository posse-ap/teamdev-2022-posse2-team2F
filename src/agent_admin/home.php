<?php
session_start();
require('../dbconnect.php');

// URLからIDを取得
$id = $_SESSION['id'];


// 既存データの表示
$stmt = $db->query("SELECT * FROM agent_users WHERE id = '$id'");
$result = $stmt->fetch();


echo "<p>ホーム画面</p><p>";
echo $result['name'];
// echo $_SESSION['dept'];
echo "がログイン中です</p>";
?>
<a href="http://localhost/agent_admin/students_info.php">ここ</a>
