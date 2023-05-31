<!DOCTYPE html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
</head>
<body>
<?php
    require_once("dbtools.inc.php");
		
    $_account=$_POST["account"];
    $_passw=$_POST["password"];
    $illegal_string = array('\'','--',' ','#','=','"');
    foreach ($illegal_string as $thats_ilegal){
        if (strpos($_account, $thats_ilegal)!== false || strpos($_passw, $thats_ilegal) !== false) {
            echo "<script> 
            alert('帳號或密碼含有非法字元'); 
            window.location.replace('../final_term/login.php');
            </script>";
         }
    }
    if (empty($_account) || empty($_passw)){
        echo "<script> 
            alert('請輸入帳號或密碼'); 
            window.location.replace('../final_term/login.php');
            </script>";
    }
    $link = create_connection();
    $sql="call login('$_account','$_passw')";
    $result = execute_sql("final_term", $sql, $link);
    $row = mysql_fetch_row($result);

    if($row){
        echo "<script>
        alert('登入成功');
        Cookies.set('account_id', '$_account', { path: '../final_term/index.php' });
        window.location.replace('../final_term/index.php');
        </script>";
    }
    else{
        echo "<script> 
            alert('帳號或密碼錯誤'); 
            window.location.replace('../final_term/login.php');
            </script>";
    }
    mysql_free_result($result);
    mysql_close($link);
?>
</body>
</html>