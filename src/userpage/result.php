<?php
require('../dbconnect.php');

session_start();

$products = isset($_SESSION['products']) ? $_SESSION['products'] : [];

// var_dump($products);

$favorite_count = count($products);
?>
<?php
//曖昧検索
// error_reporting(0);
// if (isset($_POST['search'])) {

//   if (isset($_POST['tag_id']) && is_array($_POST['tag_id'])) {
//     $search_tag = implode("%", $_POST['tag_id']);
//   } else {
//     header("Location: top.php");
//   }
// }

// $stmt = $db->query("SELECT * FROM agents WHERE agent_tag LIKE '%$search_tag%'");
// $results = $stmt->fetchAll();
// $count = $stmt->rowCount();

?>
<?php
// $stmt = $db->query('SELECT category_id FROM tag_options');

// $tag_options = $stmt->fetchAll();
?>
<?php
//タグカテゴリーの表示
// $stmt = $db->query('SELECT * FROM tag_categories');

// $categories = $stmt->fetchAll();
?>
<?php
// $result_id = array();
// $counter = 0;
// foreach ($categories as $category) {
//   //当てはまったエージェントのid全て格納
//   $result_ids = array();
//   //タグの数が配列の数と同じかどうか
//   $select_tag = "tag_" . $category['id'];
//   $ids = $category['id'];
//   $stmt = $db->query("SELECT category_id FROM tag_options WHERE category_id = $ids");
//   $tag_search = $stmt->fetchAll();
//   $num = $stmt->rowCount();
  
//   if (isset($_POST["$select_tag"]) && is_array($_POST["$select_tag"])){
//     unset($_SESSION['search_id']);
//     //配列の数カウント
//     $selected = $_POST["$select_tag"];
//     $tags = implode(',', $selected);
//     $cnt = count($selected);
//     //タグの数が配列の数と同じかどうか
//     if ($num == $cnt){
//       $stmt = $db->query("SELECT id FROM agents");
//       $result_ids = $stmt->fetchALL(PDO::FETCH_COLUMN);
//       $counter++;
//   }
//     elseif ($cnt >= 2) {
//       $split_tags = explode(',', $tags);
//       foreach($split_tags as $split_tag){
//         $stmt = $db->query("SELECT id FROM agents WHERE agent_tag LIKE '%$split_tag%'");
//         $pre_result = $stmt->fetchALL(PDO::FETCH_COLUMN);
//         $count = $stmt->rowCount();
//         $result_ids = array_merge($result_ids, $pre_result);
//       }
//       $counter++;
//     }elseif ($cnt == 1){
//       $stmt = $db->query("SELECT id FROM agents WHERE agent_tag LIKE '%$selected[0]%'");
//       $result_ids = $stmt->fetchALL(PDO::FETCH_COLUMN);
//       $counter++;
//     }
//   }else{
//     $id_results = $_SESSION['search_id'];
//   }
//   $result_id = array_merge($result_id, $result_ids);
// }

// if($counter >= 2){
//   $id_results = array_unique(array_diff($result_id, array_keys(array_count_values($result_id), 1)));
//   // $id_results = array_filter(array_count_values($result_id), function($v){return --$v;});
// }elseif($counter == 1){
//   $id_results = $result_id;
// }else{
//   header("Location: top.php");
// }
// var_dump($id_results);

// if(isset($_SESSION['search_id']) && is_array($_SESSION['search_id'])){
// $id_results = $_SESSION['search_id'];
// }
  
// var_dump($id_results);


$count = count($_SESSION['search_id']);
?>
<?php
// $stmt = $db->query('SELECT agent_tag_options.id, agent_tag_options.agent_id, agents.agent_name, agent_tag_options.tag_option_id, tag_options.tag_option, tag_options.tag_color from agent_tag_options inner join tag_options on agent_tag_options.tag_option_id = tag_options.id inner join agents on agent_tag_options.agent_id = agents.id');

// $agent_tags = $stmt->fetchAll();
?>
<?php
// タグ表示

//既存データの表示
$stmt = $db->query('SELECT * FROM tag_categories');

$categories = $stmt->fetchAll();

?>

