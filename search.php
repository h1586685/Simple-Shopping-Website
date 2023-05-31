<!DOCTYPE html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link href="../final_term/styles/style_table.css" rel="stylesheet" type="text/css">
    <link href="../final_term/styles/search_bar.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <title>搜尋結果</title>
  </head>
  <body>
  <header>
    
    <script>
      function validateForm() {
      var a = document.forms["Form1"]["search_name"].value;
        if (a == null || a == ""|| a== " ") {
          alert("請輸入名稱");
        return false;
        }
      }
    </script>

    <div class="container">
        <div class="logo"><a href="../final_term/index.php"><img src="../final_term/image/logo.png" width="90px" height="60px"></a></div>

        <div class="header_search">
          <form method="post" action="../final_term/search.php" onsubmit="return validateForm()" name="Form1">
              <div><input type="text" id = "search_box" name="search_name" class="form-control form-input" placeholder="搜尋商品...."></div>
              <div><input type="submit" class="search-btn" value =""></button></div>
          </form>
        </div>
        
        <div class ="but">
        <div><a href="../final_term/cart.php">購物車  <i class="fas fa-shopping-cart"></i></a></div>
        <div id= "before_login">
          <div>
            <a id = "login_in" href="../final_term/login.php">登入</a>
            <a id = "register" href="../final_term/register.php">註冊</a></div>
          </div>

        <div id= "after_login">
              <div><a id = "welcome"></a></div>
              <div><button id = "order_showing" onclick="javascript:location.href='../final_term/order_showing.php'">查詢訂單</button>
              <button id = "login_out" onclick = "login_out_function()">登出</button></div>
          </div>
        </div> 

        <script>
          let x = document.getElementById("before_login");
          let y = document.getElementById("after_login");
          function disappear_item() {
            x.remove();
            y.style.visibility = "visible";
            document.getElementById("welcome").textContent= "嗨! "+Cookies.get('account_id');
          }
          function appear_item() {
            x.style.visibility = "visible";
            y.remove();
          }
          function login_out_function(){
            Cookies.remove('account_id',{ path: '..final_term/index.php' });
            history.go(0);
            alert("登出成功");
          }
          if (Cookies.get('account_id') == undefined || Cookies.get('account_id') == "" ) appear_item();
          else disappear_item();
    </script>
    
    </div>
  </header>
<?php
 
    require_once("dbtools.inc.php");
		
    $search_name=$_POST["search_name"];
    if ($search_name ==null) die("Fail");

    //建立資料連接
    $link = create_connection();
    $sql="call search_goods('$search_name')";
    $result = execute_sql("final_term", $sql, $link);

    $num = mysql_num_rows($result);

    //顯示欄位名稱
    echo "<table border='0' align='center'>";
    //顯示記錄
    echo "<tbody>";
    while ($row = mysql_fetch_row($result))
    {
      echo "<tr>";      
        echo "<td><img src=\"$row[6]\" width=\"160px\" height=\"auto\"></td>";
        echo "<td><div><h3 align ='left'>$row[1]_$row[4]<h3></div><div align ='left'>$row[5]</div></td>";
        echo "<td><div><h3 align ='right' style=\"color:#cc0000;\">&dollar; $row[2]<h3></div>
              <div align ='right'><select id = \"#i_$row[0]_quantity\">";
        
        if ($row[3]<20){
          for($i = 1 ;$i <=$row[3];$i++){
              echo "<option value=\"$i\">$i</option>";
          }
        }
        else{
          for($i = 1 ;$i <=20;$i++){
              echo "<option value=\"$i\">$i</option>";
          }   
        }

        echo "</select><button onclick=\"addToCar('$row[0]')\">加入購物車</button> </div></td>";     
        echo "</tr>";     
    }
    echo "<tfoot><tr><td colspan ='5'>總共 $num 筆</td></tr></tfoot>";
    echo "</tbody>";
    echo "</table>" ;

    #購物車
    #https://w3c.hexschool.com/blog/8fcb1ead 來源
      echo "<script>
      function addToCar(item){
        var x = document.getElementById('#i_'+item+'_quantity').value;

        if(Cookies.get('cartItem') == undefined){
          let currentItem = '';
          for(let i =0;i<x;i++){
            if (i==0){
              currentItem = item;
            }
            else{
              currentItem = currentItem+','+item; 
            }
          }
          Cookies.set('cartItem', currentItem, { path: '../final_term/cart.php' });
        }

        else{
          currentItem = Cookies.get('cartItem');
          if (currentItem === \"\"){
            if (x ==1){
              currentItem = item;
            }
            else{
              for(let i =0;i<x;i++){
                if (i ==0){
                  currentItem = item;
                }
                else{
                  currentItem = currentItem+','+item; 
                }
              }
            }
          }
          else{
            for(let i =0;i<x;i++){
              currentItem = currentItem+','+item; 
            }
          }

          Cookies.set('cartItem', currentItem, { path: '../final_term/cart.php' });
        }
        alert('加入購物車成功');
      }
      </script>";
      mysql_free_result($result);
      mysql_close($link);
?>
  </body>
</html>