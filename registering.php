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
    $_name=$_POST["name_"];
    $email=$_POST["email_"];
    $birth=$_POST["birth_"];
    $phone=$_POST["phone_"];
    $address=$_POST["address_"];

    $link = create_connection();
    $sql="call register_check('$_account','$email','$phone')";
    $result = execute_sql("final_term", $sql, $link);
    $row = mysql_fetch_row($result);
    if ($row[0] == FALSE){
        echo "<script> 
            alert('帳號已被註冊，請重新輸入'); 
            window.location.replace('../final_term/register.php');
            </script>";
        mysql_free_result($result);
        mysql_close($link);
    }
    else{
        mysql_free_result($result);
        mysql_close($link);
        $link = create_connection();
        $sql="call register('$_account','$_passw','$_name','$email','$birth','$phone','$address')";
        execute_sql("final_term", $sql, $link);
        mysql_close($link);
        echo "<script> 
                alert('註冊成功 !'); 
                Cookies.set('account_id', '$_account', { path: '../final_term/index.php' });
                window.location.replace('../final_term/index.php');
            </script>";
    }
?>
</body>
</html>