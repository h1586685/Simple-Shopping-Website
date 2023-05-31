<!DOCTYPE html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link href="../final_term/styles/login.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <title>登入</title>
</head>
<body>
    <script>
    function appearPW() {
        var x = document.getElementById("PASSW");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }}
    </script>
    
    <div class = "container">
    <form method="post" action="../final_term/progress.php">
        <div><a href="../final_term/index.php"><img src="../final_term/image/logo.png" width="200px" height="auto"></a></div>
        <div>帳號 : <input type="text" name="account" size="10"></div>
        <div>密碼 : <input type="password" name="password" id ="PASSW" size="10" autocomplete ="off"><button type="button" onclick="appearPW()"><i class="fas fa-eye"></i></button></div>
        <div class ="login"><div><input type="submit" value="登入" ></div>    <div><a href="../final_term/register.php">註冊<a></div></div>
    </form>
    </div>
</body>
</html>