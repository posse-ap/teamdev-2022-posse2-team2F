<?php
session_start();
require('../dbconnect.php');


// 画像 & エージェント名表示用（元のデータを表示、画像はできてない）
$stmt = $db->query("SELECT * FROM agents");
$result = $stmt->fetch();

// URLからIDを取得
$id = $_GET['id'];


if (isset($_POST['save'])) {

  $agent_name = $_POST['agent_name'];
  $agent_tag = $_POST['agent_tag'];
  // $agent_pic = $_POST['agent_pic'];
  // $agent_pic = $_POST['agent_pic'];
  $agent_info = $_POST['agent_info'];
  // $agent_display = $_POST['agent_display'];
  if(isset($_POST['agent_display'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_display = $_POST['agent_display'];
  }

  $target_dir = "images/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // // Check if image file is a actual image or fake image
  // if(isset($_POST["submit"])) {
  //   $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  //   if($check !== false) {
  //     echo "File is an image - " . $check["mime"] . ".";
  //     $uploadOk = 1;
  //   } else {
  //     echo "File is not an image.";
  //     $uploadOk = 0;
  //   }
  // }

  // Check if file already exists
  // if (file_exists($target_file)) {
  //   echo "Sorry, file already exists.";
  //   $uploadOk = 0;
  // }

  // Check file size
  // if ($_FILES["fileToUpload"]["size"] > 500000) {
  //   echo "Sorry, your file is too large.";
  //   $uploadOk = 0;
  // }

  // Allow certain file formats
  // if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  // && $imageFileType != "gif" ) {
  //   echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  //   $uploadOk = 0;
  // }

  // Check if $uploadOk is set to 0 by an error
  // if ($uploadOk == 0) {
  //   echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  // } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      // URLからIDを取得
      echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
      $sql = "UPDATE agents SET agent_pic = '".$_FILES['fileToUpload']['name']."' WHERE id = 1";
      $stmt = $db->query($sql);
    } else {
      echo "Sorry, there was an error uploading your file.";
    }


  $sql = 'UPDATE agents
        SET agent_name = ?, agent_tag = ?, agent_info = ?, agent_display = ?
        WHERE id = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_name, $agent_tag, $agent_info, $agent_display, $id));
  $stmt = null;
  $db = null;
  header('Location: http://localhost/craft_admin/home.php');
  exit;
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