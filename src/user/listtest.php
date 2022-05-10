<?php

session_start();
require('../dbconnect.php');



// 画像 & エージェント名表示用
$stmt = $db->query("SELECT * FROM agents");
$results = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
  <head>
    <title>PHP Ajax Shopping Cart by using Bootstrap Popover</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <style>
    .popover
    {
        width: 100%;
        max-width: 800px;
    }
    </style>
  </head>
  <body>

    <!-- カート表示 -->
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-md-6">Cart Details</div>
          <div class="col-md-6" align="right">
            <button type="submit" name="clear_cart" id="clear_cart" class="btn btn-warning btn-xs">Clear</button>
          </div>
        </div>
      </div>
      <!-- カート表示をするためのdiv、ここのid取得 -->
      <div class="panel-body" id="shopping_cart">      

      </div>
    </div>

    <!-- 一覧表示 -->
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-md-6">Product List</div>
          <div class="col-md-6" align="right">
            <button type="button" name="add_to_cart" id="add_to_cart" class="btn btn-success btn-xs">Add to Cart</button>
            <!-- <button type="submit" name="add_to_cart" id="add_to_cart" class="btn btn-success btn-xs">Add to Cart</button> -->
          </div>
        </div>
      </div>
    <!-- 一覧表示をするためのdiv、ここのid取得 -->
    <!-- <div class="panel-body" id="display_item"> -->
    <form action="" method="POST">
      <?php foreach ($results as $result) : ?>
      <div style="border: 1px solid red;">
        <img src="../craft_admin/images/<?= $result['agent_pic'] ?>" alt="" style="height: 18.7vh">
        <h4 class="text-info">
          <div class="checkbox">
            <label><input type="checkbox" class="select_product" data-agent_id="<?= $result['id'] ?>" data-agent_name="<?= $result['agent_name'] ?>" data-agent_tag="<?= $result['agent_tag'] ?>" data-agent_info="<?= $result['agent_info'] ?>" value=""><?= $result['agent_name'] ?></label>
          </div>
        </h4>
        <input type="text" name="agent_tag" value="<?= $result['agent_tag'] ?>">
        <input type="text" name="agent_info" value="<?= $result['agent_info'] ?>">
      </div>
      <?php endforeach; ?>
    </form>




    </div>



  </body>

  <script>
    $(document).ready(function() {

      // load_product();

      load_cart_data();

      // function load_product()
      // {
      //   $.ajax({
      //   url:"fetch_item.php",
      //   method:"POST",
      //   success:function(data)
      //   {
      //     $('#display_item').html(data);
      //   }
      //   });
      // }

      function load_cart_data()
      {
        $.ajax({
        url:"fetch_cart.php",
        method:"POST",
        success:function(data)
        {
          $('#shopping_cart').html(data);
        }
        });
      }

      $('#add_to_cart').click(function(){
        var agent_id = [];
        var agent_name = [];
        var agent_tag = [];
        var agent_info = [];
        var action = "add";
        $('.select_product').each(function(){
          if($(this).prop('checked') == true)
          {
            agent_id.push($(this).data('agent_id'));
            agent_name.push($(this).data('agent_name'));
            agent_tag.push($(this).data('agent_tag'));
            agent_info.push($(this).data('agent_info'));
          }
        });
        // 問題はここから、情報がセッションに入らない
        if (agent_id.length > 0) 
        {
        $.ajax({
          url:"action.php",
          method:"POST",
          data:{id:agent_id, agent_name:agent_name, agent_tag:agent_tag, agent_info:agent_info, action:action},
          success:function(data)
          {
          // データを取得したら、チェックボックスのチェックを削除
          $('.select_product').each(function(){
            if($(this).prop('checked') == true)
            {
            $(this).attr('checked', false);
            }
          });

          load_cart_data();
          alert("item has been added into cart");
          }
        });
        }
        else
        {
        alert('select at least one item');
        }

      });

      

      
    });
  </script>