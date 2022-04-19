<?php
session_start();
require('../dbconnect.php');


// URLからIDを取得
// $id = $_GET['id'];


if (isset($_REQUEST['save'])) {

  $agent_name = $_REQUEST['agent_name'];
  $agent_tag = $_REQUEST['agent_tag'];
  // $agent_pic = $_REQUEST['agent_pic'];
  $agent_info = $_REQUEST['agent_info'];
  // $agent_display = $_REQUEST['agent_display'];
  if(isset($_REQUEST['agent_display'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_display = $_REQUEST['agent_display'];
  }
  $agent_pic = $_FILES['agent_pic']['name'];

  // $target_dir = "images/";
  // $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  // $uploadOk = 1;
  // $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    //   // URLからIDを取得
    //   echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
    //   $sql = "UPDATE agents SET agent_pic = '".$_FILES['fileToUpload']['name']."' WHERE id = 1";
    //   $stmt = $db->query($sql);
    // } else {
    //   echo "Sorry, there was an error uploading your file.";
    // }


  // $sql = 'UPDATE agents
  //       SET agent_name = ?, agent_tag = ?, agent_info = ?, agent_display = ?
  //       WHERE id = ?';
  // $stmt = $db->prepare($sql);
  // $stmt->execute(array($agent_name, $agent_tag, $agent_info, $agent_display, $id));
  // $stmt = null;
  // $db = null;
  // header('Location: http://localhost/craft_admin/home.php');
  // exit;

  if ((!empty($agent_name)) && (!empty($agent_tag)) && (!empty($agent_info))) {
    $db->query("UPDATE agents SET agent_name = '$agent_name', agent_tag = '$agent_tag', agent_info = '$agent_info'");
  }

  if (!empty($agent_pic)) {
    $tmpName = $_FILES["agent_pic"]["tmp_name"];
    $uploadDir = "images/";
    move_uploaded_file($tmpName, $uploadDir.$agent_pic);
    $db->query("UPDATE agents SET agent_pic = '$agent_pic'");
  }






}












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
  <h1>エージェント編集画面</h1>
  <form action="" method="POST">
    <p>
      <label for="agent_name">エージェント名：</label>
      <input type="text" name="agent_name" required>
    </p>
    <p>
      <label for="agent_tag">エージェントタグ：</label>
      <input type="text" name="agent_tag" required>
    </p>

    <p>
      <!-- <label for="agent_pic">エージェント画像：</label>
      <input type="file" name="agent_pic" required> -->
      <!-- <label for="agent_pic">エージェント画像：</label>
      <input type="file" name="agent_pic" required> -->

      Select image to upload:
      <input type="file" name="fileToUpload">
      <!-- <input type="submit" value="Upload Image" name="submit"> -->
      
    </p>
    <p>
      <label for="agent_info">エージェント説明：</label>
      <input type="textarea" name="agent_info" required>
    </p>
    <p>
      <label for="agent_display">掲載期間：</label>
      <select name="agent_display">
        <option value="1">1</option>
        <option value="3">3</option>
        <option value="6">6</option>
        <option value="12">12</option>
      </select>
      <span>ヶ月</span>
    </p>

<!-- 
    Select image to upload:
    <input type="file" name="agent_pic" id="agent_pic"> -->
    <p>
      <input type="submit" name="save" value="変更を保存">
    </p>
  </form>
  <!-- <form action="" method="post" enctype="multipart/form-data">
  </form> -->
</body>
</html>