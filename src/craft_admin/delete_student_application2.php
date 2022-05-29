<?php
require('../dbconnect.php');

$id = $_GET['id'];
$agent = $_GET['agent'];


// $sql = "SELECT * FROM agent_users JOIN students_agent ON students_agent.agent = agent_users.agent_name WHERE students_agent.agent_id = ? LIMIT 1";
$sql = "SELECT notify_email FROM agent_users WHERE agent_name = ? LIMIT 1";
$mail_stmt = $db->prepare($sql);
$mail_stmt->execute(array($agent));
$email = $mail_stmt->fetch();

$to = $email['notify_email'];
$subject = "新規削除申請があります";
$message = "

  学生情報の削除がありました。
      
  以下からログインしてご確認ください。
  http://localhost/agent_admin/login/login.php";
// 文字列の中で変数を展開
// $moji = "apple"
// echo "${moji}"
// ${変数名}で展開されます
$headers = "From: craft@boozer.com";

mb_send_mail($to, $subject, $message, $headers);

$sql = "START TRANSACTION;
          
          UPDATE students_agent
          SET 
          deleted_at = CURRENT_TIMESTAMP,
          status = '削除済み' 
          WHERE id = ?;

          UPDATE delete_student_application
          SET 
          response = '削除済み'
          WHERE application_id = ?;

          COMMIT;
          ";
$stmt = $db->prepare($sql);
$stmt->execute(array($id, $id));






header('Location: http://localhost/craft_admin/inquiries_delete.php');
// }

// //削除したくない場合
// if (isset($_POST['keep']) && $_POST["keep"]) {
//   $button_keep = key($_POST['keep']); //$buttonには押された番号が入る
//   // $agent_id = key($_POST['agentid']); 

//   // $sql = "SELECT * FROM agent_users JOIN students_agent ON students_agent.agent = agent_users.agent_name WHERE students_agent.agent_id = ? LIMIT 1";
//   $sql = "SELECT * FROM agent_users JOIN students_agent ON students_agent.agent_id = agent_users.id WHERE students_agent.agent_id = ? LIMIT 1";
//   $mail_stmt = $db->prepare($sql);
//   $mail_stmt->execute(array($agent_id));

//   $email = $mail_stmt->fetch();

//   $to = $email['notify_email'];

//   $sql = "START TRANSACTION;
          
//           UPDATE students_agent
//           SET 
//           status = '有効' 
//           WHERE id = ?;

//           UPDATE delete_student_application
//           SET response = '削除なし'
//           WHERE application_id = ?;

//           COMMIT;";
//   $stmt = $db->prepare($sql);
//   $stmt->execute(array($button_keep, $button_keep));

//   $subject = "削除申請の拒否があります。";
//   $message = "

//   学生情報の削除申請が拒否されました。
      
//   以下からログインしてご確認ください。
//   http://localhost/agent_admin/login/login.php";
//   // 文字列の中で変数を展開
//   // $moji = "apple"
//   // echo "${moji}"
//   // ${変数名}で展開されます
//   $headers = "From: craft@boozer.com";

//   mb_send_mail($to, $subject, $message, $headers);

//   header('Location: http://localhost/craft_admin/inquiries_delete.php');
// }
