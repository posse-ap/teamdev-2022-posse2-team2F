<?php
require('../dbconnect.php');

$id = $_GET['id'];
$agent = $_GET['agent'];

if (isset($_POST['delete_request'])) {

  $sql = "START TRANSACTION;

          INSERT INTO delete_student_application(application_id, agent_name) VALUES (?, ?);

          UPDATE students_agent SET status = ? WHERE id = ?;

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

  $success = $stmt->execute(array($id, $agent, '削除申請中', $id));
  if (!$success) {
    die($db->error);
  }
  sleep(1);
  header('Location: students_info.php');

}


$stmt->execute(array($application_id, $student_info['agent'], '削除申請中', $application_id));






?>