<?php

// とりあえず edit_agent.php をコビペしてきただけ

require('../dbconnect.php');


// URLからIDを取得 = 必要なし


// 既存データの表示 = 必要なし


// 画像以外の更新
if (isset($_POST['submit'])) {


  $agent_name = $_POST['agent_name'];
  $agent_tag = $_POST['agent_tag'];
  $agent_info = $_POST['agent_info'];
  // $agent_display = $_POST['agent_display'];
  if(isset($_POST['agent_display'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_display = $_POST['agent_display'];
  }

  // $sql = 'INSERT INTO agents(agent_name, agent_tag, agent_info, agent_display) 
  //         VALUES (?, ?, ?, ?)';
  // $stmt = $db->prepare($sql);
  // $stmt->execute(array($agent_name, $agent_tag, $agent_info, $agent_display));

  // 画像更新
  $target_dir = "images/";
  $target_file = $target_dir . basename($_FILES["agent_pic"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // 予想
  // INSERT INTO文 は一回で書かないとだから、編集画面みたいに分けて書けない
  // 画像をアップロードして、さらに登録ボタンが押されたら SQL文を書く仕組みにした！ （どうせ画像の登録は必要になるから）

  if (move_uploaded_file($_FILES["agent_pic"]["tmp_name"], $target_file)) {
    $sql = 'INSERT INTO agents(agent_name, agent_pic, agent_tag, agent_info, agent_display) 
            VALUES (?, ?, ?, ?, ?)';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($agent_name, $_FILES['agent_pic']['name'], $agent_tag, $agent_info, $agent_display));
  } else {
    // echo "Sorry, there was an error uploading your file.";
  }


  header('Location: http://localhost/craft_admin/home.php');
  exit;
}


// }

?>

<!DOCTYPE html>
<html>
<body>


<form action="" method="post" enctype="multipart/form-data">
  <p>
    <label for="agent_name">エージェント名：</label>
    <input type="text" name="agent_name" required>
  </p>
  <p>
    <label for="agent_tag">エージェントタグ：</label>
    <input type="text" name="agent_tag" required>
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
  <p>
    <label for="agent_pic">エージェント画像：</label>
    <br>
    <!-- <img src="images/" alt="" style="width: 500px"> -->
    <!-- <input type="image" src=" ?>" style="width: 500px"> -->
    <input id="image" type="file" name="agent_pic">
  </p>
  
  <input type="submit" value="変更を保存" name="submit">
</form>


</body>
</html>