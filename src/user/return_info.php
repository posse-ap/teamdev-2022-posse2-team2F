<?php
require('../dbconnect.php');

// 画像 & エージェント名表示用
$stmt = $db->query("SELECT * FROM agents");
$results = $stmt->fetchAll();

session_start();

?>
<?php

//お気に入り登録
// セッション保存用
$id = $_GET['id'];
$stmt = $db->query("SELECT * FROM agents WHERE id = '$id'");
$result = $stmt->fetch();

$agent_id = isset($result['id'])? htmlspecialchars($result['id'], ENT_QUOTES, 'utf-8') : '';
  $agent_name = isset($result['agent_name'])? htmlspecialchars($result['agent_name'], ENT_QUOTES, 'utf-8') : '';
  $agent_tag = isset($result['agent_tag'])? htmlspecialchars($result['agent_tag'], ENT_QUOTES, 'utf-8') : '';
  $agent_title = isset($result['agent_title'])? htmlspecialchars($result['agent_title'], ENT_QUOTES, 'utf-8') : '';
  $agent_point1 = isset($result['agent_point1'])? htmlspecialchars($result['agent_point1'], ENT_QUOTES, 'utf-8') : '';
  $agent_point2 = isset($result['agent_point2'])? htmlspecialchars($result['agent_point2'], ENT_QUOTES, 'utf-8') : '';
  $agent_point3 = isset($result['agent_point3'])? htmlspecialchars($result['agent_point3'], ENT_QUOTES, 'utf-8') : '';
  
  // 削除用
  $delete_name = isset($result['delete_name'])? htmlspecialchars($result['delete_name'], ENT_QUOTES, 'utf-8') : '';
  
  
  // 削除
  if ($delete_name != '') {
    unset($_SESSION['products'][$delete_name]);
  }
  
  if($agent_name!=''&&$agent_tag!=''&&$agent_title!=''){
    $_SESSION['products'][$agent_id]=[
              'agent_tag' => $agent_tag,
              'agent_title' => $agent_title,
              'agent_point1' => $agent_point1,
              'agent_point2' => $agent_point2,
              'agent_point3' => $agent_point3,
              'agent_name' => $agent_name,
              'agent_id' => $agent_id,
    ];
    //お気に入りボタン用
    // $_SESSION['ids'][$agent_id] = ['agent_id' => $agent_id];
  }
  // sleep(3);
header("Location: /userpage/info.php?id=$id");
?>