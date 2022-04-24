<?php
session_start();
require('../dbconnect.php');

// タグカテゴリーを表示
$stmt = $db->query("SELECT tag_category, tag_category_desc FROM tags GROUP BY tag_category, tag_category_desc;");
$results = $stmt->fetchAll();

// タグ内容を表示
// $stmt2 = $db->query("SELECT * FROM tags");
// $results2 = $stmt2->fetchAll();

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

  <p>タグの編集・追加</p>

  <?php foreach ($results as $result) : ?>
  <div style="background: lightblue; border: dashed red;">
    <p><?= $result['tag_category'] ?></p>
    <div id="more" style="display:none;">
      <p>タグのカテゴリーの説明：</p>
      <p><?= $result['tag_category_desc'] ?></p>
    </div>
    <!-- <a href="javascript:showMore()" id="" style="color: red">詳細</a> -->
    <button id="btn" style="color: red" class="btn">詳細</button>
  </div>
  <?php endforeach; ?>
  
  

  <?php require('../_footer.php'); ?>

<script>

  // const object = document.getElementById('btn');
  const object = document.getElementsByClassName("btn");
  object.forEach(
    object.onclick = function(){
      let more = document.getElementById('more');
      if (more.style.display === "none") {
        more.style.display = "block";
      } else {
        more.style.display = "none";
      }

      location.href = "http://localhost/craft_admin/tag.php?id=1";
    });
  // function showMore(){
  //   //removes the link
  //   // document.getElementById('link').parentElement.removeChild('link');
  //   //shows the #more
    
  // }
</script>

</body>

</html>