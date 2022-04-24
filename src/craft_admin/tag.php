<?php
session_start();
require('../dbconnect.php');


// タグカテゴリーを表示
$stmt = $db->query("SELECT * FROM tag_categories");
// $stmt = $db->query("SELECT * FROM tags;");
$categories = $stmt->fetchAll();




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

  <?php foreach ($categories as $category) : ?>
  <div style="background: lightblue; border: dashed red;">
    <p><?= $category['tag_category'] ?></p>
    <div id="no<?= $category['id'] ?>" class="none">
      <p>タグのカテゴリーの説明：</p>
      <p style="color: red"><?= $category['tag_category_desc'] ?></p>
      
      
      <p>タグの一覧：</p>

      <?php

      // タグ内容を表示
      $stmt = $db->prepare("SELECT * FROM tag_options WHERE category_id = ?");
      $stmt->execute(array($category['id']));
      $tags = $stmt->fetchAll();

      ?>

      <?php foreach ($tags as $tag) : ?>
      <p style="color: red"><?= $tag['tag_option'] ?></p>
      <?php endforeach; ?>
    </div>
    <button onclick="clickfunction(<?= $category['id'] ?>)" style="color: red">詳細</button>
  </div>
  <?php endforeach; ?>
  
  
  

  <?php require('../_footer.php'); ?>

<script>

//ボタンをクリックした時の処理
let clickfunction = function (id) {
      let more = document.getElementById(`no${id}`);
      if (more.classList.contains("none")) {
              more.classList.add("block");
              more.classList.remove("none");
          } else {
              more.classList.add("none");
              more.classList.remove("block");
          }
  }
// }

  
</script>

<style>
  .none {
    display: none;
  }

  .block {
    display: block;
  }
</style>

</body>

</html>