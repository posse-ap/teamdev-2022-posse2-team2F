<?php
require('../dbconnect.php');
session_start();

$mode = "input";

if( isset($_POST["back"] ) && $_POST["back"] ){
  // 何もしない
} else if( isset($_POST["confirm"] ) && $_POST["confirm"] ){
  $_SESSION["student_name"] = $_POST["student_name"];
  $_SESSION["student_email"] = $_POST["student_email"];
  $_SESSION["student_phone"] = $_POST["student_phone"];
  $_SESSION["student_university"] = $_POST["student_university"];
  $_SESSION["student_faculty"] = $_POST["student_faculty"];
  $_SESSION["student_address"] = $_POST["student_address"];
  $_SESSION["student_graduation"] = $_POST["student_graduation"];
  $mode = "confirm";
} else if( isset($_POST["send"] ) && $_POST["send"] ){
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
<html>
<body>
<?php require('../_header.php'); ?>

<!-- <script type="text/javascript" src="contact.js"></script> -->


<?php if( $mode == "input") { ?>
  <!-- POST情報がないときのHTMLコード（入力画面） -->
  <form action="form.php" method="post" enctype="multipart/form-data">
    <div>
      <label for="student_name">氏名</label>
      <input  type="text" name="student_name" placeholder="例）山田太郎" value="<?= $_SESSION["student_name"] ?>" required/>
    </div>
    <div>
      <label for="student_email">メールアドレス</label>
      <input type="text" name="student_email" placeholder="例）taroyamada@gmail.com" value="<?= $_SESSION["student_email"] ?>" required>
    </div>
    <div>
      <label for="student_phone">電話番号</label>
      <input type="text" name="student_phone" placeholder="例）09011110000" value="<?= $_SESSION["student_phone"] ?>" required>
    </div>
    <div>
      <label for="student_university">大学</label>
      <input type="text" name="student_university" placeholder="例）〇〇大学" value="<?= $_SESSION["student_university"] ?>" required>
    </div>
    <div>
      <label for="student_faculty">学科</label>
      <input type="text" name="student_faculty" placeholder="例）〇〇学科" value="<?= $_SESSION["student_faculty"] ?>" required>
    </div>
    <div>
      <label for="student_address">住所</label>
      <input type="text" name="student_address" placeholder="例）東京都〇〇区1-1-1" value="<?= $_SESSION["student_address"] ?>" required>
    </div>
    <div>
      <label for="student_graduation">卒業年</label>
      <select name="student_graduation" value="<?= $_SESSION["student_graduation"] ?>">
        <option value="24">24</option>
        <option value="25">25</option>
        <option value="26">26</option>
        <option value="27">27</option>
        <option value="28">28</option>
      </select>
    </div>
    <input type="submit" name="confirm" value="確認画面へ" class="manage_button">
  </form>
  
<?php } else if( $mode == "confirm" ){ ?>
  <!-- POST情報があるときのHTMLコード（確認画面） -->

  <?php require('cart.php'); ?>

  <form action="form.php" method="post" enctype="multipart/form-data">
    <h1>お問い合わせ 内容確認</h1>
    <p>お問い合わせ内容はこちらで宜しいでしょうか？<br>よろしければ「送信する」ボタンを押して下さい。</p>
    <div>
      <label>氏名</label>
      <p><?= $_SESSION['student_name'] ?></p>
    </div>
    <div>
      <label>メールアドレス</label>
      <p><?= $_SESSION['student_email'] ?></p>
    </div>
    <div>
      <label>電話番号</label>
      <p><?= $_SESSION['student_phone'] ?></p>
    </div>
    <div>
      <label>大学</label>
      <p><?= $_SESSION['student_university'] ?></p>
    </div>
    <div>
      <label>学科</label>
      <p><?= $_SESSION['student_faculty'] ?></p>
    </div>
    <div>
      <label>住所</label>
      <p><?= $_SESSION['student_address'] ?></p>
    </div>
    <div>
      <label>卒業年</label>
      <p><?= $_SESSION['student_graduation'] ?></p>
    </div>
    <input type="submit" name="back" value="戻る" />
    <input type="submit" name="send" value="送信" />
  </form>

<?php } else if ( $mode == "send" ) { ?>
  <?php
  // $_POST['student_name'] = $student_name;
  // echo $student_name;
  
  $sql = 'INSERT INTO students_contact(name, email, phone, university, faculty, address, grad_year) 
          VALUES (?, ?, ?, ?, ?, ?, ?)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($_SESSION['student_name'], $_SESSION['student_email'], $_SESSION['student_phone'], $_SESSION['student_university'], $_SESSION['student_faculty'], $_SESSION['student_address'], $_SESSION['student_graduation']));
  

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