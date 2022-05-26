<?php
require('../dbconnect.php');

// 詳細ページから削除したい場合


if (isset($_POST['delete_more'])) {


  $id = $_GET['id'];
// $agent = $_GET['agent'];

// var_dump($id);

$sql = "START TRANSACTION;
          
        UPDATE students_agent
        SET 
          deleted_at = CURRENT_TIMESTAMP,
          status = '削除済み' 
        WHERE id = ?;

        UPDATE delete_student_application
        SET response = '削除済み'
        WHERE application_id = ?;

        COMMIT;";

$stmt = $db->prepare($sql);

if (!$stmt) {
  die($db->error);
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$id) {
  echo '生徒が正しく指定されていません';
  exit();
}
// $stmt->bindParam(1, $id);
// $stmt->bindParam(2, $agent);
// $stmt->bindParam(3, '削除申請中');
// $stmt->bindParam(4, $id);
// $success = $stmt->execute();

$success = $stmt->execute(array($id, $id));
if (!$success) {
  die($db->error);
}
sleep(1);
header('Location: students_info.php');

}


// お問合せ管理ページから削除したい場合


if (isset($_POST['delete']) && $_POST["delete"]) {
  $button_delete = key($_POST['delete']); //$button_deleteには押された番号が入る

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
  $stmt->execute(array($button_delete, $button_delete));

  header('Location: http://localhost/craft_admin/inquiries.php');
}

//削除したくない場合
if (isset($_POST['keep']) && $_POST["keep"]) {
  $button_keep = key($_POST['keep']); //$buttonには押された番号が入る

  $sql = "START TRANSACTION;
          
          UPDATE students_agent
          SET 
          status = '有効' 
          WHERE id = ?;

          UPDATE delete_student_application
          SET response = '削除なし'
          WHERE application_id = ?;

          COMMIT;";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($button_keep, $button_keep));

  header('Location: http://localhost/craft_admin/inquiries.php');
}

?>