<?php
session_start();
include('../_header.php');
require('../dbconnect.php');

// 画像以外の更新
if (isset($_POST['submit'])) {

  $content = $_POST['content'];
  $details = $_POST['details'];


  $sql = 'INSERT INTO agent_inquiries(content, details) 
          VALUES (?, ?)';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($content, $details));

  
  header('Location: students_info.php');
  exit;
}

?>


<div class="util_container">
    <div class="util_sidebar no-print-area">
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/agent_admin/students_info.php">学生申し込み一覧</a>
            <i class="fas fa-angle-right"></i>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/agent_admin/edit_info.php">担当者情報管理</a>
            <i class="fas fa-angle-right"></i>
        </div>
        <div class="util_sidebar_button util_sidebar_button--selected">
            <a class="util_sidebar_link  util_sidebar_link--selected" href="/agent_admin/inquiries.php">お問合せ</a>
            <i class="fas fa-angle-right"></i>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="/agent_admin/invoice.php">請求金額確認</a>
            <i class="fas fa-angle-right"></i>
        </div>
        <div class="util_sidebar_button">
            <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
            <i class="fas fa-angle-right"></i>
        </div>
    </div>


    <div class="util_content">
        <!-- <h2 class="no-print-area"></h2> -->
        <div class="util_title">
            <h2 class="util_title--text">
                お問合せ
            </h2>
        </div>

        <div class="inquiries">
          <form action="" method="post" enctype="multipart/form-data">
            <div class="inquiries_item">
              <label class="inquiries_item--label" for="content">お問合せ内容</label>
              <select class="inquiries_item--select" name="content">
                <option selected value="">選択してください</option>
                <option value="エージェントの情報変更依頼">エージェントの情報変更依頼</option>
                <option value="サイトの使い方に関して">サイトの使い方に関して</option>
                <option value="その他">その他</option>
              </select>
            </div>
            <div class="inquiries_item">
              <label class="inquiries_item--label" for="details">詳細・理由</label>
              <textarea class="inquiries_item--textarea" name="details"></textarea>
            </div>
            <input class="inquiries_button" type="submit" value="送信" name="submit">
          </form>
        </div>
        
    </div>
</div>


<?php require('../_footer.php'); ?>
</body>