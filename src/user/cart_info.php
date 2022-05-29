<?php
require('../dbconnect.php');

// 画像 & エージェント名表示用
// $stmt = $db->query("SELECT * FROM agents");
// $results = $stmt->fetchAll();

$id = $_GET['id'];

//現在時刻の取得
$now = time();

session_start();

$products = isset($_SESSION['products']) ? $_SESSION['products'] : [];

$count = count($products);

// if(isset($_POST['delete_name']))
// {
//   unset($_SESSION['products'][$_POST['delete_name']]);
// }

// 削除用
if (isset($_POST['cart_delete'])) {
  $delete_id = isset($_POST['delete_id']) ? htmlspecialchars($_POST['delete_id'], ENT_QUOTES, 'utf-8') : '';

  // 削除
  if ($delete_id != '') {
    unset($_SESSION['products'][$delete_id]);
  }
  header('Location: cart.php');
}


?>

<?php require('../_header.php'); ?>

<script>
    var positionY; /* スクロール位置のY座標 */
    var STORAGE_KEY = "scrollY"; /* ローカルストレージキー */
    /*
     * checkOffset関数: 現在のスクロール量をチェックしてストレージに保存
     */
    function checkOffset() {
      positionY = window.pageYOffset;
      localStorage.setItem(STORAGE_KEY, positionY);
    }
    /*
     * 起動時の処理
     *
     *      ローカルストレージをチェックして前回のスクロール位置に戻す
     */
    window.addEventListener("load", function() {
      // ストレージチェック
      positionY = localStorage.getItem(STORAGE_KEY);
      // 前回の保存データがあればスクロールする
      if (positionY !== null) {
        scrollTo(0, positionY);
      }
      // スクロール時のイベント設定
      window.addEventListener("scroll", checkOffset, false);
    });
  </script>

<div class="favorite_container">
  <div class="favorite_container_title">
    <h3>お気に入り一覧</h3>
    <p><?= 'お気に入り：' . $count . '件' ?></p>
  </div>
  <?php if($count == 0) :?>
    <div class="error">お気に入りにエージェントが登録されていません</div>
    <div class="cart-btn">
        <a href="/userpage/result.php" class="result_back">一覧に戻る</a>
        <a href="/userpage/info.php?id=<?=$id?>" class="info_back">詳細に戻る</a>

    </div>
  <?php else : ?>
  <form action="/user/form_cart.php" method="POST">
    <div class="apply_modal_cover">

      <div class="apply_modal">
        <p>
          チェックしたエージェント
        </p>

        <p class="check_count"></p>
        <p>件をまとめて</p>

        <input type="submit" name="apply_id" value="申し込む">
      </div>
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
        $stmt = $db->query("SELECT * FROM agents WHERE id = $id AND hide = 0");
        $results = $stmt->fetchAll();
        foreach ($results as $result) :
        ?>
          <div class="favorite_ind">
            <div class="checkbox">

              <input class="checks" type="checkbox" id="<?= $id ?>" value="<?= $id ?>" name="apply_tag[]">
              <label for="<?= $id ?>"></label>
            </div>
            <div class="favorite_ind_title">
              <h2><?= $product['agent_name'] ?></h2>
              <img src="../craft_admin/images/<?= $result['agent_pic'] ?>" alt="" />
              <form action="cart.php" method="post">
                <input type="hidden" name="delete_id" value="<?= $id; ?>">
                <a href="/user/delete_cart.php?id=<?= $result['id'] ?>">
                  お気に入りから削除
                </a>
              </form>
            </div>
            <div class="favorite_ind_info">
              <?php
              $id = $result['id'];
              $stmt = $db->prepare("SELECT agent_tag_options.id, agent_tag_options.agent_id, agents.agent_name, agent_tag_options.tag_option_id, tag_options.tag_option, tag_options.tag_color from agent_tag_options inner join tag_options on agent_tag_options.tag_option_id = tag_options.id inner join agents on agent_tag_options.agent_id = agents.id WHERE tag_options.hide = 0 AND agent_id = ?");

              $stmt ->execute(array($id));
              $agent_tags = $stmt->fetchAll();
              ?>
              <div class="tags">
                <?php foreach ($agent_tags as $agent_tag) : ?>

                  <p style="color: <?= $agent_tag['tag_color'] ?>;"><?= $agent_tag['tag_option'] ?></p>

                <?php endforeach; ?>
              </div>
              <div class="agent_info_cover">

                <div class="agent_info">
  
                  <?php $agent_title = nl2br($product['agent_title2']);
                  echo $agent_title; ?>
                </div>
              </div>
              <div class="agent_points_cover">
              <div class="agent_points">
                <ul>
                  <li>
                  <?= $product['agent_point1'] ?>
                  </li>
                  <li>
                  <?= $product['agent_point2'] ?>
                  </li>
                  <li>
                  <?= $product['agent_point3'] ?>
                  </li>
                </ul>
              </div>

              </div>
            </div>
            <div class="under_checkbox">
            </div>
            <div class="favorite_ind_buttons">
              <!-- 残り掲載期間 -->
              <?php
              $end_time = $end_time = strtotime($result['end_display']);
              $start_time = strtotime($result['start_display']);
              $last_time = floor(($end_time - $now) / (60 * 60 * 24));

              ?>
              <?php
                      if ($last_time <= 30) { ?>
                        <div class="last_time">
                          ⌛️掲載終了まで
                          <br>
                          <?= "あと" . $last_time . "日!!" ?>
                        </div>

                      <?php } else { ?>
                      <?php } 
              ?>

              <!-- 申し込んだ人数 -->
              <?php
              $stmt = $db->query("SELECT student_id FROM students_agent INNER JOIN students_contact ON students_agent.student_id = students_contact.id WHERE agent_id = '$id' AND deleted_at IS NULL AND created_at >=(NOW()-INTERVAL 1 MONTH)");
              $student_num = $stmt->rowCount();
              ?>
              <?php
              if ($student_num >= 30) { ?>
                <div class="student_numbers">申込者<br>🔥多数🔥</div>

              <?php } elseif ($student_num >= 10) { ?>
                <div class="student_numbers">⬆︎申込者急増！</div>

              <?php } else { ?>
              <?php } ?>
              <!-- ここまで -->


              <a href="/userpage/info.php?id=<?= $id ?>" target="_blank">詳細を見る</a>
              <input type="submit" value="申し込む" name="apply_id_single[<?= $result['id'] ?>]">

            </div>
          </div>
        <?php endforeach; ?>
      <?php endforeach; ?>

      <div class="cart-button">
        <a href="/userpage/result.php" class="result_back">一覧に戻る</a>
        <a href="/userpage/info.php?id=<?=$id?>" class="info_back">詳細に戻る</a>
      </div>
    </div>
  </form>
  <?php endif; ?>
</div>

<?php require('../_footer.php'); ?>