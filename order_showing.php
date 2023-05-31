<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link href="../final_term/styles/style_table_showing_orider.css" rel="stylesheet" type="text/css">
    <link href="../final_term/styles/search_bar.css" rel="stylesheet" type="text/css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <title>訂單查詢</title>
  </head>
<body>
    <div align = 'center' class="logo"><a href="../final_term/index.php"><img src="../final_term/image/logo.png" width="90px" height="60px"></a></div>
    <?php
        require_once("dbtools.inc.php");
        if (empty($_COOKIE["account_id"])){
            echo "<script>
            alert('請登入會員');
            window.location.replace('../final_term/login.php');
            </script>";
        }
        else{
        $account = $_COOKIE["account_id"];
        $link = create_connection();
        $sql= "call order_showing('$account')";
        $result = execute_sql("final_term", $sql, $link);

        $item_string = '';
        $order_id_string = '';
        while($row = mysql_fetch_row($result)){
            $day_date = strtotime($row[1]);
            $day_date = $newDate = date('Y-m-d',$day_date);
            $order_id_string .= $row[0].",";
            echo "<table align=\"center\">";
            echo "<th class =\"x\"><div>訂單編號 :$row[0]</div> <div>建立時間 : $day_date</div></th>";
            echo "</table>";

            echo "<table align=\"center\" id=\"table_$row[0]\">";
            echo "<thead>
                  <th colspan=\"2\" width =\"130\" class =\"r\">商品</th>
                  <th width =\"100\">數量</th>
                  <th width =\"100\">價格</th>
                  </thead>";
            echo "<tbody>";

        
        $items_list = explode(",",$row[2]);
        $quan_list = explode(",",$row[3]);
        $price_list = explode(",",$row[4]);

        for($i =0 ; $i<count($items_list);$i++){
            $single_amount_price=$quan_list[$i]*$price_list[$i];
            echo  "<tr>
                   <td class =\"r\" colspan=\"2\"><a id =\"#$row[0]#item_$items_list[$i]\"></a></td>
                   <td>$quan_list[$i]</td>
                   <td width=\"150\">$single_amount_price</td></tr>";

            if (strpos($item_string, $items_list[$i]) == false){
                $item_string .= $items_list[$i].",";
            }
        }
            echo "<tr><td colspan=\"3\" class =\"l\"><b>總金額 :</b></td><td><b>$row[5]</b></td></tr>";
            echo "<tr></tr>
                  <tr><td colspan=\"4\" class =\"l\"><b>收件人 : </b><b>$row[6]</b></td></tr>";
            echo "<tr><td colspan=\"4\" class =\"l\"><b>收件地址 : </b><b>$row[7]</b></td></tr>";
            echo "<tr><td colspan=\"4\" class =\"l\"><b>連絡電話 : </b><b>$row[8]</b></td></tr>";
            echo "</tbody>";
            echo "</table>";
        }
        mysql_free_result($result);
        mysql_close($link);

        $item_string = substr($item_string, 0, -1);
        $order_id_string = substr($order_id_string, 0, -1);

        $temp = '';
        $item_id = explode(",",$item_string);
        $item_id = array_count_values($item_id);

        // echo "<script>alert('$item_string')</script>";
        foreach($item_id as $id => $key){
            $temp .= $id . ",";
        }
        $item_string = substr($temp, 0, -1);

        $item_string = explode(",",$item_string);
        $order_id_string = explode(",",$order_id_string);
        foreach($order_id_string as $order){
            foreach($item_string as $item){
                $link = create_connection();
                $sql= "call short_item_detail('$item')";
                $result = execute_sql("final_term", $sql, $link);
                $row = mysql_fetch_row($result);
                // echo "<script>alert('$row[0]')</script>";

                $a_id = "#$order#item_$item";
                $good_name = $row[0] . "_" . $row[1];
                echo "<script>
                        var x = document.getElementById('$a_id');
                        if (x !=null){
                            x.innerHTML = '$good_name'; 
                        }
                      </script>";
                mysql_free_result($result);
                mysql_close($link);
            }
        }
    }
    ?>
</body>
</html>