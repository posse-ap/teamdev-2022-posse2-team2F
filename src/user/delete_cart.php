<?php
require('../dbconnect.php');

session_start();

$products = isset($_SESSION['products'])? $_SESSION['products']:[];

$id = $_GET['id'];
$stmt = $db->query("SELECT * FROM agents WHERE id = '$id'");
$result = $stmt->fetch();

//削除機能(result.phpから飛んできた時)
if(isset($result['id'])){
  $delete_id = isset($result['id'])? htmlspecialchars($result['id'], ENT_QUOTES, 'utf-8') : '';
  
  // 削除
  if ($delete_id != '') {
    unset($_SESSION['products'][$delete_id]);
  }
  header('Location: /user/cart.php');
  }
  ?>