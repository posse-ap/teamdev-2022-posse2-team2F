<?php
require('../dbconnect.php');

//agent自動更新
// 画像 & エージェント名表示用
$stmt = $db->query("SELECT * FROM agents");
$agent_results = $stmt->fetchAll();

//現在時刻の取得
$now = time();
//掲載期間超えているエージェントは自動的に非表示に
foreach ($agent_results as $rlt) {
  $id = $rlt['id'];
  $end_time = strtotime($rlt['end_display']);
  $start_time = strtotime($rlt['start_display']);
  if ($now <= $start_time) {
    //掲載前=2
    $sql = "UPDATE agents
          SET hide = 2
          WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($id));
  } elseif ($now >= $end_time) {
    //掲載後=3
    $sql = "UPDATE agents
          SET hide = 3
          WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($id));
  }
}
?>
<?php
// 既存データの表示
$stmt = $db->query("SELECT * FROM agents WHERE hide = 0");
$results = $stmt->fetchAll();
$count = $stmt->rowCount();
//ここから申し込んだ人数出す
$search_ids = array();
foreach ($results as $result){
  $agent_id = $result['id'];
          $stmt = $db->query("SELECT student_id FROM students_agent INNER JOIN students_contact ON students_agent.student_id = students_contact.id WHERE agent_id = '$agent_id' AND deleted_at IS NULL AND created_at >=(NOW()-INTERVAL 1 MONTH)");
          
          $student_num =
          $stmt->rowCount();
          $student_nums = array($agent_id => $student_num);
          $search_ids += $student_nums;

}
arsort($search_ids);
$search_id = array_keys($search_ids);

?>
<?php
// タグ表示

//既存データの表示
$stmt = $db->query('SELECT * FROM tag_categories');

$categories = $stmt->fetchAll();

//現在時刻の取得
$now = time();

?>
<?php
// $stmt = $db->query('SELECT agent_tag_options.id, agent_tag_options.agent_id, agents.agent_name, agent_tag_options.tag_option_id, tag_options.tag_option, tag_options.tag_color from agent_tag_options inner join tag_options on agent_tag_options.tag_option_id = tag_options.id inner join agents on agent_tag_options.agent_id = agents.id');

// $agent_tags = $stmt->fetchAll();
?>
<?php require('../_header.php'); ?>
<div id="fullOverlay" onclick="OverlayOpen()"></div>
<div class="top_container">
  <h2>あなたにぴったりの<br>エージェントを見つけよう</h2>
  <button class="search-button" onclick="search_modalOpen()">絞りこむ</button>
  <button class="search-button_res" onclick="responsive_modalOpen()">絞り込む</button>
  <div id="search_modal">
    <form action="/userpage/search.php" method="POST">

      <div class="search_modal_container">
        <h4>詳細条件で比較</h4>
        <?php foreach ($categories as $category) : ?>
          <div class="search_modal_container--tag">
            <div class="tag_category">

              <div class="category_info" id="<?= 'div' . $category['id'] ?>">
                <?= $category['tag_category_desc'] ?>
              </div>
              <h3>
                <?= $category['tag_category'] ?>
              </h3>
              <p class="question" id="<?= 'button' . $category['id'] ?>">?</p>
              <p class="question_delete" id="<?= 'button_delete' . $category['id'] ?>">?</p>
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
            <!-- <div class="search_modal_container--tag__tags">
              <?php foreach ($tags as $tag) : ?>

                <input type="checkbox" name="tag[]" value="<?= $tag['tag_option'] ?>">
                <input type="checkbox" value="<?= $tag['id'] ?>" name="tag_id[]" id="<?= $tag['id'] ?>">
                <label for="<?= $tag['id'] ?>">

                  <?= $tag['tag_option'] ?>
                </label>

              <?php endforeach; ?>
            </div> -->
            <div class="search_modal_container--tag__tags">
              <?php foreach ($tags as $tag) : ?>

                <input type="checkbox" name="tag[]" value="<?= $tag['tag_option'] ?>">
                <input type="checkbox" value="<?= $tag['id'] ?>" name="<?= 'tag_' . $category['id'] . '[]' ?>" id="<?= $tag['id'] ?>">
                <label for="<?= $tag['id'] ?>">

                  <?= $tag['tag_option'] ?>
                </label>

              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
        <div class="search-buttons">
          <input class="search_modal_container--buttons" type="submit" name="search" value="この条件で検索する" />
          <input class="search_modal_container--button" type="submit" name="search" value="絞り込む">

        </div>
      </div>
    </form>

  </div>

  <div class="top_container_compare">
    <?= '全' . $count . '社を比較' ?>
  </div>
  <div class="top_container_agents">

    <?php foreach ($search_id as $id) : ?>
      <?php 
      $stmt = $db->query("SELECT * FROM agents WHERE id = $id");
      $res = $stmt->fetchAll();
      foreach($res as $result):
      ?>
      <!-- 始まりと終わりの時間確認 -->
      <div class="top_container_agents--all">
        <div class="top_container_agents--all__text">

          <h4><?= $result['agent_name'] ?></h4>
        </div>
        <div class="top_container_agents--all__flex">

          <div class="top_container_agents--all__flex--left">
            <img src="../craft_admin/images/<?= $result['agent_pic'] ?>" alt="" style="height: 180px;">

          </div>
          <div class="top_container_agents--all__flex--right">
            <!-- <p><?= $result['agent_tag'] ?></p> -->
            <div class="tag_container">

              <?php
              $id = $result['id'];
              $stmt = $db->query("SELECT agent_tag_options.id, agent_tag_options.agent_id, agents.agent_name, agent_tag_options.tag_option_id, tag_options.tag_option, tag_options.tag_color from agent_tag_options inner join tag_options on agent_tag_options.tag_option_id = tag_options.id inner join agents on agent_tag_options.agent_id = agents.id WHERE agent_id = '$id'");

              $agent_tags = $stmt->fetchAll();

              foreach ($agent_tags as $agent_tag) : ?>
                <p style="color: <?= $agent_tag['tag_color'] ?>;"><?= $agent_tag['tag_option'] ?></p>
              <?php endforeach; ?>
            </div>
            <!-- <section><?= $result['agent_info'] ?></section> -->
            <!-- <div class="top_container_agents--all--right_buttons">
              <button>詳細を見る</button>
              <button>申し込む</button>
            </div> -->
          </div>
        </div>

      </div>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </div>
</div>
<script>
  const search_modal = document.getElementById('search_modal');

  const overlay = document.getElementById('fullOverlay');

  function search_modalOpen() {
    search_modal.style.display = "block";
  };

  function responsive_modalOpen() {
    search_modal.style.display = "block";
    overlay.style.display = "block";
  }

  function OverlayOpen() {
      search_modal.style.display = "none";
      overlay.style.display = "none";
    }
</script>


<?php require('../_footer.php'); ?>