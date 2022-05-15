<?php
require('../dbconnect.php');

?>
<?php
error_reporting(0);
if (isset($_POST['search'])) {

  if (isset($_POST['tag_id']) && is_array($_POST['tag_id'])) {
    $search_tag = implode("%", $_POST['tag_id']);
  } else {
    header("Location: top.php");
  }
}
// echo $search_tag;

$stmt = $db->query("SELECT * FROM agents WHERE agent_tag LIKE '%$search_tag%'");
$results = $stmt->fetchAll();
$count = $stmt->rowCount();
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
<div class="top_container">
  <div class="top_container_title">
    <p class="top_container_title--all">エージェント一覧</p>
    <p class="top_container_title--info">
      <?= '当てはまるエージェント数：' . $count . '件' ?>
    </p>
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

      const apply_modal = document.getElementById('apply_modal');

      function modalOpen() {
        apply_modal.style.display = "none";
      }
    </script>


    <div class="top_container_results">
      <div class="top_container_results--agents" id="checkbox_count">


        <?php
        foreach ($results as $result) :
        ?>
          <div class="top_container_results--agents__agent">
            <div class="top_container_results--agents__agent--checkbox">

              <input class="checks" type="checkbox" id="<?= $result['id'] ?>" value="<?= $result['id'] ?>" name="apply_tag[]" onclick="modalOpen()">
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
                <a href="">詳細を見る</a>
                <input type="submit" value="申し込む">
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
  </form>
  <form action="result.php" method="POST">

    <div class="top_container_results--research">
      <h4>条件を変更する</h4>
      <div class="top_container_results--research__tags">
      <?php foreach ($categories as $category) : ?>
          <div class="top_container_results--research__tags--each">

            <h3>
              <?= $category['tag_category'] ?>
            </h3>
            <?php
            $stmt = $db->prepare("SELECT * FROM tag_options WHERE category_id = ?");
  
            $stmt->execute(array($category['id']));
            $tags = $stmt->fetchAll();
  
            ?>
  
  <div class="top_container_results--research__tags--each__option">
            <?php foreach ($tags as $tag) : ?>

                <input type="checkbox" name="tag[]" value="<?= $tag['tag_option'] ?>">
                <input type="checkbox" value="<?= $tag['id'] ?>" name="tag_id[]" id="<?= $tag['id'] . "1"?>">
                <label for="<?= $tag['id'] . "1"?>">
    
                  <?= $tag['tag_option']?>
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

<?php require('../_footer.php'); ?>