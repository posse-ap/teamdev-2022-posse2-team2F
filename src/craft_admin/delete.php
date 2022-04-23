<?php
require('../dbconnect.php');

$id = $_GET['id'];

$stmt = $db->prepare('delete from agents where id=?');

if (!$stmt) {
  die($db->error);
}
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$id) {
  echo 'エージェントが正しく指定されていません';
  exit();
}
$stmt->bindParam(1, $id);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
sleep(1);
header('Location: home.php');
?>