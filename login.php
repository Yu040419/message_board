<!DOCTYPE html>

<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FEI WEN</title>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
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
  <script src="JS/all.js"></script>
</body>
</html>