<?php

require('../dbconnect.php');

// URLからIDを取得
$id = $_GET['id'];?>




// 既存データの表示
<?php $stmt = $db->query("SELECT * FROM agents WHERE id = '$id'");
$result = $stmt->fetch();



if (isset($_POST['submit'])) {

  // 画像以外の更新
  $agent_name = $_POST['agent_name'];
  $agent_tag = $_POST['agent_tag'];
  // $agent_pic = $_POST['agent_pic'];
  $agent_info = $_POST['agent_info'];
  // $agent_display = $_POST['agent_display'];
  if(isset($_POST['agent_display'])) {
    // セレクトボックスで選択された値を受け取る
    $agent_display = $_POST['agent_display'];
  }

  $sql = 'UPDATE agents
        SET agent_name = ?, agent_tag = ?, agent_info = ?, agent_display = ?
        WHERE id = ?';
  $stmt = $db->prepare($sql);
  $stmt->execute(array($agent_name, $agent_tag, $agent_info, $agent_display, $id));

  // 画像更新
  $target_dir = "images/";
  $target_file = $target_dir . basename($_FILES["agent_pic"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  if (move_uploaded_file($_FILES["agent_pic"]["tmp_name"], $target_file)) {
    // echo "The file ". htmlspecialchars( basename( $_FILES["agent_pic"]["name"])). " has been uploaded.";
    // 既存データの表示
    $sql = "UPDATE agents SET agent_pic = '".$_FILES['agent_pic']['name']."' WHERE id = '$id'";
    $stmt = $db->query($sql);
  } else {
    // echo "Sorry, there was an error uploading your file.";
  }


  header('Location: http://localhost/craft_admin/home.php');
  exit;
}


?>

<!DOCTYPE html>
<html>
<body>
<?php require('../_header.php'); ?>

<div class="agent_container">
    <div class="agent_leftcontainer">
      <div class="agent_manage">
        <a href="/craft_admin/home.php">エージェント管理</a>
      </div>
      <div class="agent_add">
        <a href="/craft_admin/add.php">エージェント追加</a>
      </div>
      <div class="tag_manage">
        <a href="">タグ編集・追加</a>
      </div>
      <div class="usersite">
        <a href="">ユーザー用サイトへ</a>
      </div>
    </div>
    <div class="agent_rightcontainer">

      <form action="" method="post" enctype="multipart/form-data">
        <p>
          <label for="agent_name">エージェント名：</label>
          <input type="text" name="agent_name" value="<?= $result['agent_name'] ?>" required>
        </p>
        <p>
          <label for="agent_tag">エージェントタグ：</label>
          <input type="text" name="agent_tag" value="<?= $result['agent_tag'] ?>" required>
        </p>
        <p>
          <label for="agent_info">エージェント説明：</label>
          <input type="textarea" name="agent_info" value="<?= $result['agent_info'] ?>" required>
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
          <img src="images/<?= $result['agent_pic'] ?>" alt="" style="width: 500px">
          <!-- <input type="image" src=" ?>" style="width: 500px"> -->
          <input id="image" type="file" name="agent_pic">
        </p>
        
        <input type="submit" value="変更を保存" name="submit">
      </form>
    </div>
</div>


<?php require('../_footer.php'); ?>
</body>
</html>

