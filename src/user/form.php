<?php

use LDAP\Result;

require('../dbconnect.php');
session_start();

//ここからお気に入り削除
// 削除用
if(isset($_POST['cart_delete'])){
  $delete_id = isset($_POST['delete_id'])? htmlspecialchars($_POST['delete_id'], ENT_QUOTES, 'utf-8') : '';
  
  // 削除
  if ($delete_id != '') {
    unset($_SESSION['products'][$delete_id]);
  }
  header('Location: cart.php');
  }

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
          <span class="err-msg-name"></span>
          <div class="userform_text">
            <label class="userform_text--label" for="student_name">氏名<span class="required">必須</span></label>
            <input class="userform_text--box" type="text" name="student_name" id="name" placeholder="例）山田太郎" value="<?= $_SESSION["student_name"] ?>"  />
          </div>
          <span class="err-msg-email"></span>
          <div class="userform_text">
            <label class="userform_text--label" for="student_email">メールアドレス<span class="required">必須</span></label>
            <input class="userform_text--box" type="email" name="student_email" id="email" placeholder="例）taroyamada@gmail.com" value="<?= $_SESSION["student_email"] ?>" >
          </div>
          <span class="err-msg-phone"></span>
          <div class="userform_text">
            <label class="userform_text--label" for="student_phone">電話番号<span class="required">必須</span></label>
            <input class="userform_text--box" type="tel" name="student_phone" id="phone" placeholder="例）09011110000" value="<?= $_SESSION["student_phone"] ?>" >
          </div>
          <span class="err-msg-university"></span>
          <div class="userform_text">
            <label class="userform_text--label" for="student_university">大学<span class="required">必須</span></label>
            <input class="userform_text--box" type="text" name="student_university" id="university" placeholder="例）〇〇大学" value="<?= $_SESSION["student_university"] ?>" >
          </div>
          <span class="err-msg-faculty"></span>
          <div class="userform_text">
            <label class="userform_text--label" for="student_faculty">学科<span class="required">必須</span></label>
            <input class="userform_text--box" type="text" name="student_faculty" id="faculty" placeholder="例）〇〇学科" value="<?= $_SESSION["student_faculty"] ?>" >
          </div>
          <span class="err-msg-address"></span>
          <div class="userform_text">
            <label class="userform_text--label" for="student_address">住所<span class="required">必須</span></label>
            <input class="userform_text--box" type="text" name="student_address" id="address" placeholder="例）東京都〇〇区1-1-1" value="<?= $_SESSION["student_address"] ?>" >
          </div>
          <span class="err-msg-graduation"></span>
          <div class="userform_text">
            <label class="userform_text--label" for="student_graduation">卒業年<span class="required">必須</span></label>
            <select class="userform_text--select" name="student_graduation" id="graduation" value="<?= $_SESSION["student_graduation"] ?>" placeholder="選択してください">
              <option selected value="">選択してください</option>
              <option value="24">24</option>
              <option value="25">25</option>
              <option value="26">26</option>
              <option value="27">27</option>
              <option value="28">28</option>
            </select>
          </div>

          <input type="button" name="back" value="一覧に戻る" class="userform_button userform_button--left">
          <input type="submit" name="confirm" value="確認画面へ" class="userform_button userform_button--right confirm">
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

