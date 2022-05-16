<?php

session_start();
if(isset($_POST["action"]))
{
  if($_POST["action"] == "add")
  {
    $agent_id = $_POST["agent_id"];
    $agent_name = $_POST["agent_name"];
    $agent_info = $_POST["agent_info"]; 
    $agent_tag = $_POST["agent_tag"]; 
    for($count = 0; $count < count($agent_id); $count++)
    {
      
    // array("Orange" => 100, "Apple" => 200, "Banana" => 300, "Cherry" => 400);
    // array_keys($array1);
    //[0] => Orange [1] => Apple [2] => Banana [3] => Cherry

      $cart_product_id = array_keys($_SESSION["shopping_cart"]);

      // $cart_product_id に $agent_id[$count] があるか確認
      if(in_array($agent_id[$count], $cart_product_id))
      {
        // $_SESSION["shopping_cart"][$agent_id[$count]]['product_quantity']++;
      }
      else
      {
      // $item_array = array(
      //   'agent_id'           =>     $agent_id[$count],  
      //   'agent_name'   =>     $agent_name[$count],
      //   'agent_tag'    =>     $agent_tag[$count],
      //   'agent_info'   =>     $agent_info[$count]
      // );

      // $_SESSION["shopping_cart"][$agent_id[$count]] = $item_array;
      
      
      $_SESSION['shopping_cart'] = array();
      $_SESSION['shopping_cart'][$agent_id[$count]] = array('agent_name' => $agent_name[$count], 'agent_tag' => $agent_tag[$count], 'agent_info' => $agent_info[$count]);

      
      }
    }
  }
}

?>