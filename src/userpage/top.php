<?php
require('../dbconnect.php');

// 既存データの表示
$stmt = $db->query("SELECT * FROM agents WHERE hide = 0");
$results = $stmt->fetchAll();
$count = $stmt->rowCount();
?>
<?php
// タグ表示

//既存データの表示
$stmt = $db->query('SELECT * FROM tag_categories');

$categories = $stmt->fetchAll();



?>
<?php
// $stmt = $db->query('SELECT agent_tag_options.id, agent_tag_options.agent_id, agents.agent_name, agent_tag_options.tag_option_id, tag_options.tag_option, tag_options.tag_color from agent_tag_options inner join tag_options on agent_tag_options.tag_option_id = tag_options.id inner join agents on agent_tag_options.agent_id = agents.id');

// $agent_tags = $stmt->fetchAll();
?>
<?php require('../_header.php'); ?>
<div id="fullOverlay"></div>
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
            <h3>
              <?= $category['tag_category'] ?>
            </h3>
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
                <input type="checkbox" value="<?= $tag['id'] ?>" name="<?='tag_' . $category['id'] . '[]' ?>" id="<?= $tag['id'] ?>">
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
    <?='全' . $count . '社を比較'?>
  </div>
  <div class="top_container_agents">

    <?php foreach ($results as $result) : ?>
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
  </div>
</div>
<script>
  const search_modal = document.getElementById('search_modal');

  const overlay = document.getElementById('fullOverlay');

  function search_modalOpen() {
    search_modal.style.display = "block";
  };

  function responsive_modalOpen(){
    search_modal.style.display = "block";
    overlay.style.display = "block";
  }
</script>


<?php require('../_footer.php'); ?>