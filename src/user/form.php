<?php

use LDAP\Result;

require('../dbconnect.php');
session_start();

// //ここからまとめて申し込み
if(isset($_POST['apply_id'])){
  if(isset($_POST['apply_tag']) && is_array($_POST['apply_tag'])){
  }else{
    header("Location: /userpage/result.php");
  }
}
// //ここまで

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
          <div class="userform_applied">
            <p class="userform_heading">お問い合わせのエージェント</p>
            <table class="userform_applied--table" border=1; style=border-collapse:collapse;>
              <tr>
                <th>エージェント名</th>
                <th>エージェント説明</th>
              </tr>
              <?php 
              // ここからまとめて申し込み
              if(isset($_POST['apply_id'])){
                
                if(isset($_POST['apply_tag']) && is_array($_POST['apply_tag'])){
                  $_SESSION['tag_id'] = $_POST['apply_tag'];

                  foreach ($_POST['apply_tag'] as $tag_id) {
                    $stmt = $db->query("SELECT * FROM agents WHERE id = '$tag_id'");
                    $results = $stmt->fetchAll();
                  ?>

                  <?php foreach($results as $result){ ?>
                    
                  <tr>
                  <th><?= $result['agent_name'] ?></th>
                  <th><?= $result['agent_title'] ?></th>
                  </tr>
                  <?php } ?>

                  <?php
                  }
                } 
              } elseif (!isset($_POST['apply_id']) && isset($_POST['apply_id_single'])) 
              {
                $single_id = key($_POST['apply_id_single']); //$id には押された番号が入る
                $_SESSION['single_id'] = $single_id;

                $sql_single = "SELECT * FROM agents WHERE id = ?";
                $stmt = $db->prepare($sql_single);
                $stmt->execute(array($single_id));
                $result_single = $stmt->fetch();


                ?>
                  
                  <tr>
                    <th><?= $result_single['agent_name'] ?></th>
                    <th><?= $result_single['agent_title'] ?></th>
                  </tr>

            <?php 
              }
                ?>
            </table>
          </div>

          <p class="userform_heading">個人情報の入力</p>
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
            <label class="userform_text--label" for="student_faculty">学部・学科<span class="required">必須</span></label>
            <input class="userform_text--box" type="text" name="student_faculty" id="faculty" placeholder="例）〇〇学部〇〇学科" value="<?= $_SESSION["student_faculty"] ?>" >
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
          <a href="/userpage/result.php">
          <input type="button" name="back" value="一覧に戻る" class="userform_button userform_button--left">
          </a>
          <input type="submit" name="confirm" value="確認画面へ" class="userform_button userform_button--right confirm">
        </form>

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
              errMsgEmail.classList.add('form-invalid');
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
              errMsgPhone.classList.add('form-invalid');
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

          // 学部学科名のバリデーション
          if(!faculty.value){

              // デフォルトアクションをキャンセル
              e.preventDefault(); 

              errMsgFaculty.classList.add('form-invalid');
              errMsgFaculty.textContent = '学部・学科名が入力されていません';
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

      <?php } else if ($mode == "confirm") { 
        

        ?>



        

        <!-- POST情報があるときのHTMLコード（確認画面） -->
        <h1 class="userform_title">内容確認</h1>
        <form action="form.php" method="post" enctype="multipart/form-data">

        <?php 

          // foreach($results_after as $result_after){ ?>

            <div class="userform_applied">
              <p class="userform_heading">お問い合わせのエージェント</p>
              <table class="userform_applied--table" border=1; style=border-collapse:collapse;>
                <tr>
                  <th>エージェント名</th>
                  <th>エージェント説明</th>
                </tr>
                
              <?php 
              // ここからまとめて申し込み
              if(isset($_SESSION['tag_id']) && is_array($_SESSION['tag_id'])){

                
                  foreach ($_SESSION['tag_id'] as $tag_id) {
                    $stmt = $db->query("SELECT * FROM agents WHERE id = '$tag_id'");
                    $results = $stmt->fetchAll();


                  ?>


                  <?php foreach($results as $result){ ?>
                    
                  <tr>
                  <th><?= $result['agent_name'] ?></th>
                  <th><?= $result['agent_title'] ?></th>
                  </tr>
                  <?php } ?>

                  <?php

                    
                }
              } else
              {

                $id = $_SESSION['single_id']; //$id には押された番号が入る


                $stmt = $db->query("SELECT * FROM agents WHERE id = '$id'");
                $result = $stmt->fetch();

              ?>
                  
                  <tr>
                    <th><?= $result['agent_name'] ?></th>
                    <th><?= $result['agent_title'] ?></th>
                  </tr>

            <?php  
              
              }


                ?>
              </table>
            </div>
          <p class="userform_heading">個人情報</p>
          <div class="userform_text">
            <label class="userform_text--label">氏名<span class="required">必須</span></label>
            <input class="userform_text--box box-white" type="text" value="<?= $_SESSION["student_name"] ?>" readonly/>
          </div>
          <div class="userform_text">
            <label class="userform_text--label">メールアドレス<span class="required">必須</span></label>
            <input class="userform_text--box box-white" type="email" value="<?= $_SESSION["student_email"] ?>" readonly/>
          </div>
          <div class="userform_text">
            <label class="userform_text--label">電話番号<span class="required">必須</span></label>
            <input class="userform_text--box box-white" type="tel" value="<?= $_SESSION["student_phone"] ?>" readonly />
          </div>
          <div class="userform_text">
            <label class="userform_text--label">大学<span class="required">必須</span></label>
            <input class="userform_text--box box-white" type="text" value="<?= $_SESSION["student_university"] ?>" readonly/>
          </div>
          <div class="userform_text">
            <label class="userform_text--label">学科<span class="required">必須</span></label>
            <input class="userform_text--box box-white" type="text" value="<?= $_SESSION["student_faculty"] ?>" readonly/>
          </div>
          <div class="userform_text">
            <label class="userform_text--label">住所<span class="required">必須</span></label>
            <input class="userform_text--box box-white" type="text" value="<?= $_SESSION["student_address"] ?>" readonly/>
          </div>
          <div class="userform_text">
            <label class="userform_text--label">卒業年<span class="required">必須</span></label>
            <input class="userform_text--box box-white" type="text" value="<?= $_SESSION["student_graduation"] ?>" readonly/>
          </div>
          <p class="userform_heading">利用規約</p>
          <div class="userform_agreement">
              <h1>利用規約</h1>
              <p>この利用規約（以下、「本規約」といいます。）は、CRAFT（以下、「当社」といいます。）がこのウェブサイト上で提供するサービス（以下、「本サービス」といいます。）の利用条件を定めるものです。登録ユーザーの皆さま（以下、「ユーザー」といいます。）には、本規約に従って、本サービスをご利用いただきます。</p>
              <h2>第1条（適用）</h2>
              <p>1. 本規約は，ユーザーと当社との間の本サービスの利用に関わる一切の関係に適用されるものとします。</p>
              <p>2. 当社は本サービスに関し，本規約のほか，ご利用にあたってのルール等，各種の定め（以下，「個別規定」といいます。）をすることがあります。これら個別規定はその名称のいかんに関わらず，本規約の一部を構成するものとします。</p>
              <p>3. 本規約の規定が前条の個別規定の規定と矛盾する場合には，個別規定において特段の定めなき限り，個別規定の規定が優先されるものとします。</p>
              <h2>第2条（利用登録）</h2>
              <p>1. 本サービスにおいては，登録希望者が本規約に同意の上，当社の定める方法によって利用登録を申請し，当社がこれを承認することによって，利用登録が完了するものとします。</p>
              <p>2. 当社は，利用登録の申請者に以下の事由があると判断した場合，利用登録の申請を承認しないことがあり，その理由については一切の開示義務を負わないものとします。</p>
              <h2>第3条（ユーザーIDおよびパスワードの管理）</h2>
              <p>1. ユーザーは，自己の責任において，本サービスのユーザーIDおよびパスワードを適切に管理するものとします。</p>
              <p>2. ユーザーは，いかなる場合にも，ユーザーIDおよびパスワードを第三者に譲渡または貸与し，もしくは第三者と共用することはできません。当社は，ユーザーIDとパスワードの組み合わせが登録情報と一致してログインされた場合には，そのユーザーIDを登録しているユーザー自身による利用とみなします。</p>
              <p>3. ユーザーID及びパスワードが第三者によって使用されたことによって生じた損害は，当社に故意又は重大な過失がある場合を除き，当社は一切の責任を負わないものとします。</p>
              <h2>第4条（利用料金および支払方法）</h2>
              <p>1. ユーザーは，本サービスの有料部分の対価として，当社が別途定め，本ウェブサイトに表示する利用料金を，当社が指定する方法により支払うものとします。</p>
              <p>2. ユーザーが利用料金の支払を遅滞した場合には，ユーザーは年14．6％の割合による遅延損害金を支払うものとします。</p>
              <h2>第5条（禁止事項）</h2>
              <p>ユーザーは，本サービスの利用にあたり，以下の行為をしてはなりません。</p>
              <p>1. 法令または公序良俗に違反する行為</p>
              <p>2. 犯罪行為に関連する行為</p>
              <p>3. 本サービスの内容等，本サービスに含まれる著作権，商標権ほか知的財産権を侵害する行為</p>
              <p>4. 当社，ほかのユーザー，またはその他第三者のサーバーまたはネットワークの機能を破壊したり，妨害したりする行為</p>
              <h2>第6条（本サービスの提供の停止等）</h2>
              <p>1. 当社は，以下のいずれかの事由があると判断した場合，ユーザーに事前に通知することなく本サービスの全部または一部の提供を停止または中断することができるものとします。</p>
              <p>2. 当社は，本サービスの提供の停止または中断により，ユーザーまたは第三者が被ったいかなる不利益または損害についても，一切の責任を負わないものとします。</p>
              <h2>第7条（利用制限および登録抹消）</h2>
              <p>1. 当社は，ユーザーが以下のいずれかに該当する場合には，事前の通知なく，ユーザーに対して，本サービスの全部もしくは一部の利用を制限し，またはユーザーとしての登録を抹消することができるものとします。</p>
              <p>2. 当社は，本条に基づき当社が行った行為によりユーザーに生じた損害について，一切の責任を負いません。</p>
              <h2>第8条（退会）</h2>
              <p>ユーザーは，当社の定める退会手続により，本サービスから退会できるものとします。</p>
              <h2>第9条（保証の否認および免責事項）</h2>
              <p>1. 当社は，本サービスに事実上または法律上の瑕疵（安全性，信頼性，正確性，完全性，有効性，特定の目的への適合性，セキュリティなどに関する欠陥，エラーやバグ，権利侵害などを含みます。）がないことを明示的にも黙示的にも保証しておりません。</p>
              <p>2. 当社は，本サービスに起因してユーザーに生じたあらゆる損害について、当社の故意又は重過失による場合を除き、一切の責任を負いません。ただし，本サービスに関する当社とユーザーとの間の契約（本規約を含みます。）が消費者契約法に定める消費者契約となる場合，この免責規定は適用されません。</p>
              <h2>第10条（サービス内容の変更等）</h2>
              <p>当社は，ユーザーへの事前の告知をもって、本サービスの内容を変更、追加または廃止することがあり、ユーザーはこれを承諾するものとします。</p>
              <h2>第11条（利用規約の変更）</h2>
              <p>1. 当社は以下の場合には、ユーザーの個別の同意を要せず、本規約を変更することができるものとします。。</p>
              <p>2. 当社はユーザーに対し、前項による本規約の変更にあたり、事前に、本規約を変更する旨及び変更後の本規約の内容並びにその効力発生時期を通知します。</p>
              <h2>第12条（個人情報の取扱い）</h2>
              <p>当社は，本サービスの利用によって取得する個人情報については，当社「プライバシーポリシー」に従い適切に取り扱うものとします。</p>
              <h2>第13条（通知または連絡）</h2>
              <p>ユーザーと当社との間の通知または連絡は，当社の定める方法によって行うものとします。当社は,ユーザーから,当社が別途定める方式に従った変更届け出がない限り,現在登録されている連絡先が有効なものとみなして当該連絡先へ通知または連絡を行い,これらは,発信時にユーザーへ到達したものとみなします。</p>
              <h2>第14条（権利義務の譲渡の禁止）</h2>
              <p>ユーザーは，当社の書面による事前の承諾なく，利用契約上の地位または本規約に基づく権利もしくは義務を第三者に譲渡し，または担保に供することはできません。</p>
              <h2>第15条（準拠法・裁判管轄）</h2>
              <p>1. 本規約の解釈にあたっては，日本法を準拠法とします。</p>
              <p>2. 本サービスに関して紛争が生じた場合には，当社の本店所在地を管轄する裁判所を専属的合意管轄とします。。</p>
          </div>
          <div class="userform_check">
            <input type="checkbox" required>
            <p>上記に同意する</p>
          </div>
          <input type="submit" name="back" value="戻る" class="userform_button userform_button--left"/>
          <input type="submit" name="send" value="送信" class="userform_button userform_button--right confirm"/>
        </form>

      <?php } else if ($mode == "send") { ?>


        <!-- 完了画面 -->
        <div class="done">
          <h1 class="done_title">お問合せありがとうございます。</h1>
          <div class="done_body">
            <p class="done_body--text">お問合せ内容については、順次対応させていただきます。</p>
            <p class="done_body--text">システムによる自動返信にて、受付完了メールを送信しております。</p>
            <p class="done_body--text">メールが届かない場合は、お手数ですが再度お問い合わせいただく</p>
            <p class="done_body--text">か、下記のメールアドレスまでご連絡ください。</p>
            <p class="done_body--text">craft@boozer.com</p>
          </div>
          <a href="../userpage/top.php" class="done_button">ホームに戻る</a>
        </div>
        


        <?php
        // students_contact にまず学生情報を入れる
        $sql = 'INSERT INTO students_contact(name, email, phone, university, faculty, address, grad_year) 
        VALUES (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($_SESSION['student_name'], $_SESSION['student_email'], $_SESSION['student_phone'], $_SESSION['student_university'], $_SESSION['student_faculty'], $_SESSION['student_address'], $_SESSION['student_graduation']));

        // 続いて students_agent に学生が登録したエージェントの情報を入れる
        $agent_sql = $db->query("SELECT id FROM students_contact ORDER BY id DESC LIMIT 1");
        $id = $agent_sql->fetch();

        // 複数申し込みした場合のテーブル追加処理
        if(isset($_SESSION['tag_id']))
        {
          foreach ($_SESSION['tag_id'] as $tag_id) {
            $stmt = $db->query("SELECT * FROM agents WHERE id = '$tag_id'");
            // $email_use = $stmt->fetch();
            
            $results = $stmt->fetchAll();

            foreach($results as $result) 
            {
            $sql = "INSERT INTO students_agent(student_id, agent_id, agent) VALUES (?, ?, ?);";
            $stmt = $db->prepare($sql);
            $stmt->execute(array($id['id'], $result['id'], $result['agent_name']));

            $sql_email = "SELECT agent_users.notify_email, agent_users.agent_name FROM agent_users INNER JOIN agents ON agent_users.agent_name = agents.agent_name WHERE agent_users.agent_name = ?";
            $stmt = $db->prepare($sql_email);
            $stmt->execute(array($result['agent_name']));
            $email = $stmt->fetch();

            $agent_name = $result['agent_name'];

            $to      = $email['notify_email'];
            $subject = "学生の申し込みがありました";
            $message = "

            ${agent_name}様


            学生の新規申し込みがありました 
            以下でご確認ください：
            // リンク";
            // 文字列の中で変数を展開
            // $moji = "apple"
            // echo "${moji}"
            // ${変数名}で展開されます
            $headers = "From: craft@boozer.com";

            mb_send_mail($to, $subject, $message, $headers);


            }

          }
        // 個別申し込みした場合
        } else {

          $single_id = $_SESSION['single_id']; 

          $stmt = $db->query("SELECT * FROM agents WHERE id = '$single_id'");
          $result = $stmt->fetch();

          $sql = "INSERT INTO students_agent(student_id, agent_id, agent) VALUES (?, ?, ?);";
          $stmt = $db->prepare($sql);
          $stmt->execute(array($id['id'], $result['id'], $result['agent_name']));

          $sql_email = "SELECT agent_users.notify_email, agent_users.agent_name FROM agent_users INNER JOIN agents ON agent_users.agent_name = agents.agent_name WHERE agent_users.agent_name = ?";
          $stmt = $db->prepare($sql_email);
          $stmt->execute(array($result['agent_name']));
          $email = $stmt->fetch();

          $agent_name = $result['agent_name'];

          // メールの送信先
          $to = $email['notify_email'];
          $subject = "学生の申し込みがありました";
          $message = "

          ${agent_name}様

          文字列の中で変数を展開

          学生の新規申し込みがありました
          以下からログインしてご確認ください：
          http://localhost/agent_admin/login/login.php
          ";

          $headers = "From: craft@boozer.com";

          mb_send_mail($to, $subject, $message, $headers);

        }





        

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

      ";
        $headers = "From: craft@boozer.com";

        mb_send_mail($to, $subject, $message, $headers);

        ?>


      <?php } 

      require('../_footer.php'); ?>

    </div>
  </div>
</body>



</html>

