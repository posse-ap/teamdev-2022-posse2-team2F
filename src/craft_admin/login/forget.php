<?php

session_start();
require('../../dbconnect.php');




$err_msg = "";

if (isset($_POST['submit_email'])) {
  // $_POST['email'] = $_SESSION['email'];
  $_SESSION["email"] = $_POST['email'];
  $email = $_POST['email'];
  
  $sql = 'SELECT count(*) FROM craft_users WHERE email = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($email));
  $result = $stmt->fetch();

  // result に一つでも値が入っているなら、登録メールアドレスが存在するということ
  if ($result[0] != 0) {
    // メール送信 
    $to      = $_POST['email'];
    $subject = "パスワード再発行";
    $message = "
    トラスト・ログインのパスワードリセットの申請を受け付けました。

    パスワードの再設定をご希望の場合は、以下URLをクリックし
    新しいパスワードをご登録ください。

    ※パスワードリセットの申請に心当たりがない場合は、以降の対応は不要となります。

    ▼パスワードの再設定URL
    http://localhost/craft_admin/login/reset.php

    ";
    $headers = "From: craft@boozer.com";

    mb_send_mail($to, $subject, $message, $headers);

    header('Location: http://localhost/craft_admin/login/send_link.php');

    


    exit;
  } else {
    $err_msg = "メールアドレスが登録されていません。";
  }

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <p>パスワードの再設定が必要となります。</p>
  <p>恐れ入りますが、登録されたメールアドレスをご入力いただき、受信されたメールの案内に従ってパスワード再設定をお願いします。</p>
  <p>登録しているメールアドレス</p>
  <form action="/craft_admin/login/forget.php" method="POST">
    <input type="text" name="email">
    <?php if ($err_msg !== null && $err_msg !== '') { echo $err_msg .  "<br>";} ?>
    <input type="submit" name="submit_email">
  </form>
  
</body>
</html>