<?php require('../_header.php'); ?>
<?php if($count == 0){ ?>
  <div class="no_match">
  <div class="top_container_title">
  <p class="top_container_title--all">エージェント一覧</p>
    <p class="top_container_title--info">
      <?= '当てはまるエージェント数：' . $count . '件' ?>
    </p>
  </div>
  <div class="no_match_message">
    <p>
    検索条件に一致するエージェントは見つかりませんでした。
    </p>
    <a href="/userpage/top.php">topに戻る</a>
  </div>
  </div>
<?php } else { ?>
<script>
  var positionY;                  /* スクロール位置のY座標 */
var STORAGE_KEY = "scrollY";    /* ローカルストレージキー */
/*
 * checkOffset関数: 現在のスクロール量をチェックしてストレージに保存
 */
function checkOffset(){
    positionY = window.pageYOffset;
    localStorage.setItem(STORAGE_KEY, positionY);
}
/*
 * 起動時の処理
 *
 *      ローカルストレージをチェックして前回のスクロール位置に戻す
 */
window.addEventListener("load", function(){
    // ストレージチェック
    positionY = localStorage.getItem(STORAGE_KEY);
    // 前回の保存データがあればスクロールする
    if(positionY !== null){
        scrollTo(0, positionY);
    }
    // スクロール時のイベント設定
    window.addEventListener("scroll", checkOffset, false);
});

</script>
<div class="top_container">
  <div class="top_container_title">
    <h5>絞り込み結果</h5>
    <p class="top_container_title--all">エージェント一覧</p>
    <p class="top_container_title--info">
      <?= '当てはまるエージェント数：' . $count . '件' ?>
    </p>
    <div class="favorites">
    
    <p>♡お気に入り:</p>
    <p class="favorite_count"><?= $favorite_count?>件</p>
    <a href="/user/cart.php" class="favorite">一覧を見る</a>
  </div>

  </div>
  <form action="/user/form.php" method="POST">
    <div class="apply_modal">
      <p>
        チェックしたエージェント
      </p>

      <p class="check_count"></p>
      <p>件をまとめて</p>

      <input type="submit" name="apply_id" value="申し込む">
    </div>
    <!-- ここからまとめて申し込むmodal -->
    <script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js?ver=1.12.2'></script>
    <script>
      $(function() {
        $('input:checkbox').change(function() {
          var cnt = $('#checkbox_count input:checkbox:checked').length;
          $('p.check_count').text(cnt);
        }).trigger('change');
      });
    </script>


    <div class="top_container_results">
      <div class="top_container_results--agents" id="checkbox_count">

        <?php
        foreach ($_SESSION['search_id'] as $search_id) :
          $stmt = $db->query("SELECT * FROM agents WHERE id = $search_id");
          $results = $stmt->fetchAll();
          foreach($results as $result):
        ?>
          <div class="top_container_results--agents__agent">
            <div class="top_container_results--agents__agent--checkbox">

              <input class="checks" type="checkbox" id="<?= $result['id'] ?>" value="<?= $result['id'] ?>" name="apply_tag[]">
              <label for="<?= $result['id'] ?>"></label>
            </div>
            <div class="top_container_results--agents__agent--container">

              <div class="top_container_results--agents__agent--container--name">
                <h4><?= $result['agent_name'] ?></h4>
              </div>

              <div class="top_container_results--agents__agent--container--info">
                <div class="top_container_results--agents__agent--container--info__img">

                  <img src="../craft_admin/images/<?= $result['agent_pic'] ?>" alt="" />
                </div>
                <div class="top_container_results--agents__agent--container--info__right">
                  <div class="top_container_results--agents__agent--container--info__right--tags">

                    <?php
                    $id = $result['id'];
                    $stmt = $db->query("SELECT agent_tag_options.id, agent_tag_options.agent_id, agents.agent_name, agent_tag_options.tag_option_id, tag_options.tag_option, tag_options.tag_color from agent_tag_options inner join tag_options on agent_tag_options.tag_option_id = tag_options.id inner join agents on agent_tag_options.agent_id = agents.id WHERE agent_id = '$id'");

                    $agent_tags = $stmt->fetchAll();
                    ?>
                    <?php foreach ($agent_tags as $agent_tag) : ?>
                      <p style="color: <?= $agent_tag['tag_color'] ?>;"><?= $agent_tag['tag_option'] ?></p>

                    <?php endforeach; ?>
                  </div>
                  <div class="top_container_results--agents__agent--container--info__right--exp">

                    <?= $result['agent_info'] ?>
                  </div>

                </div>

              </div>
              <div class="top_container_results--agents__agent--container--buttons">
                <div class="favorite_button">
                  <?php if (empty($products)){ ?>
                    <a href="/user/home.php?id=<?= $result['id'] ?>" id="<?= $result['id'] ?>"  ; class="on"><p class="heart">♡</p><p>追加</p></a>
                  <?php
                    }elseif ($products[$result['id']]['agent_id'] == $result['id']) {
                    ?>
                      
                      <a href="/user/delete_cart.php?id=<?=$result['id']?>" class="off"><p class="heart">♥</p><p>解除</p></a>
                    <?php
                    } else {
                    ?>
                      <a href="/user/home.php?id=<?= $result['id'] ?>" id="<?= $result['id'] ?>"  class="on"><p class="heart">♡</p><p>追加</p></a>
                    <?php } ?>
                </div>
                <div class="otherbuttons">

                  <a href="">詳細を見る</a>
                  <input type="submit" value="申し込む">
                </div>
                <!-- <input type="hidden" name="agent_name" value="<?= $result['agent_name'] ?>">
        <input type="hidden" name="agent_info" value="<?= $result['agent_info'] ?>">
        <input type="hidden" name="agent_tag" value="<?= $result['agent_tagname'] ?>">
        <input type="submit" name="favorite" class="btn-sm btn-blue" value="お気に入り追加"> -->
                
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <?php endforeach; ?>
      </div>

        <!-- トップに戻るボタン -->
        <a href="#" class="gotop">トップへ</a>
        <!-- ここからまとめて申し込み（下） -->
  <div class="under_apply_modal" id="under_apply_modal">
    <div class="under_overlay">
  
      <p>チェックしたエージェント</p>
      <p class="check_count"></p>
      <p>件をまとめて</p>
      <input type="submit" name="apply_id" value="申し込む"/>
    </div>
  </div>
<script>
  $(function() {
        $('input:checkbox').change(function() {
          var cnt = $('#checkbox_count input:checkbox:checked').length;
          if(cnt == 0){
            $('#under_apply_modal').css("display", "none");
          }else{
            $('#under_apply_modal').css("display", "block");
          };
      });
    });
</script>
  </form>

  <form action="result.php" method="POST">
    <div class="top_container_results--research">
      <h4>条件を変更する</h4>
      <div class="top_container_results--research__tags">
        <?php foreach ($categories as $category) : ?>
          <div class="top_container_results--research__tags--each">
            <div class="tag_category">
              <div class="category_info" id="<?= 'div' . $category['id']?>">
                <?= $category['tag_category_desc'] ?>
              </div>
              <!-- <div class="category_info_cover" id="<?= 'div_cover' . $category['id']?>"></div> -->

              <h3>
                <?= $category['tag_category'] ?>
              </h3>
              <p class="question" id="<?= 'button' . $category['id']?>">?</p>
              <p class="question_delete" id="<?= 'button_delete' . $category['id']?>">?</p>
              <!-- ここからはてなボタン（hover） -->
              <script>
                  // var elem = document.getElementById('<?= 'button' . $category['id']?>');
                  // var elem_delete = document.getElementById('<?= 'button_delete' . $category['id']?>');
                document.getElementById('<?= 'button' . $category['id']?>').addEventListener("click", function(){
                  document.getElementById('<?= 'div' . $category['id']?>').style.display = "block";
                  document.getElementById('<?= 'button' . $category['id']?>').style.display = "none";
                  document.getElementById('<?= 'button_delete' . $category['id']?>').style.display = "block";
                });

                document.getElementById('<?= 'button_delete' . $category['id']?>').addEventListener("click", function(){
                  document.getElementById('<?= 'div' . $category['id']?>').style.display = "none";
                  document.getElementById('<?= 'button' . $category['id']?>').style.display = "block";
                  document.getElementById('<?= 'button_delete' . $category['id']?>').style.display = "none";
                });
              </script>
            </div>
            <?php
            $stmt = $db->prepare("SELECT * FROM tag_options WHERE category_id = ?");

            $stmt->execute(array($category['id']));
            $tags = $stmt->fetchAll();

            ?>
            <div class="top_container_results--research__tags--each__option">
              <?php foreach ($tags as $tag) : ?>

                <input type="checkbox" name="tag[]" value="<?= $tag['tag_option'] ?>">
                <input type="checkbox" value="<?= $tag['id'] ?>" name="<?= 'tag_' . $category['id'] . '[]' ?>" id="<?= $tag['id'] . "1" ?>">
                <label for="<?= $tag['id'] . "1" ?>">

                  <?= $tag['tag_option'] ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
          


        <?php endforeach; ?>
      </div>


      <input class="re_search" type="submit" name="search" value="この条件で再度検索する">
    </div>
  </form>
</div>

              </div>
<?php }?>

<?php require('../_footer.php'); ?>