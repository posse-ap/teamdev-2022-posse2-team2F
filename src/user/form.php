<?php

use LDAP\Result;

require('../dbconnect.php');
session_start();

//ここからまとめて申し込み
if(isset($_POST['apply_id'])){
  if(isset($_POST['apply_tag']) && is_array($_POST['apply_tag'])){
    $tag_ids = $_POST['apply_tag'];
    // $split_ids = explode(',', $tag_ids);

    

    foreach ($tag_ids as $tag_id) {
      $stmt = $db->query("SELECT * FROM agents WHERE id = '$tag_id'");
      $results = $stmt->fetchAll();
      foreach($results as $result){
        echo $result['agent_name'];
        echo $result['agent_info'];
      }
    }

  }else{
    header("Location: /userpage/result.php");
  }
}
//ここまで

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

<?php
//ここからまとめて申し込み
// if(isset($_POST['apply_id'])){
//   if(isset($_POST['apply_tag']) && is_array($_POST['apply_tag'])){
//     $tag_ids = $_POST['apply_tag'];
//     // $split_ids = explode(',', $tag_ids);

    

//     foreach ($tag_ids as $tag_id) {
//       $stmt = $db->query("SELECT * FROM agents WHERE id = '$tag_id'");
//       $results = $stmt->fetchAll();
//       foreach($results as $result){
//         echo $result['agent_name'];
//         echo $result['agent_info'];
//       }
//     }

//   }else{
//     header("Location: /userpage/result.php");
//   }
// }
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
  
  

  // mb_language("Japanese");
  // mb_internal_encoding("UTF-8");
  // $to = 'kohei.s.n@gmail.com';
  // $title = 'test';
  // $message = 'hello!';
  // $additional_headers = "From:".mb_encode_mimeheader("送信者2")."<test2@example.com>";

  
  // $to      = 'kohei.s.n@gmail.com';
  // $subject = 'the subject';
  // $message = 'hello';
  // $headers = 'From: koheilampard@gmail.com';
      // 'Reply-To: webmaster@example.com' . "\r\n" .
      // 'X-Mailer: PHP/' . phpversion();

    // $to = "to@example.com";
    // $subject = "HTML MAIL";
    // $message = "<html><body><h1>This is HTML MAIL</h1></body></html>";
    // $headers = "From: from@example.com";
    // $headers .= "\r\n";
    // $headers .= "Content-type: text/html; charset=UTF-8";

    mb_language("Japanese");  //言語の指定
    mb_internal_encoding("UTF-8"); //文字コードの指定
    
    $to = 'kohei.s.n@example.com'; //送信先アドレスの指定
    $subject = 'こんにちは、これはテストです。';
    $message = 'お元気ですか？私は毎日健康に過ごしていますよ。また会いましょう。';
    $additional_headers = "From: a1@test.com\r\nReply-To: a1@test.com\r\n";//送信元の設定
    
    

    if(mb_send_mail ($to, $subject, $message, $additional_headers)){
      echo "メールを送信しました";
    } else {
      echo "メールの送信に失敗しました";
    };
  ?>

<?php } else { ?>
<!-- 完了画面 -->
<?php } ?>





<?php require('../_footer.php'); ?>

</body>
</html>


</body>
</html>