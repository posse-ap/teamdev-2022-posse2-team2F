<?php
require('../dbconnect.php');

session_start();
?>
<?php
//タグカテゴリーの表示
$stmt = $db->query('SELECT * FROM tag_categories WHERE hide = 0');

$categories = $stmt->fetchAll();
?>
<?php
unset($_SESSION['search_id']);
unset($_SESSION['default_id']);
$result_id = array();
$counter = 0;
foreach ($categories as $category) {
  //当てはまったエージェントのid全て格納
  $result_ids = array();
  //タグの数が配列の数と同じかどうか
  $select_tag = "tag_" . $category['id'];
  $ids = $category['id'];
  $stmt = $db->query("SELECT category_id FROM tag_options WHERE category_id = $ids AND hide = 0");
  $tag_search = $stmt->fetchAll();
  $num = $stmt->rowCount();
  
  if (isset($_POST[$select_tag]) && is_array($_POST[$select_tag])){
    //配列の数カウント
    $selected = $_POST[$select_tag];
    // $tags = implode(',', $selected);
    $cnt = count($selected);
    //タグの数が配列の数と同じかどうか
    if ($num == 1 && $cnt == 1){
      $stmt = $db->query("SELECT id FROM agents WHERE hide = 0 AND agent_tag LIKE '%$selected[0]%'");
      $result_ids = $stmt->fetchALL(PDO::FETCH_COLUMN);
      $counter++;
    }
    elseif ($num == $cnt){
      $stmt = $db->query("SELECT id FROM agents WHERE hide = 0");
      $result_ids = $stmt->fetchALL(PDO::FETCH_COLUMN);
      $counter++;
    }
    elseif ($cnt >= 2) {
      $tags = implode(',', $selected);
      $split_tags = explode(',', $tags);
      foreach($split_tags as $split_tag){
        $stmt = $db->query("SELECT id FROM agents WHERE agent_tag LIKE '%$split_tag%' AND hide = 0");
        $pre_result = $stmt->fetchALL(PDO::FETCH_COLUMN);
        $count = $stmt->rowCount();
        $result_ids = array_merge($result_ids, $pre_result);
      }
      $counter++;
    }
    elseif ($cnt == 1){
      $stmt = $db->query("SELECT id FROM agents WHERE agent_tag LIKE '%$selected[0]%' AND hide = 0");
      $result_ids = $stmt->fetchALL(PDO::FETCH_COLUMN);
      $counter++;
    }
  }
  $result_id = array_merge($result_id, $result_ids);
}
// var_dump($result_id);
// $overlap = $counter - 1;
if($counter >= 2){
  //$counter個重複している要素を取り出す
  $id_results = array_unique(array_keys(array_count_values($result_id), $counter));
  // $id_results = array_filter(array_count_values($result_id), function($v){return --$v;});
}elseif($counter == 1){
  $id_results = $result_id;
}else{
  header("Location: top.php");
}

$_SESSION['default_id'] = $id_results;
// var_dump($_SESSION['default_id']);
// var_dump($_SESSION['search_id']);

//ここから人気順（申込人数多い順）
$search_ids = array();
foreach($id_results as $search_id){
  $stmt = $db->query("SELECT * FROM agents WHERE id = $search_id");
            $results = $stmt->fetchAll();
            foreach ($results as $result){
              $agent_id = $result['id'];
                      $stmt = $db->query("SELECT student_id FROM students_agent INNER JOIN students_contact ON students_agent.student_id = students_contact.id WHERE agent_id = '$agent_id' AND deleted_at IS NULL AND created_at >=(NOW()-INTERVAL 1 MONTH)");
                      
                      $student_num =
                      $stmt->rowCount();
                      $student_nums = array($agent_id => $student_num);
                      $search_ids += $student_nums;

            }
}

arsort($search_ids);
$search_id = array_keys($search_ids);
// var_dump($search_id);


$_SESSION['search_id'] = $search_id;
$_SESSION['sort_name'] = "人気順";

//ここから掲載期間短い順
// $search_ids = array();
//   foreach ($_SESSION['default_id'] as $search_id){
//     $stmt = $db->query("SELECT * FROM agents WHERE id = $search_id");
//     $results = $stmt->fetchAll();
//     foreach($results as $result){
//       $agent_id = $result['id'];
//       $end_time = strtotime($result['end_display']);
//       $student_nums = array($agent_id => $end_time);
//       $search_ids += $student_nums;
//     }
//   }
//   asort($search_ids);
//   $search_id = array_keys($search_ids);
//   // var_dump($search_id);

//   $_SESSION['search_id'] = $search_id;

header("Location: result.php");
?>