<?php
session_start();
echo "<p>ホーム画面</p><p>";
echo $_SESSION['name'];
echo "がログイン中です</p>";