<?php
require('../dbconnect.php');

// 画像 & エージェント名表示用
$stmt = $db->query("SELECT * FROM agents");
$results = $stmt->fetchAll();

// セッション保存用
if (isset($_POST['favorite'])) {
$agent_name = isset($_POST['agent_name'])? htmlspecialchars($_POST['agent_name'], ENT_QUOTES, 'utf-8') : '';
$agent_tag = isset($_POST['agent_tag'])? htmlspecialchars($_POST['agent_tag'], ENT_QUOTES, 'utf-8') : '';
$agent_info = isset($_POST['agent_info'])? htmlspecialchars($_POST['agent_info'], ENT_QUOTES, 'utf-8') : '';

// 削除用
$delete_name = isset($_POST['delete_name'])? htmlspecialchars($_POST['delete_name'], ENT_QUOTES, 'utf-8') : '';

session_start();

// 削除
if ($delete_name != '') {
  unset($_SESSION['products'][$delete_name]);
}

if($agent_name!=''&&$agent_tag!=''&&$agent_info!=''){
  $_SESSION['products'][$agent_name]=[
            'agent_tag' => $agent_tag,
            'agent_info' => $agent_info
  ];
}
header("Location: /userpage/result.php");
}



// if(isset($products)){
//   foreach($products as $key => $product){
//       echo $key;      //商品名
//       echo "<br>";
//       echo $product['agent_tag'];  
//       echo "<br>";
//       echo $product['agent_info']; 
//       echo "<br>";
//   }
// } 

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