<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link href="../final_term/styles/search_bar.css" rel="stylesheet" type="text/css">
    <link href="../final_term/styles/body.css" rel="stylesheet" type="text/css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
    <title>首頁</title>
    <!-- 不用快取
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0"> -->
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
            document.getElementById("welcome").textContent= "嗨! "+Cookies.get('account_id') + "     ";
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
    //建立資料連接
    $link = create_connection();
    $sql="call demo()";
    $result = execute_sql("final_term", $sql, $link);
    $max = 4;
    $i = 0;
    //商品預覽
      echo  "<div class ='container_card'>";
      while ($row = mysql_fetch_row($result) and $i < $max){
      echo  "<div class='card'>";
      echo  "  <img src='$row[6]' style='width:100%;'>";
      echo  "  <h4 class='name'>$row[1]  $row[4]</h4>";
      echo  "  <p class='price'>&dollar;$row[2]</p>";
      echo  "  <p>$row[5]</p>";
      echo  "  <p><button onclick=\"addToCar('$row[0]')\">加入購物車</button></p>";
      echo  "</div>";
      $i++;
      }
      echo  "</div>";
      
      #購物車
      #https://w3c.hexschool.com/blog/8fcb1ead 來源
      echo "<script>
      function addToCar(item){
        if(Cookies.get('cartItem') == undefined){
          Cookies.set('cartItem', item, { path: '../final_term/cart.php' });
        }
        else{
          currentItem = Cookies.get('cartItem');
          if (currentItem === \"\"){
            currentItem = item;
          }
          else{
            currentItem = currentItem+','+item; 
          }
          Cookies.set('cartItem', currentItem, { path: '../final_term/cart.php' });
        }
        alert('加入購物車成功');
      }
      </script>";
  ?>
  </body>
</html>