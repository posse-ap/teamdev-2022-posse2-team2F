<?php
session_start();
echo "<p>ホーム画面</p><p>";
echo $_SESSION['name'];
echo "がログイン中です</p>";
?>
<a href="http://localhost/agent_admin/students_info.php">ここ</a>
