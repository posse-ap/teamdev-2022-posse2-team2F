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
  $agent_title2 = isset($result['agent_title2'])? htmlspecialchars($result['agent_title2'], ENT_QUOTES, 'utf-8') : '';
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
              'agent_title2' => $agent_title2,
              'agent_point1' => $agent_point1,
              'agent_point2' => $agent_point2,
              'agent_point3' => $agent_point3,'agent_name' => $agent_name,
              'agent_id' => $agent_id,
    ];
    //お気に入りボタン用
    // $_SESSION['ids'][$agent_id] = ['agent_id' => $agent_id];
  }
  // sleep(3);
header("Location: /userpage/result.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php require('../_header.php'); ?>
  
    <?php foreach ($results as $result) : ?>
    <img src="../craft_admin/images/<?= $result['agent_pic'] ?>" alt="" style="height: 18.7vh">
    <div>
      <h1><?= $result['agent_name'] ?></h1>
      <p><?= $result['agent_info'] ?></p>
      <p><?= $result['agent_tagname'] ?></p>
      <form action="home.php" method="POST" class="item-form">
        <input type="hidden" name="agent_name" value="<?= $result['agent_name'] ?>">
        <input type="hidden" name="agent_info" value="<?= $result['agent_info'] ?>">
        <input type="hidden" name="agent_tag" value="<?= $result['agent_tagname'] ?>">
        <button type="submit" name="favorite" class="btn-sm btn-blue">お気に入りに追加</button>
      </form>
    </div>
    <?php endforeach; ?>



  <?php require('../_footer.php'); ?>

  <?php require('cart.php'); ?>




    
  </script>

</body>

</html>