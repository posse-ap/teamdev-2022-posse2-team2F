<?php
require('../dbconnect.php');

// 画像 & エージェント名表示用
// $stmt = $db->query("SELECT * FROM agents");
// $results = $stmt->fetchAll();



session_start();

$products = isset($_SESSION['products'])? $_SESSION['products']:[];

$count = count($products);

// if(isset($_POST['delete_name']))
// {
//   unset($_SESSION['products'][$_POST['delete_name']]);
// }

// 削除用
if(isset($_POST['cart_delete'])){
$delete_id = isset($_POST['delete_id'])? htmlspecialchars($_POST['delete_id'], ENT_QUOTES, 'utf-8') : '';

// 削除
if ($delete_id != '') {
  unset($_SESSION['products'][$delete_id]);
}
header('Location: cart.php');
}


?>

<?php require('../_header.php'); ?>

<div class="favorite_container">
    <div class="favorite_container_title">
        <h3>お気に入り一覧</h3>
        <p><?= 'お気に入り：' . $count . '件'?></p>
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
          var cnt = $('#checked_count input:checkbox:checked').length;
          $('p.check_count').text(cnt);
        }).trigger('change');
      });
    </script>
    <!-- ここまで -->
    <div class="favorite_list" id="checked_count">
          <?php foreach ($products as $id => $product) : ?>
          <?php 
          $stmt = $db->query("SELECT * FROM agents WHERE id = $id"); 
          $results = $stmt->fetchAll();
          foreach($results as $result):
          ?>
            <div class="favorite_ind">
            <div class="checkbox">

              <input class="checks" type="checkbox" id="<?= $id ?>" value="<?= $id?>" name="apply_tag[]">
                  <label for="<?= $id ?>"></label>
            </div>
            <div class="favorite_ind_title">
              <h2><?= $product['agent_name']?></h2>
              <img src="../craft_admin/images/<?= $result['agent_pic'] ?>" alt=""/>
              <form action="cart.php" method="post">
                <input type="hidden" name="delete_id" value="<?= $id; ?>">
                <button type="submit" name="cart_delete" class="">お気に入りから削除</button>
              </form>
            </div>
            <div class="favorite_ind_info">
            <?php
                    $id = $result['id'];
                    $stmt = $db->query("SELECT agent_tag_options.id, agent_tag_options.agent_id, agents.agent_name, agent_tag_options.tag_option_id, tag_options.tag_option, tag_options.tag_color from agent_tag_options inner join tag_options on agent_tag_options.tag_option_id = tag_options.id inner join agents on agent_tag_options.agent_id = agents.id WHERE agent_id = '$id'");

                    $agent_tags = $stmt->fetchAll();
                    ?>
                      <div class="tags">
                    <?php foreach ($agent_tags as $agent_tag) : ?>

                        <p style="color: <?= $agent_tag['tag_color'] ?>;"><?= $agent_tag['tag_option'] ?></p>
                        
                        <?php endforeach; ?>
                      </div>
              <?= $product['agent_info']?>
            </div>
            <div class="favorite_ind_buttons">
              <div class="buttons">

                <a href="">詳細を見る</a>
                <input type="submit" value="申し込む">
              </div>
            </div>
              </div>
              <?php endforeach; ?>
          <?php endforeach; ?>
    
      <div class="cart-btn">
        <a href="/userpage/result.php">一覧に戻る</a>
      </div>
    </div>
    </form>
  </div>

  <?php require('../_footer.php'); ?>