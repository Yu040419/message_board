<!DOCTYPE html>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FeiWen</title>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
  <link rel="stylesheet" href="CSS/normalize.css">
  <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
  <div class="wrap">

    <?php
    include_once('templates/navbar.php');
    ?>
    
    <div class="login__title">會員登入</div>
    <div class="login">
      <form class="login__block" action="./handle_login.php" method="POST" >
        <div>帳號： <input class="login__username" placeholder="請輸入帳號（必填）" name="username" type="text" required /></div>
        <div>密碼： <input class="login__password" placeholder="請輸入密碼（必填）" name="password" type="password" required /></div>
        <input class="btn login__btn" type="button" value="登入"/>
      </form>
    </div>
  </div>
  <script src="all.js"></script>
</body>
</html>