<!-- フォームのバリデーション -->
<script>
  // 「送信」ボタンの要素を取得
  const confirm = document.querySelector('.confirm');

  // 「送信」ボタンの要素にクリックイベントを設定
  confirm.addEventListener('click', (e) => {

    const name = document.querySelector('#name');
    const email = document.querySelector('#email');
    const phone = document.querySelector('#phone');
    const university = document.querySelector('#university');
    const faculty = document.querySelector('#faculty');
    const address = document.querySelector('#address');
    const graduation = document.querySelector('#graduation');
    // エラーメッセージを表示させる要素を取得
    const errMsgName = document.querySelector('.err-msg-name');
    const errMsgEmail = document.querySelector('.err-msg-email');
    const errMsgPhone = document.querySelector('.err-msg-phone');
    const errMsgUniversity = document.querySelector('.err-msg-university');
    const errMsgFaculty = document.querySelector('.err-msg-faculty');
    const errMsgAddress = document.querySelector('.err-msg-address');
    const errMsgGraduation = document.querySelector('.err-msg-graduation');
    // 「先頭に記号を含まない、@と.を含む」文字列を判定
    const email_match = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}.[A-Za-z0-9]{1,}$/;
    const phone_match = /^0\d{9,10}$/;

    // 氏名バリデーション
    if(!name.value){
        // デフォルトアクションをキャンセル
        e.preventDefault();

        errMsgName.classList.add('form-invalid');
        errMsgName.textContent = 'お名前が入力されていません';
        // クラスを追加(フォームの枠線を赤くする)
        name.classList.add('input-invalid');
        return;
    } else {
        errMsgName.textContent ='';
        name.classList.remove('input-invalid');
    }

    // メールバリデーション
    if(!email.value){     

        // デフォルトアクションをキャンセル
        e.preventDefault();

        errMsgEmail.classList.add('form-invalid');
        errMsgEmail.textContent = 'メールアドレスが入力されていません';
        // クラスを追加(フォームの枠線を赤くする)
        email.classList.add('input-invalid');
        return;
    } else if (!email_match.test(email.value)){
        e.preventDefault();
        errMsgEmail.textContent = 'メールアドレスの形式が不正です。';
    } else {
        errMsgEmail.textContent ='';
        email.classList.remove('input-invalid');
    }

    // 電話番号バリデーション
    if(!phone.value){     

        // デフォルトアクションをキャンセル
        e.preventDefault();

        errMsgPhone.classList.add('form-invalid');
        errMsgPhone.textContent = '電話番号が入力されていません';
        // クラスを追加(フォームの枠線を赤くする)
        phone.classList.add('input-invalid');
        return;
    } else if (!phone_match.test(phone.value)){
        e.preventDefault();
        errMsgPhone.textContent = '電話番号の形式が不正です。';
        return;
    } else {
        errMsgPhone.textContent ='';
        phone.classList.remove('input-invalid');
    }

    // 大学名のバリデーション
    if(!university.value){
        // デフォルトアクションをキャンセル
        e.preventDefault();

        errMsgUniversity.classList.add('form-invalid');
        errMsgUniversity.textContent = '大学名が入力されていません';
        // クラスを追加(フォームの枠線を赤くする)
        university.classList.add('input-invalid');
        return;
    } else {
        errMsgUniversity.textContent ='';
        university.classList.remove('input-invalid');
    }

    // 学科名のバリデーション
    if(!faculty.value){

        // デフォルトアクションをキャンセル
        e.preventDefault(); 

        errMsgFaculty.classList.add('form-invalid');
        errMsgFaculty.textContent = '学科名が入力されていません';
        // クラスを追加(フォームの枠線を赤くする)
        faculty.classList.add('input-invalid');
        return;
    } else {
        errMsgFaculty.textContent ='';
        faculty.classList.remove('input-invalid');
    }

    // 住所のバリデーション
    if(!address.value){

        // デフォルトアクションをキャンセル
        e.preventDefault(); 

        errMsgAddress.classList.add('form-invalid');
        errMsgAddress.textContent = '住所が入力されていません';
        // クラスを追加(フォームの枠線を赤くする)
        address.classList.add('input-invalid');
        return;
    } else {
        errMsgAddress.textContent ='';
        address.classList.remove('input-invalid');
    }

    // 卒業年のバリデーション
    if(!graduation.value){

        // デフォルトアクションをキャンセル
        e.preventDefault();

        errMsgGraduation.classList.add('form-invalid');
        errMsgGraduation.textContent = '卒業年が選択されていません';
        // クラスを追加(フォームの枠線を赤くする)
        graduation.classList.add('input-invalid');
        return;
    } else {
        errMsgGraduation.textContent ='';
        graduation.classList.remove('input-invalid');
    }
    

  }, false);


  

</script>

</html>


</body>

</html>