<?php
require('../dbconnect.php');

session_start();

$products = isset($_SESSION['products']) ? $_SESSION['products'] : [];
?>
<?php
error_reporting(E_ALL & ~E_WARNING);
if (isset($_GET['id'])) {


  // URLからIDを取得
  $id = $_GET['id'];

  // 既存データの表示
  $stmt = $db->query("SELECT * FROM agents WHERE id = '$id'");
  $result = $stmt->fetch();
}
?>
<?php
// $stmt = $db->query('SELECT * FROM tag_categories');
$stmt = $db->query('SELECT tag_categories.id, tag_categories.tag_category FROM tag_categories INNER JOIN tag_options ON tag_categories.id = tag_options.category_id
                    UNION
                    SELECT sort_categories.tag_category_id, sort_categories.sort_category FROM sort_categories INNER JOIN sort_options ON sort_categories.tag_category_id = sort_options.category_id');

$categories = $stmt->fetchAll();
?>


<?php require('../_header.php'); ?>

<div class="container">
  <div class="heading_wrapper">
    <h2 class>エージェント詳細</h2>
  </div>
  <div class="info_wrapper">
    <div class="info_wrapper-name">
      <h2 class="info-name"><?= $result['agent_name'] ?></h2>
    </div>
    <div class="info_inner">
      <div class="info_inner-box">
        <div class="info_inner-leftbox">
          <img src="/craft_admin/images/<?= $result['agent_pic'] ?>" class="img_agent" alt="">
          <h4 class="table_heading">〈 エージェント情報 〉</h4>
          <table class="table">
            <?php foreach ($categories as $category) : ?>
              <tr>
                <td class="table_title"><?= $category['tag_category'] ?></td>
                <?php

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
        <div class="btn-fav">
          <?php if (empty($products)) { ?>
            <a href="/user/return_info.php?id=<?= $result['id'] ?>" id="<?= $result['id'] ?>" ; class="on">
              お気に入りに追加
            </a>
          <?php
          } elseif ($products[$result['id']]['agent_id'] == $result['id']) {
          ?>

            <a href="/user/delete_info.php?id=<?= $result['id'] ?>" class="off">
              お気に入り解除
            </a>
          <?php
          } else {
          ?>
            <a href="/user/return_info.php?id=<?= $result['id'] ?>" id="<?= $result['id'] ?>" class="on">
              お気に入り追加
            </a>
          <?php } ?>
        </div>
        <form action="/user/form.php" method="POST">
          <input type="submit" name="apply_id_single[<?= $result['id'] ?>]" value="申し込む" class="btn-register">
        </form>
      </div>
    </div>
  </div>
</div>

<?php require('../_footer.php'); ?>