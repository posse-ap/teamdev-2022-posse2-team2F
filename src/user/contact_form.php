<?php
if (!isset($_SESSION)) {
    session_start();
}
// session_start();

use LDAP\Result;

require('../dbconnect.php');

$mode = "input";

if (isset($_POST["back"]) && $_POST["back"]) {
    // 何もしない
} else if (isset($_POST["confirm"]) && $_POST["confirm"]) {
    $_SESSION["title_select"] = $_POST["title_select"];
    $_SESSION["student_name"] = $_POST["student_name"];
    $_SESSION["student_email"] = $_POST["student_email"];
    $_SESSION["student_phone"] = $_POST["student_phone"];
    $_SESSION["student_detail"]  = $_POST["student_detail"];
    $mode = "confirm";
} else if (isset($_POST["send"]) && $_POST["send"]) {
    $mode = "send";
} else {
    $_SESSION["title_select"] = '';
    $_SESSION["student_name"] = '';
    $_SESSION["student_email"] = '';
    $_SESSION["student_phone"] = '';
    $_SESSION["student_detail"] = '';
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
    <title>お問い合わせフォーム</title>
</head>

<body>

    <?php require('../_header.php'); ?>

    <div class="util_fullscreen_container">
        <div class="util_fullscreen userform">
            <?php if ($mode == "input") { ?>
                <h1 class="userform_title">お問い合わせフォーム</h1>
                <p>※CRAFTはエージェント会社情報については保持しておりません</p>
                <!-- POST情報がないときのHTMLコード（入力画面） -->
                <form action="contact_form.php" method="post" enctype="multipart/form-data">
                    <span class="err-msg-name"></span>
                    <div class="userform_text">
                        <label class="userform_text--label" for="title_select">お問合せ内容<span class="required">必須</span></label>
                        <select name="title_select" id="contact_title_select">
                            <option value="掲載エージェントに関して">掲載エージェントに関して</option>
                            <option value="サイト利用に関して">サイト利用に関して</option>
                            <option value="お申し込みに関して">お申し込みに関して</option>
                            <option value="その他">その他</option>
                        </select>
                    </div>
                    <span class="err-msg-name"></span>
                    <div class="userform_text">
                        <label class="userform_text--label" for="student_name">氏名<span class="required">必須</span></label>
                        <input class="userform_text--box" type="text" name="student_name" id="name" placeholder="例）山田太郎" value="<?= $_SESSION["student_name"] ?>" />
                    </div>
                    <span class="err-msg-email"></span>
                    <div class="userform_text">
                        <label class="userform_text--label" for="student_email">メールアドレス<span class="required">必須</span></label>
                        <input class="userform_text--box" type="email" name="student_email" id="email" placeholder="例）taroyamada@gmail.com" value="<?= $_SESSION["student_email"] ?>">
                    </div>
                    <span class="err-msg-phone"></span>
                    <div class="userform_text">
                        <label class="userform_text--label" for="student_phone">電話番号<span class="required">必須</span></label>
                        <input class="userform_text--box" type="tel" name="student_phone" id="phone" placeholder="例）09011110000" value="<?= $_SESSION["student_phone"] ?>">
                    </div>
                    <!-- <span class="err-msg-phone"></span> -->
                    <div class="userform_text">
                        <label class="userform_text--label" for="student_detail">詳細<span class="required">必須</span></label>
                        <input class="userform_text--box" type="text" name="student_detail" id="detail" placeholder="詳しく教えてください" value="<?= $_SESSION["student_detail"] ?>">
                    </div>

                    <input type="button" name="back" value="一覧に戻る" class="userform_button userform_button--left">
                    <input type="submit" name="confirm" value="確認画面へ" class="userform_button userform_button--right confirm">
                </form>

            <?php } else if ($mode == "confirm") { ?>
                <!-- POST情報があるときのHTMLコード（確認画面） -->
                <h1 class="userform_title">内容確認</h1>
                <?php //require('cart.php'); 
                ?>
                <form action="contact_form.php" method="post" enctype="multipart/form-data">
                    <p>入力された内容をご確認の上、「上記を確認のうえ送信」ボタンを押してください。このボタンを押すまで送信は完了しません。</p>
                    <div class="userform_text">
                        <label>お問合せ内容</label>
                        <p><?= $_SESSION['title_select'] ?></p>
                    </div>
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
                        <label>詳細</label>
                        <p><?= $_SESSION['student_detail'] ?></p>
                    </div>
                    <input type="submit" name="back" value="戻る" />
                    <input type="submit" name="send" value="送信" />
                </form>

            <?php } else if ($mode == "send") { ?>
                <?php
                // $_POST['student_name'] = $student_name;
                // echo $student_name;

                $sql = 'INSERT INTO user_contact_form(title, name, email, phone, detail) 
                VALUES (?, ?, ?, ?, ?)';
                $stmt = $db->prepare($sql);
                $stmt->execute(array( $_SESSION["title_select"], $_SESSION['student_name'], $_SESSION['student_email'], $_SESSION['student_phone'], $_SESSION['student_detail']));

                echo '<p>お問合せが完了しました</p>';


                // ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝　ここからメール送信  ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
                // - 学生用
                $to      = $_SESSION['student_email'];
                $subject = "お問合せ完了通知メール";
                $message = "
        " .  $_SESSION["student_name"] . "様

        お問合せありがとうございました。
        ご記入いただいた内容をご確認ください:


                ";
                $message .= 'お問合せ内容：' . $_SESSION["title_select"] . '
                
                ';
                $message .= '名前：' . $_SESSION["student_name"]  . '
                
                ';
                $message .= 'email：' . $_SESSION["student_email"]  . '
                
                ';
                $message .= '電話番号：'.  $_SESSION["student_phone"] . '
                
                ' ;
                $message .= '詳細：' . $_SESSION["student_detail"] . '
                
                ' ;

                $headers = "From: craft@boozer.com";

                mb_send_mail($to, $subject, $message, $headers);

                // - boozer用
                $to      = "admin@boozer.com";
                $subject = "学生のお問合せがありました";
                $message = "
    
        ユーザーの" . $_SESSION["student_name"] . "様からお問合せがありました。
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
        // エラーメッセージを表示させる要素を取得
        const errMsgName = document.querySelector('.err-msg-name');
        const errMsgEmail = document.querySelector('.err-msg-email');
        const errMsgPhone = document.querySelector('.err-msg-phone');
        // 「先頭に記号を含まない、@と.を含む」文字列を判定
        const email_match = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}.[A-Za-z0-9]{1,}$/;
        const phone_match = /^0\d{9,10}$/;

        // 氏名バリデーション
        if (!name.value) {
            // デフォルトアクションをキャンセル
            e.preventDefault();

            errMsgName.classList.add('form-invalid');
            errMsgName.textContent = 'お名前が入力されていません';
            // クラスを追加(フォームの枠線を赤くする)
            name.classList.add('input-invalid');
            return;
        } else {
            errMsgName.textContent = '';
            name.classList.remove('input-invalid');
        }

        // メールバリデーション
        if (!email.value) {

            // デフォルトアクションをキャンセル
            e.preventDefault();

            errMsgEmail.classList.add('form-invalid');
            errMsgEmail.textContent = 'メールアドレスが入力されていません';
            // クラスを追加(フォームの枠線を赤くする)
            email.classList.add('input-invalid');
            return;
        } else if (!email_match.test(email.value)) {
            e.preventDefault();
            errMsgEmail.textContent = 'メールアドレスの形式が不正です。';
        } else {
            errMsgEmail.textContent = '';
            email.classList.remove('input-invalid');
        }

        // 電話番号バリデーション
        if (!phone.value) {

            // デフォルトアクションをキャンセル
            e.preventDefault();

            errMsgPhone.classList.add('form-invalid');
            errMsgPhone.textContent = '電話番号が入力されていません';
            // クラスを追加(フォームの枠線を赤くする)
            phone.classList.add('input-invalid');
            return;
        } else if (!phone_match.test(phone.value)) {
            e.preventDefault();
            errMsgPhone.textContent = '電話番号の形式が不正です。';
            return;
        } else {
            errMsgPhone.textContent = '';
            phone.classList.remove('input-invalid');
        }
    }, false);
</script>

</html>


</body>

</html>