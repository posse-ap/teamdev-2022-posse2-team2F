<?php
require('../dbconnect.php');
session_start();

$mode = "input";

if (isset($_POST["back"]) && $_POST["back"]) {
  // 何もしない
} else if (isset($_POST["confirm"]) && $_POST["confirm"]) {
  $_SESSION["student_name"] = $_POST["student_name"];
  $_SESSION["student_email"] = $_POST["student_email"];
  $_SESSION["student_phone"] = $_POST["student_phone"];
  $_SESSION["student_university"] = $_POST["student_university"];
  $_SESSION["student_faculty"] = $_POST["student_faculty"];
  $_SESSION["student_address"] = $_POST["student_address"];
  $_SESSION["student_graduation"] = $_POST["student_graduation"];
  $mode = "confirm";
} else if (isset($_POST["send"]) && $_POST["send"]) {
  $mode = "send";
} else {

  $_SESSION["student_name"] = '';
  $_SESSION["student_email"] = '';
  $_SESSION["student_phone"] = '';
  $_SESSION["student_university"] = '';
  $_SESSION["student_faculty"] = '';
  $_SESSION["student_address"] = '';
  $_SESSION["student_display"] = '';
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/normalize.css">
  <link rel="stylesheet" href="/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <title>申し込み画面</title>
</head>


<body>

  <?php require('../_header.php'); ?>

  <div class="util_fullscreen_container">
    <div class="util_fullscreen userform">
      <?php if ($mode == "input") { ?>
        <h1 class="userform_title">申し込み</h1>
        <!-- POST情報がないときのHTMLコード（入力画面） -->
        <form action="form.php" method="post" enctype="multipart/form-data">
          <div class="userform_text">
            <label class="userform_text--label" for="student_name">氏名<span>必須</span></label>
            <input class="userform_text--box" type="text" name="student_name" placeholder="例）山田太郎" value="<?= $_SESSION["student_name"] ?>" required />
          </div>
          <div class="userform_text">
            <label class="userform_text--label" for="student_email">メールアドレス<span>必須</span></label>
            <input class="userform_text--box" type="email" name="student_email" placeholder="例）taroyamada@gmail.com" value="<?= $_SESSION["student_email"] ?>" required>
          </div>
          <div class="userform_text">
            <label class="userform_text--label" for="student_phone">電話番号<span>必須</span></label>
            <input class="userform_text--box" type="tel" name="student_phone" placeholder="例）09011110000" value="<?= $_SESSION["student_phone"] ?>" required>
          </div>
          <div class="userform_text">
            <label class="userform_text--label" for="student_university">大学<span>必須</span></label>
            <input class="userform_text--box" type="text" name="student_university" placeholder="例）〇〇大学" value="<?= $_SESSION["student_university"] ?>" required>
          </div>
          <div class="userform_text">
            <label class="userform_text--label" for="student_faculty">学科<span>必須</span></label>
            <input class="userform_text--box" type="text" name="student_faculty" placeholder="例）〇〇学科" value="<?= $_SESSION["student_faculty"] ?>" required>
          </div>
          <div class="userform_text">
            <label class="userform_text--label" for="student_address">住所<span>必須</span></label>
            <input class="userform_text--box" type="text" name="student_address" placeholder="例）東京都〇〇区1-1-1" value="<?= $_SESSION["student_address"] ?>" required>
          </div>
          <div class="userform_text">
            <label class="userform_text--label" for="student_graduation">卒業年<span>必須</span></label>
            <select class="userform_text--select" name="student_graduation" value="<?= $_SESSION["student_graduation"] ?>" placeholder="選択してください">
              <option selected value="">選択してください</option>
              <option value="24">24</option>
              <option value="25">25</option>
              <option value="26">26</option>
              <option value="27">27</option>
              <option value="28">28</option>
            </select>
          </div>

          <input type="button" name="back" value="一覧に戻る" class="userform_button userform_button--left">
          <input type="submit" name="confirm" value="確認画面へ" class="userform_button userform_button--right">
        </form>

      <?php } else if ($mode == "confirm") { ?>
        <!-- POST情報があるときのHTMLコード（確認画面） -->
        <h1 class="userform_title">内容確認</h1>
        <?php require('cart.php'); ?>
        <form action="form.php" method="post" enctype="multipart/form-data">
          <p>入力された内容をご確認の上、「上記を確認のうえ申し込み」ボタンを押してください。申し込みはこのボタンを押すまで確定しません。</p>
          <div class="userform_text">
            <label>氏名</label>
            <p><?= $_SESSION['student_name'] ?></p>
          </div>
          <div class="userform_text">
            <label>メールアドレス</label>
            <p><?= $_SESSION['student_email'] ?></p>
          </div>
          <div class="userform_text">
            <label>電話番号</label>
            <p><?= $_SESSION['student_phone'] ?></p>
          </div>
          <div class="userform_text">
            <label>大学</label>
            <p><?= $_SESSION['student_university'] ?></p>
          </div>
          <div class="userform_text">
            <label>学科</label>
            <p><?= $_SESSION['student_faculty'] ?></p>
          </div>
          <div class="userform_text">
            <label>住所</label>
            <p><?= $_SESSION['student_address'] ?></p>
          </div>
          <div class="userform_text">
            <label>卒業年</label>
            <p><?= $_SESSION['student_graduation'] ?></p>
          </div>
          <input type="submit" name="back" value="戻る" />
          <input type="submit" name="send" value="送信" />
        </form>

      <?php } else if ($mode == "send") { ?>
        <?php
        // $_POST['student_name'] = $student_name;
        // echo $student_name;

        // $sql = 'INSERT INTO students_contact(name, email, phone, university, faculty, address, grad_year) 
        //       VALUES (?, ?, ?, ?, ?, ?, ?)';
        $sql =
          'START TRANSACTION;
        INSERT INTO students_contact(name, email, phone, university, faculty, address, grad_year) VALUES (?, ?, ?, ?, ?, ?, ?);
        INSERT INTO students_contact_delete(name, email, phone, university, faculty, address, grad_year) VALUES (?, ?, ?, ?, ?, ?, ?);
        COMMIT;';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($_SESSION['student_name'], $_SESSION['student_email'], $_SESSION['student_phone'], $_SESSION['student_university'], $_SESSION['student_faculty'], $_SESSION['student_address'], $_SESSION['student_graduation'], $_SESSION['student_name'], $_SESSION['student_email'], $_SESSION['student_phone'], $_SESSION['student_university'], $_SESSION['student_faculty'], $_SESSION['student_address'], $_SESSION['student_graduation']));


        // メール送信 - エージェント用
        $to      = "agent1@agent1.com";
        $subject = "学生の申し込みがありました";
        $message = "

        〇〇agent様

        学生の新規申し込みがありました
        以下でご確認ください：
        // リンク
        ";

        $headers = "From: craft@boozer.com";

        mb_send_mail($to, $subject, $message, $headers);

        // メール送信 - 学生用
        // $to      = "student1@gmail.com";
        $to      = $_SESSION['student_email'];
        $subject = "学生の申し込みがありました";
        $message = "
        〇〇様

        申し込みありがとうございます！
        以下でご確認ください：
        // リンク

      ";
        $headers = "From: craft@boozer.com";

        mb_send_mail($to, $subject, $message, $headers);

        // メール送信 - 学生用
        $to      = "admin@boozer.com";
        $subject = "学生の申し込みがありました";
        $message = "
    
        〇〇エージェントから申し込みがありました！
        以下でご確認ください：
        // リンク

      ";
        $headers = "From: craft@boozer.com";

        mb_send_mail($to, $subject, $message, $headers);

        ?>

      <?php } else { ?>
        <!-- 完了画面 -->
      <?php } ?>


      <?php require('../_footer.php'); ?>

</body>

</html>


</body>

</html>