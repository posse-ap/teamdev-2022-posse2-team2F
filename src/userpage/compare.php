<?php
require('../dbconnect.php');

session_start();

// agent自動更新
// 画像 & エージェント名表示用
$stmt = $db->query("SELECT * FROM agents");
$agent_results = $stmt->fetchAll();


$products = isset($_SESSION['products']) ? $_SESSION['products'] : [];

// var_dump($products);

$favorite_count = count($products);

//現在時刻の取得
$now = time();

error_reporting(E_ALL & ~E_WARNING);

//並び替え
if (isset($_POST['sort_button'])) {
  //人気順
  if ($_POST['sort'] == "人気順") {
    unset($_SESSION['search_id']);
    //ここから人気順（申込人数多い順）
    $search_ids = array();
    foreach ($_SESSION['default_id'] as $search_id) {
      $stmt = $db->query("SELECT * FROM agents WHERE id = $search_id AND hide = 0");
      $results = $stmt->fetchAll();
      foreach ($results as $result) {
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
  } elseif ($_POST['sort'] == "掲載期間の短い順") {
    //掲載期間短い順
    unset($_SESSION['search_id']);
    $search_ids = array();
    foreach ($_SESSION['default_id'] as $search_id) {
      $stmt = $db->query("SELECT * FROM agents WHERE id = $search_id AND hide =0");
      $results = $stmt->fetchAll();
      foreach ($results as $result) {
        $agent_id = $result['id'];
        $end_time = strtotime($result['end_display']);
        $student_nums = array($agent_id => $end_time);
        $search_ids += $student_nums;
      }
    }
    asort($search_ids);
    $search_id = array_keys($search_ids);

    $_SESSION['search_id'] = $search_id;
  } elseif ($_POST['sort'] == "公開求人数が多い順") {
    //公開求人数が多い順
    unset($_SESSION['search_id']);
    $search_ids = array();
    foreach ($_SESSION['default_id'] as $search_id) {
      $stmt =  $db->prepare("SELECT sort_options.sort_option, agent_sort_options.agent_id FROM sort_options INNER JOIN agent_sort_options on sort_options.id = agent_sort_options.sort_option_id 
    WHERE category_id = 100 AND agent_id = ? AND hide = 0");
      $stmt->execute(array($search_id));
      $results = $stmt->fetchAll();
      foreach ($results as $result) {
        $agent_id = $result['agent_id'];
        if (is_numeric($result['sort_option'])) {

          $want_num = $result['sort_option'];
        } else {
          $want_num = 0;
        }
        $want_nums = array($agent_id => $want_num);
        $search_ids += $want_nums;
      }
    }
    arsort($search_ids);
    $search_id = array_keys($search_ids);

    $_SESSION['search_id'] = $search_id;
  } elseif ($_POST['sort'] == "利用者数が多い順") {
    unset($_SESSION['search_id']);
    $search_ids = array();
    foreach ($_SESSION['default_id'] as $search_id) {
      $stmt =  $db->prepare("SELECT sort_options.sort_option, agent_sort_options.agent_id FROM sort_options INNER JOIN agent_sort_options on sort_options.id = agent_sort_options.sort_option_id 
    WHERE category_id = 102 AND agent_id = ? AND hide = 0");
      $stmt->execute(array($search_id));
      $results = $stmt->fetchAll();
      foreach ($results as $result) {
        $agent_id = $result['agent_id'];
        if (is_numeric($result['sort_option'])) {

          $user_num = $result['sort_option'];
        } else {
          $user_num = 0;
        }
        $user_nums = array($agent_id => $user_num);
        $search_ids += $user_nums;
      }
    }
    arsort($search_ids);
    $search_id = array_keys($search_ids);

    $_SESSION['search_id'] = $search_id;
  }
}

$count = count($_SESSION['search_id']);


//既存データの表示
// $stmt = $db->query('SELECT * FROM tag_categories');

// $categories = $stmt->fetchAll();

$stmt = $db->query('SELECT tag_categories.id, tag_categories.tag_category FROM tag_categories INNER JOIN tag_options ON tag_categories.id = tag_options.category_id
                    UNION
                    SELECT sort_categories.tag_category_id, sort_categories.sort_category FROM sort_categories INNER JOIN sort_options ON sort_categories.tag_category_id = sort_options.category_id');

$categories = $stmt->fetchAll();

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
<div id="fullOverlay" onclick="OverlayOpen()"></div>
<div class="compare_container">
  <div class="compare_container_title">
    <h5>絞り込み結果</h5>
    <p class="compare_container_title--all">エージェント比較表</p>
    <p class="compare_container_title--info">
      <?= '当てはまるエージェント数：' . $count . '件' ?>
    </p>

    <div class="favorites">
      <p>♥お気に入り:</p>
      <p class="favorite_count"><?= $favorite_count ?>件</p>
      <a href="/user/cart.php" class="favorite">一覧を見る</a>
    </div>

  </div>

  <form action="compare.php" method="POST">
    <select name="sort" class="compare_sort_select">
      <?php
      // セレクトボックスの値を格納する配列
      $orders_list = array(
        "人気順",
        "掲載期間の短い順",
        "公開求人数が多い順",
        "利用者数が多い順",
      );

      // 戻ってきた場合
      if (isset($_POST['sort'])) {
        unset($_SESSION['sort']);
        $sort = $_POST['sort'];
        //セッションに保存
        $_SESSION['sort_name'] = $sort;
      }

      foreach ($orders_list as $value) {
        if ($value === $_SESSION['sort_name']) {
          // ① POST データが存在する場合はこちらの分岐に入る
          echo "<option value='$value' selected>" . $value . "</option>";
        } else {
          // ② POST データが存在しない場合はこちらの分岐に入る
          echo "<option value='$value'>" . $value . "</option>";
        }
      }



      ?>
    </select>
    <input type="submit" value="並び替え" name="sort_button" class="compare_sort_button">
  </form>
  <form action="/user/form.php" method="POST">
    <div class="compare_cont">
      <div class="compare">
        <!-- カテゴリーを左に一覧として表示 -->
        <div class="compare__label">
          <div class="compare__label--container">
            <div class="compare__label--container--name">
              <h4>エージェント名</h4>
            </div>
            <div class="compare__label--container--info">
              <table class="compare_table">
                <?php foreach ($categories as $category) : ?>
                  <tr>
                    <td class="table_title"><?= $category['tag_category'] ?></td>
                  </tr>
                <?php endforeach; ?>
              </table>
            </div>
          </div>
        </div>
        <div class="compare_scroll">
          <?php
          foreach ($_SESSION['search_id'] as $search_id) :
            $stmt = $db->query("SELECT * FROM agents WHERE id = $search_id");
            $results = $stmt->fetchAll();
            foreach ($results as $result) :
          ?>
              <?php
              $end_time = strtotime($result['end_display']);
              $start_time = strtotime($result['start_display']);
              ?>
              <div class="compare__agent">
                <div class="compare__agent--container">

                  <div class="compare__agent--container--name">
                    <h4><?= $result['agent_name'] ?></h4>
                  </div>


                  <div class="compare__agent--container--info">
                    <div class="compare__agent--container--info__img">

                      <img src="../craft_admin/images/<?= $result['agent_pic'] ?>" alt="" />
                    </div>
                    <div class="compare__agent--container--info__right--points">
                      <ul>
                        <li><?= $result['agent_point1'] ?></li>
                        <li><?= $result['agent_point2'] ?></li>
                      </ul>
                    </div>
                    <table class="compare_table">
                      <?php foreach ($categories as $category) : ?>
                        <tr>
                          <?php

                          // 申し込んだ人数
                          $agent_id = $result['id'];
                          $stmt = $db->query("SELECT student_id FROM students_agent INNER JOIN students_contact ON students_agent.student_id = students_contact.id WHERE agent_id = '$agent_id' AND deleted_at IS NULL AND created_at >=(NOW()-INTERVAL 1 MONTH)");

                          $student_num =
                            $stmt->rowCount();
                          // echo $student_num;
                          // ここまで 

                          // $stmt = $db->prepare("SELECT * FROM tag_options INNER JOIN agent_tag_options on tag_options.id = agent_tag_options.tag_option_id WHERE category_id = ? AND agent_id = ?");
                          $stmt = $db->prepare("SELECT tag_options.tag_option FROM tag_options INNER JOIN agent_tag_options on tag_options.id = agent_tag_options.tag_option_id 
                                      WHERE category_id = ? AND agent_id = ?
                                      UNION 
                                      SELECT sort_options.sort_option FROM sort_options INNER JOIN agent_sort_options on sort_options.id = agent_sort_options.sort_option_id 
                                      WHERE category_id = ? AND agent_id = ?");

                          $stmt->execute(array($category['id'], $result['id'], $category['id'], $result['id']));
                          $tags = $stmt->fetchAll();

                          $tag_num = $stmt->rowCount();

                          if ($tag_num >= 2) :
                          ?>
                            <?php
                            $get_tag = array();
                            foreach ($tags as $tag) {
                              $tag_option = $tag['tag_option'];
                              array_push($get_tag, "$tag_option");
                            }
                            $tag_container = implode('、 ', $get_tag);
                            ?>

                            <td class="table_content"><?= $tag_container ?></td>
                          <?php elseif ($tag_num == 1) : ?>
                            <?php foreach ($tags as $tag) : ?>
                              <td class="table_content"><?= $tag['tag_option'] ?></td>
                              <!-- <td><?= $student_num ?></td> -->
                            <?php endforeach; ?>
                          <?php elseif ($tag_num == 0) : ?>
                            <td class="table_content">-</td>
                          <?php endif; ?>
                        </tr>
                      <?php endforeach; ?>
                    </table>
                  </div>
                  <div class="compare__agent--container--buttons">
                    <div class="favorite_button">
                      <?php if (empty($products)) { ?>
                        <a href="/user/home_compare.php?id=<?= $result['id'] ?>" id="<?= $result['id'] ?>" ; class="on">
                          <p class="heart">♡</p>
                        </a>
                      <?php
                      } elseif ($products[$result['id']]['agent_id'] == $result['id']) {
                      ?>

                        <a href="/user/delete_result_compare.php?id=<?= $result['id'] ?>" class="off">
                          <p class="heart">♥</p>
                        </a>
                      <?php
                      } else {
                      ?>
                        <a href="/user/home_compare.php?id=<?= $result['id'] ?>" id="<?= $result['id'] ?>" class="on">
                          <p class="heart">♡</p>
                        </a>
                      <?php } ?>
                    </div>
                    <div class="otherbuttons">

                      <a href="info.php?id=<?= $result['id'] ?>" target="_blank">詳細</a>
                      <input type="submit" name="apply_id_single[<?= $result['id'] ?>]" value="申し込む">
                    </div>

                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endforeach; ?>

          </div>
        </div>
      </div>
  </form>

</div>
</div>

<script>
  const overlay = document.getElementById('fullOverlay');

  function OverlayOpen() {
    research_modal.style.display = "none";
    overlay.style.display = "none";
  }
</script>


<?php require('../_footer.php'); ?>