<?php 

// require('../dbconnect.php');

// $query = "SELECT * FROM agents ORDER BY id DESC";
// $statement = $db->prepare($query);

// // もしうまく execute できたら、、
// if($statement->execute())
// {
//   // 値を取得 
//   $result = $statement->fetchAll();
//   $output = '';
//   foreach($result as $row)
//   {
//     // 取得した結果を html のコードに埋め込み、これを jQuery 使って表示させる
//     $output .= '
//     <div class="col-md-3" style="margin-top:12px;margin-bottom:12px;">  
//       <div style="border:1px solid #ccc; border-radius:5px; padding:16px; height:300px;" align="center" id="agent_'.$row["id"].'">
//         <img src="../craft_admin/images/'.$row["agent_pic"].'" class="img-responsive" /><br />
//         <h4 class="text-info">
//           <div class="checkbox">
//             <label><input type="checkbox" class="select_product" data-agent_id="'.$row["id"].'" data-agent_name="'.$row["agent_name"].'" data-agent_info="'.$row["agent_info"] .'" value="">'.$row["agent_name"].'</label>
//           </div>
//         </h4>
//         <h4 class="text-danger">'.$row["agent_info"] .'</h4>
//       </div>
//     </div>
//     ';
//   }
//   echo $output;
// }

?>