<?php
require('../dbconnect.php');
?>
<?php
// 既存データの表示
$stmt = $db->query("SELECT * FROM agents");
$results = $stmt->fetchAll();
?>
<!-- まとめて申し込む機能 -->
<form action="/user/form.php" method="POST">

  <?php
  foreach ($results as $result) :
  ?>
  <div>
    <?= $result['agent_name']?>
    <input type="checkbox" id="<?= $result['id']?>" value="<?= $result['id']?>" name="tag_apply[]">
  </div>
  <?php endforeach ;?>
  <input type="submit" name="apply" value="まとめて申し込む">
</form>