<?php
require('../dbconnect.php');

session_start();
?>
<?php
//タグカテゴリーの表示
$stmt = $db->query('SELECT * FROM tag_categories');

$categories = $stmt->fetchAll();
?>
<?php
unset($_SESSION['search_id']);
$result_id = array();
$counter = 0;
foreach ($categories as $category) {
  //当てはまったエージェントのid全て格納
  $result_ids = array();
  //タグの数が配列の数と同じかどうか
  $select_tag = "tag_" . $category['id'];
  $ids = $category['id'];
  $stmt = $db->query("SELECT category_id FROM tag_options WHERE category_id = $ids");
  $tag_search = $stmt->fetchAll();
  $num = $stmt->rowCount();
  
  if (isset($_POST["$select_tag"]) && is_array($_POST["$select_tag"])){
    //配列の数カウント
    $selected = $_POST["$select_tag"];
    $tags = implode(',', $selected);
    $cnt = count($selected);
    //タグの数が配列の数と同じかどうか
    if ($num == $cnt){
      $stmt = $db->query("SELECT id FROM agents");
      $result_ids = $stmt->fetchALL(PDO::FETCH_COLUMN);
      $counter++;
  }
    elseif ($cnt >= 2) {
      $split_tags = explode(',', $tags);
      foreach($split_tags as $split_tag){
        $stmt = $db->query("SELECT id FROM agents WHERE agent_tag LIKE '%$split_tag%'");
        $pre_result = $stmt->fetchALL(PDO::FETCH_COLUMN);
        $count = $stmt->rowCount();
        $result_ids = array_merge($result_ids, $pre_result);
      }
      $counter++;
    }elseif ($cnt == 1){
      $stmt = $db->query("SELECT id FROM agents WHERE agent_tag LIKE '%$selected[0]%'");
      $result_ids = $stmt->fetchALL(PDO::FETCH_COLUMN);
      $counter++;
    }
  }
  $result_id = array_merge($result_id, $result_ids);
}

if($counter >= 2){
  $id_results = array_unique(array_diff($result_id, array_keys(array_count_values($result_id), 1)));
  // $id_results = array_filter(array_count_values($result_id), function($v){return --$v;});
}elseif($counter == 1){
  $id_results = $result_id;
}else{
  header("Location: top.php");
}

$_SESSION['search_id'] = $id_results;

header("Location: result.php");
?>