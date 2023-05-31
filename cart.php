<!DOCTYPE html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link href="../final_term/styles/style_table.css" rel="stylesheet" type="text/css">
    <link href="../final_term/styles/search_bar.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <title>購物車</title>
</head>
<body>
    <?php
    require_once("dbtools.inc.php");

    if (!empty($_COOKIE["cartItem"])){
        $temp = $_COOKIE["cartItem"];
        $product_list = explode(",",$temp);

        $product_count_ = array_count_values($product_list);
        $num = count($product_count_);
        $total_cost = 0; 

        echo "<div align = 'center' class=\"logo\"><a href=\"../final_term/index.php\"><img src=\"../final_term/image/logo.png\" width=\"90px\" height=\"60px\"></a></div>";
        echo "<table border='0' align='center'>";
        echo "<thead>
                <th><input type=\"checkbox\" onclick=\"select_all_item()\"></th>
                <th>商品</th>
                <th></th>
                <th>數量</th>
                <th>價格</th>
                <th></th>
              </thead>
        ";
        //顯示記錄
        echo "<tbody>";
        echo "<form method='post' action='checkout.php' onsubmit=\"return check_account_item()\">";
        foreach ($product_count_ as $id =>$quan){
            // echo "index = " .$id;
            // echo " and value = ".$quan;
            $link = create_connection();
            $sql= "call CHECK_QUAN('$id','$quan')";
            $result = execute_sql("final_term", $sql, $link);
            $row = mysql_fetch_row($result);
            if ($row[0] == FALSE){
                $quan = $row[1];
            }
            if ($quan >20){
                $quan =20;
            }
            mysql_free_result($result);
            mysql_close($link); 

            $link = create_connection();
            $sql= "call cart('$id')";
            $result = execute_sql("final_term", $sql, $link);
            $row = mysql_fetch_row($result);
            $single_total = $row[2]*$quan;
            $total_cost += $single_total;

            echo "<tr>";    
            echo "<td><div><input type='checkbox' name='items[]' value='$row[0]'></div></td>";
            echo "<td><div><img src=\"$row[4]\" width=\"auto\" height=\"120px\"></div></td>";
            echo "<td><div><h5 align ='left'>$row[1]_$row[3]<h5></div></td>";

            echo "<td><select id = \"#i_$row[0]_quantity\">";

            if ($row[5] < 20){
                for($i = 1;$i<=$row[5];$i++){
                    if ($i == $quan){
                        echo "<option  selected=\"selected\" value=\"$i\">$i</option>";
                    }
                    else{
                        echo "<option value=\"$i\">$i</option>";
                    }
                }
            }
            else{
                for($i = 1;$i<=20;$i++){
                    if ($i == $quan){
                        echo "<option selected=\"selected\" value=\"$i\">$i</option>";
                    }
                    else{
                        echo "<option value=\"$i\">$i</option>";
                    }
                }
            }

            echo "<input type='hidden' name='quantity_$row[0]' value='$quan'>";
            echo "<script>
                    var selectElement = document.getElementById('#i_$row[0]_quantity');
                    let currentItem_$row[0] = selectElement.value;
                    selectElement.addEventListener('change', (event) => {
                    var result = document.querySelector('input[name=\"quantity_$row[0]\"]');
                    result.value = event.target.value;

                    var cookieItems = Cookies.get('cartItem');
                    if (currentItem_$row[0] - event.target.value < 0){//加
                        let range = Math.abs(currentItem_$row[0] - event.target.value);
                        let increase_item_list = '';
                        for(let i = 0;i<range;i++){
                            if (i ==0){
                                increase_item_list = $row[0] + ','; 
                            }
                            else{
                                increase_item_list += $row[0] + ','; 
                            }
                        }
                        increase_item_list = increase_item_list.substring(0, increase_item_list.length - 1);
                        cookieItems += ','+increase_item_list;
                        Cookies.set('cartItem', cookieItems, { path: \"../final_term/cart.php\" });
                        history.go(0);
                    } 
                    
                    else{ //減
                        let range = Math.abs(currentItem_$row[0] - event.target.value);
                        let items = cookieItems.split(',');
                        for(let i = 0;i<range;i++){
                            items.splice(items.lastIndexOf('$row[0]'),1);
                        }
                        items.join(',');
                        Cookies.set('cartItem', items, { path: \"../final_term/cart.php\" });
                        history.go(0);
                    }
                    });
                </script>";

            echo "</select></td>";

            echo "<input type='hidden' name='price_$row[0]' value='$row[2]'>";
            echo "<td><div><h4 align ='center' style=\"color:#cc0000;\">&dollar; $single_total <h4></div>";
            echo "<td><div><button type=\"button\" onclick=\"del_good('$id')\">刪除</button> </div></td>";   
            echo "</tr>";
            mysql_free_result($result);
            mysql_close($link);      
            }
            echo "<td colspan=\"3\"></td>
                 <td align = 'center'>$num 種商品</td><td><h3 align ='center' style=\"color:#cc0000;\">&dollar; $total_cost<h3></td>";
            echo "<td><div><button>結帳</button> </div></td>";
            echo "</form>";   
            echo "</tbody>";
            echo "</table>" ;

            echo "<script>
            function del_good(id) {
                let cookieItems = Cookies.get('cartItem');
                if (!cookieItems) return;
            
                let items = cookieItems.split(',')
                    .filter(function(item) { return item != id; })
                    .join(',');
            
                Cookies.set('cartItem', items, { path: \"../final_term/cart.php\" });
                history.go(0);
                alert('刪除成功');
            }
            function check_account_item(){
                let cookie_account = Cookies.get('account_id');
                let checked_item = document.querySelectorAll(\"input[name^='items[']:checked\");
                if (cookie_account == undefined){
                    alert('請登入帳號');
                    window.location.replace('../final_term/login.php');
                    return false;
                }
                if (checked_item.length == 0){
                    alert('請選擇商品');
                    window.location.replace('../final_term/cart.php');
                    return false;
                }
            }
            function select_all_item(){
                let checked_item = document.querySelectorAll(\"input[name^='items[']\");
                for (let i = 0; i<checked_item.length; i++){
                    if (checked_item[i].checked === false){
                        checked_item[i].checked = true;
                    }
                    else{
                        checked_item[i].checked = false;
                    }
                }
            }
            </script>";
    }
    else{
            echo "<script>
            alert('購物車內沒有物品喔!趕快去選購');
            window.location.replace('../final_term/index.php');
            </script>";
    }
    ?>
</body>
</html>