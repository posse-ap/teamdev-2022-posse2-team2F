<?php 

session_start();

$output = '
<div class="table-responsive" id="order_table">
  <table class="table table-bordered table-striped">
    <tr>  
      <th width="30%">Agent Name</th>  
      <th width="50%">Info</th>  
      <th width="20%">Action</th>  
    </tr>
';

if(!empty($_SESSION["shopping_cart"]))
{
  foreach($_SESSION["shopping_cart"] as $keys => $values)
  {
  $output .= '
  <tr>
    <td>'.$values["agent_name"].'</td>
    <td>'.$values["agent_info"].'</td>
    <td><button name="delete" class="btn btn-danger btn-xs delete" id="'. $values["agent_id"].'">Remove</button></td>
  </tr>
  ';
  }
}
else
{
  $output .= '
  <tr>
    <td colspan="5" align="center">
    Your Cart is Empty!
    </td>
  </tr>
    ';
}
$output .= '</table></div>';

echo $output;