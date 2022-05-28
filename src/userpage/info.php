<?php
require('../dbconnect.php');

session_start();

$products = isset($_SESSION['products']) ? $_SESSION['products'] : [];

$favorite_count = count($products);

$now = time();
?>
<?php
// error_reporting(E_ALL & ~ E_WARNING);
if (isset($_GET['id'])) {


  // URLからIDを取得
  $id = $_GET['id'];

  // 既存データの表示
  $stmt = $db->query("SELECT * FROM agents WHERE id = '$id'");
  $result = $stmt->fetch();
}
?>
<?php
$stmt = $db->query('SELECT * FROM tag_categories');

$categories = $stmt->fetchAll();
?>


<?php require('../_header.php'); ?>

<div class="container">
  <div class="heading_wrapper">
    <h2 class>エージェント詳細</h2>
    <div class="favorites">

        <p>♥お気に入り:</p>
        <p class="favorite_count"><?= $favorite_count ?>件</p>
        <a href="/user/cart.php" class="favorite">一覧を見る</a>
      </div>
  </div>
  <div class="info_wrapper">
    <div class="info_wrapper-name">
      <h2 class="info-name"><?= $result['agent_name'] ?></h2>
    </div>
    <div class="info_inner">
      <div class="info_inner-box">
        <div class="info_inner-leftbox">
          <div class="info_inner-leftbox_alerts">
            <!-- 申し込んだ人数 -->
          <?php
              $stmt = $db->query("SELECT student_id FROM students_agent INNER JOIN students_contact ON students_agent.student_id = students_contact.id WHERE agent_id = '$id' AND deleted_at IS NULL AND created_at >=(NOW()-INTERVAL 1 MONTH)");
              $student_num = $stmt->rowCount();
              $student_num = 30;
              ?>
              <?php
              if ($student_num >= 30) { ?>
                <div class="student_numbers">🔥申込者多数🔥</div>

              <?php } elseif ($student_num >= 10) { ?>
                <div class="student_numbers">⬆︎申込者急増！</div>

              <?php } else { ?>
              <?php } ?>
              <!-- ここまで -->
            
              <!-- 残り掲載期間 -->
              <?php
              $end_time = strtotime($result['end_display']);
              $start_time = strtotime($result['start_display']);
              $last_time = floor(($end_time - $now) / (60 * 60 * 24));

              ?>
              <?php
                      if ($last_time <= 30) { ?>
                        <div class="last_time">
                          ⌛️掲載終了まで
                          <?= "あと" . $last_time . "日!!" ?>
                        </div>

                      <?php } else { ?>
                      <?php } 
              ?>

          </div>
          <img src="/craft_admin/images/<?= $result['agent_pic'] ?>" class="img_agent" alt="">
          <h4 class="table_heading">〈 エージェント情報 〉</h4>
          <table class="table">
            <?php foreach ($categories as $category) : ?>
              <tr>
                <td class="table_title"><?= $category['tag_category'] ?></td>
                <?php
                $stmt = $db->prepare("SELECT * FROM tag_options WHERE category_id = ?");
                $stmt->execute(array($category['id']));
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
                  <?php endforeach; ?>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>

            <!-- <tr>
              <td class="table_title">運営会社の規模</td>
              <td class="table_content">大手</td>
            </tr>
            <tr>
              <td class="table_title">登録会社の規模</td>
              <td class="table_content">大手含む</td>
            </tr>
            <tr>
              <td class="table_title">累計利用者</td>
              <td class="table_content">90万人以上</td>
            </tr>
            <tr>
              <td class="table_title">面談拠点</td>
              <td class="table_content">北海道、宮城県、福岡県、埼玉県、東京都、神奈川県</td>
            </tr>
            <tr>
              <td class="table_title">オンライン面談の有無</td>
              <td class="table_content">無</td>
            </tr>
            <tr>
              <td class="table_title">公開求人数</td>
              <td class="table_content">無</td>
            </tr>
            <tr>
              <td class="table_title">強い分野</td>
              <td class="table_content">-</td>
            </tr>
            <tr>
              <td class="table_title">特別選考の有無
              </td class="table_content">
              <td class="table_content">有</td>
            </tr>
            <tr>
              <td class="table_title">イベントの有無(23卒/24卒)</td>
              <td class="table_content">-/現在公開なし</td>
            </tr>
              <td class="table_title">タイプ</td>
              <td class="table_content">総合</td>
            </tr> -->
          </table>
        </div>
        <div class="info_inner-rightbox">
          <h3 class="info_inner-rightbox_catchcopy"><?= $result['agent_title'] ?></h3>
          <p class="info_inner-rightbox_context"><?= $result['agent_info'] ?></p>
          <div class="info_inner-point">
            <h4 class="info_inner-point_title">ポイントまとめ</h4>
            <ul class="info_inner-point_list">
              <li><?= $result['agent_point1'] ?></li>
              <li><?= $result['agent_point2'] ?></li>
              <li><?= $result['agent_point3'] ?></li>
            </ul>
          </div>
        </div>
                  </div>
      <div class="btn">
          <?php if (empty($products)) { ?>
            <a href="/user/return_info.php?id=<?= $result['id'] ?>" id="<?= $result['id'] ?>" ; class="btn-fav">
              お気に入りに追加
            </a>
          <?php
          } elseif ($products[$result['id']]['agent_id'] == $result['id']) {
          ?>

            <a href="/user/delete_info.php?id=<?= $result['id'] ?>" class="btn-fav">
              お気に入り解除
            </a>
          <?php
          } else {
          ?>
            <a href="/user/return_info.php?id=<?= $result['id'] ?>" id="<?= $result['id'] ?>" class="btn-fav">
              お気に入り追加
            </a>
          <?php } ?>
        <form action="/user/form.php" method="POST">
          <input type="submit" name="apply_id_single[<?= $result['id'] ?>]" value="申し込む" class="btn-register">
        </form>
      </div>
    </div>
  </div>
</div>

<?php require('../_footer.php'); ?>