<!DOCTYPE html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link href="../final_term/styles/style_table.css" rel="stylesheet" type="text/css">
    <link href="../final_term/styles/search_bar.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <title>結帳</title>
</head>
<body>
<?php
    require_once("dbtools.inc.php");
    
    $items_=$_POST["items"];
    $account_cookie = $_COOKIE["account_id"];
    
    // if (empty($items_)){
    //     echo "<script>
    //         alert('請選擇商品');
    //         window.location.replace('../final_term/cart.php');
    //     </script>";
    // }

    $item_string = implode(",",$items_);
    $num = 0;
    $quanlist = array();
    $pricelist = array();

    foreach($items_ as $id =>$value){
        $temp = $_POST["quantity_".$value];
        $quanlist[$value] = $temp;
        $temp = $_POST["price_".$value];
        $pricelist[$value] = $temp;
    }

    // $temp = count($quanlist);
    // foreach($quanlist as $id =>$value){
    //     echo "<script>
    //     alert($id )</script>";
    // }
    $quan_string = implode(",",$quanlist);
    $price_string = implode(",",$pricelist);

    for ($i = 0 ;$i <count($items_);$i++){
        $num += $quanlist[$items_[$i]];
    }

    echo "<div align = 'center' class=\"logo\"><a href=\"../final_term/index.php\"><img src=\"../final_term/image/logo.png\" width=\"90px\" height=\"60px\"></a></div>";
        echo "<table border='0' align='center'>";
        echo "<thead>
                <th></th>
                <th>商品</th>
                <th></th>
                <th>數量</th>
                <th>價格</th>
              </thead>
        ";

        $total_cost = 0; 
        //顯示記錄
        echo "<tbody>";
        echo "<form method='post' action='finish_checkout.php'>";
        echo "<input type='hidden' name='AC' value='$account_cookie'>";
        echo "<input type='hidden' name='IS' value='$item_string'>";
        echo "<input type='hidden' name='QS' value='$quan_string'>";
        echo "<input type='hidden' name='PL' value='$price_string'>";
        foreach($items_ as $a => $id){
            // echo "index = " .$id;
            // echo " and value = ".$quan;
            $link = create_connection();
            $sql= "call cart('$id')";
            $result = execute_sql("final_term", $sql, $link);
            $row = mysql_fetch_row($result);
            $single_total = $row[2]*$quanlist[$id];
            $total_cost += $single_total;

            echo "<tr>";    
            echo "<td></td><td><div><img src=\"$row[4]\" width=\"auto\" height=\"120px\"></div></td>";
            echo "<td><div><h5 align ='left'>$row[1]_$row[3]<h5></div></td>";
            echo "<td><div align ='center'>  $quanlist[$id] </div></td>";
            echo "<td><div><h4 align ='center' style=\"color:#cc0000;\">&dollar; $single_total <h4></div>";
            echo "</tr>";
            mysql_free_result($result);
            mysql_close($link);      
            }
        echo "<tr>
              <td colspan = \"4\" class = \"l\">$num 件商品</td><td><h3 align ='center' style=\"color:#cc0000;\">&dollar; $total_cost<h3></td></tr>";
        echo "<input type='hidden' name='TA' value='$total_cost'></tr>";

        $link = create_connection();
        $sql= "call order_detail('$account_cookie')";
        $result = execute_sql("final_term", $sql, $link);
        $row = mysql_fetch_row($result);
        echo "<tr><td colspan = \"5\" class = \"l\">收件人 :
            <input style=\"width:200px\" type = \"text\" value = \"$row[2]\" name = \"_name\"></td></tr>";
        echo "<tr><td colspan = \"5\" class = \"l\">地址 : 
            <input style=\"width:200px\" type = \"text\" value = \"$row[0]\" name = \"shipping_address\"></td></tr>";
        echo "<tr><td colspan = \"5\" class = \"l\">連絡電話 : 
            <input style=\"width:200px\" type = \"text\" value = \"$row[1]\" name = \"phone\"></td></tr>";
        mysql_free_result($result);
        mysql_close($link);  

        echo "<tr><td colspan = \"5\" class = \"l\"><button name =\"submit\">確認結帳</button></td></tr>";
        echo "</form>";   
        echo "</tbody>";
        echo "</table>" ;
?>
</body>
</html>