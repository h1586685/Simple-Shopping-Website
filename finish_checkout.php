<!DOCTYPE html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
    <title>交易中</title>
</head>
<?php
    require_once("dbtools.inc.php");
    $account_cookie=$_POST["AC"];
    $item_string=$_POST["IS"];
    $quan_string=$_POST["QS"];
    $total_amount=$_POST["TA"];
    $address=$_POST["shipping_address"];
    $phone=$_POST["phone"];
    $name=$_POST["_name"];
    $price=$_POST["PL"];

    $link = create_connection();
    $sql= "call new_order('$account_cookie','$item_string','$quan_string','$total_amount','$address','$phone','$name','$price')";
    execute_sql("final_term", $sql, $link);
    mysql_close($link);

    $cart_items = $_COOKIE["cartItem"];
    $cart_items = explode(",",$cart_items);
    
    $cart_items = array_count_values($cart_items);
    $item_list = explode(",",$item_string);

    for ($i = 0 ;$i <count($item_list);$i++){
        unset($cart_items[$item_list[$i]]);
    }

    $after_checkout ='';
    foreach($cart_items as $key =>$value){
        for($i =0 ;$i <$value;$i++){
            $after_checkout.= $key.",";
        }
    }
    $after_checkout = substr($after_checkout, 0, -1);

    $item_array = explode(",",$item_string);
    $quan_array = explode(",",$quan_string);
    for($i = 0 ;$i<count($item_array);$i++){
        $link = create_connection();
        $sql= "call sub_quan('$item_array[$i]','$quan_array[$i]')";
        execute_sql("final_term", $sql, $link);
        mysql_close($link);  
    }

    $after_checkout = rawurldecode($after_checkout);
    echo "<script>
          Cookies.set('cartItem', '$after_checkout', { path: '../final_term/cart.php' });
          document.location.href=\"../final_term/index.php\";
          alert('結帳完成')</script>";
?>
</html>