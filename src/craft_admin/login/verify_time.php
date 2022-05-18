<?php
require('../../dbconnect.php');

$passResetToken = $_GET['pass_reset'];

// リセットトークン発行から 24時間経過しているかチェックするファイル
// 成功の場合 reset.php に、 失敗の場合 expired.php 

// 計算用
$sql = 'SELECT count(*) FROM password_reset WHERE pass_token = ?';
$stmt = $db->prepare($sql);
$stmt->execute(array($passResetToken));
$result = $stmt->fetch();

// 利用
$sql = 'SELECT * FROM password_reset WHERE pass_token = ?';
$stmt = $db->prepare($sql);
$stmt->execute(array($passResetToken));
$verify = $stmt->fetch();

// result に一つでも値が入っているなら、トークンが存在するということ
if ($result[0] != 0) {

  // 今の時間を取得
  $limitTime = date("Y-m-d H:i:s");

  // トークン発行から 24時間経過していない場合、パスワード再発行できる
  // 今の時間（秒） - トークン発行の時間 （秒）
  if (strtotime($limitTime) - strtotime($verify['created_at']) <= 60 * 60 * 24)
  { 
    header('Location: http://localhost/craft_admin/login/reset.php');
    exit;
  } else {
    header('Location: http://localhost/craft_admin/login/expired.php');
  }

  exit;
} else {
  header('Location: http://localhost/craft_admin/warning.php');
}

?>