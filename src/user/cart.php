<?php
require('../dbconnect.php');

// 画像 & エージェント名表示用
// $stmt = $db->query("SELECT * FROM agents");
// $results = $stmt->fetchAll();


session_start();

$products = isset($_SESSION['products'])? $_SESSION['products']:[];

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

<div class="container">
    <div class="wrapper-title">
        <h3>MY CART</h3>
        <p>カート</p>
    </div>
    <div class="cartlist">
      <table class="cart-table">
        <thead>
          <tr>
            <th>エージェント名</th>
            <th>エージェントタグ</th>
            <th>エージェント情報</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $id => $product) : ?>
          <tr>
            <td label="商品名："><?= $product['agent_name']?></td>
            <td label="タグ：" class="text-right"><?= $product['agent_tag'] ?></td>
            <td label="情報：" class="text-right"><?= $product['agent_info'] ?></td>
            <td>
              <form action="cart.php" method="post">
                <input type="hidden" name="delete_id" value="<?= $id; ?>">
                <button type="submit" name="cart_delete" class="">削除</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="cart-btn">
        <a href="/userpage/result.php">戻る</a>
      </div>
    </div>
  </div>