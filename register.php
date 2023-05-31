<html>
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link href="../final_term/styles/body.css" rel="stylesheet" type="text/css">
    <link href="../final_term/styles/login.css" rel="stylesheet" type="text/css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
    <title>註冊</title>
  </head>
<body>

<script>
      function validateForm() {
      var _account = document.forms["Form1"]["account"].value;
      var _password = document.forms["Form1"]["password"].value;
      var _name = document.forms["Form1"]["name_"].value;
      var _email = document.forms["Form1"]["email_"].value;
      var _birth = document.forms["Form1"]["birth_"].value;
      var _phone = document.forms["Form1"]["phone_"].value;
      var _address = document.forms["Form1"]["address_"].value;

      if (_account == null||_account==""||_password == null||_password==""||_name == null||
      _name==""||_email == null||_email==""||_birth == null||_birth==""||_phone == null||_phone==""||_address == null||_address==""){
        alert("有欄位空白，請檢查");
        return false;
      }
      let illegal_string = ['\'','--',' ','#','=','"'];
      let address_illegal_string = ['\'','--','#','=','"'];
      for(let i =0;i<illegal_string.length;i++){
        if (_account.indexOf(illegal_string[i])!==-1){
            alert("帳號含有非法字元");
            return false;
        }
        if (_password.indexOf(illegal_string[i])!==-1){
            alert("密碼含有非法字元");
            return false;
        }
        if (_name.indexOf(illegal_string[i])!==-1){
            alert("姓名含有非法字元");
            return false;
        }
        if (_email.indexOf(illegal_string[i])!==-1){
            alert("電子信箱含有非法字元");
            return false;
        }
        if (_phone.indexOf(illegal_string[i])!==-1){
            alert("手機含有非法字元");
            return false;
        }
        if (_account.length <6){
            alert("帳號過短，需超過6字元");
            return false;
        }
        if (_password.length<6){
            alert("密碼過短，需超過6字元");
            return false;
        }
      }
      for(let i =0;i<address_illegal_string.length;i++){
        if (_address.indexOf(address_illegal_string[i])!==-1){
            alert("地址含有非法字元");
            return false;
        }
      }
      }
      function appearPW() {
        var x = document.getElementById("PASSW");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }}
</script>

<div class = "container">
    <form method="post" action="../final_term/registering.php" onsubmit="return validateForm()" name="Form1">
        <div><a href="../final_term/index.php"><img src="../final_term/image/logo.png" width="200px" height="auto"></a></div>
        <div><div>帳號 : </div><div><input type="text" name="account" size="25" placeholder="須超過6字元"></div>
        <div><div>密碼 : </div><div><input type="password" name="password" id ="PASSW" size="25" autocomplete ="off" placeholder="須超過6字元"><button type="button" onclick="appearPW()"><i class="fas fa-eye"></i></button></div>
        <div><div>姓名 : </div><div><input type="name" name="name_" size="25"></div>
        <div><div>Email : </div><div><input type="email" name="email_" size="25"></div>
        <div><div>生日 : </div><div><input type="date" name="birth_" size="25"></div>
        <div><div>手機 : </div><div><input type="tel" name="phone_" size="25" pattern="09\d{8}" placeholder="範例:0912345678"></div>
        <div><div>地址 : </div><div><input type="text" name="address_" size="25"></div>  
        <div class ="login"><div><input type="submit" value="註冊"></div>
    </form>
    </div>
</body>
</html>