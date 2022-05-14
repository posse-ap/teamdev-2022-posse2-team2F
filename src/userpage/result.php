<?php
require('../dbconnect.php');

?>
<?php
if(isset($_POST['search'])){

  if (isset($_POST['tag_id']) && is_array($_POST['tag_id'])) {
      $search_tag = implode("%", $_POST['tag_id']);
    }
    else{
      header("Location: top.php");
    }
} 
  // echo $search_tag;

  $stmt = $db->query("SELECT * FROM agents WHERE agent_tag LIKE '%$search_tag%'");
  $results = $stmt->fetchAll();
  $count = $stmt-> rowCount();
?>
<?php
// $stmt = $db->query('SELECT agent_tag_options.id, agent_tag_options.agent_id, agents.agent_name, agent_tag_options.tag_option_id, tag_options.tag_option, tag_options.tag_color from agent_tag_options inner join tag_options on agent_tag_options.tag_option_id = tag_options.id inner join agents on agent_tag_options.agent_id = agents.id');

// $agent_tags = $stmt->fetchAll();
?>
<?='当てはまるエージェント数：'.$count.'件'?>
<?php 
foreach($results as $result) :
?>
<?= $result['agent_name']?>
<img src="../craft_admin/images/<?= $result['agent_pic']?>" style="width :200px;" alt=""/>
<?php 
$id = $result['id'];
$stmt = $db->query("SELECT agent_tag_options.id, agent_tag_options.agent_id, agents.agent_name, agent_tag_options.tag_option_id, tag_options.tag_option, tag_options.tag_color from agent_tag_options inner join tag_options on agent_tag_options.tag_option_id = tag_options.id inner join agents on agent_tag_options.agent_id = agents.id WHERE agent_id = '$id'");

$agent_tags = $stmt->fetchAll();
?>
<?php foreach ($agent_tags as $agent_tag) : ?>
  <?= $agent_tag['tag_option'] ?>
  <?php endforeach; ?>
<?= $result['agent_info']?>
<?php endforeach ; ?>

