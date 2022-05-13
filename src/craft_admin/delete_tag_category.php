<?php
require('../dbconnect.php');

$id = $_GET['id'];

$stmt = $db->prepare('DELETE FROM tag_categories WHERE id=?');

if (!$stmt) {
  die($db->error);
}

if (!$id) {
  echo 'タグが正しく指定されていません';
  exit();
}
$stmt->bindParam(1, $id);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
sleep(1);
header('Location: tag.php');
?>