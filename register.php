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
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <div class="wrap">
    
    <?php
    include_once('templates/navbar.php');
    ?>
    
    <div class="register__title">會員註冊</div>
    <?php
    if (isset($_GET['errcode'])) {
      if ($_GET['errcode'] === '1') {
        echo "<div class='register__err'>請完整輸入暱稱、帳號及密碼</div>";
      } else if ($_GET['errcode'] === '2') {
        echo "<div class='register__err'>此帳號已有人使用</div>";
      }
    } 
    ?>
    <div class="register">
      <form class="register__block" action="./handle_register.php" method="POST" >
        <div>暱稱： <input class="register__nickname" placeholder="請輸入暱稱（必填）" name="nickname" type="text" required /></div>
        <div>帳號： <input class="register__username" placeholder="請輸入帳號（必填）" name="username" type="text" required /></div>
        <div>密碼： <input class="register__password" placeholder="請輸入密碼（必填）" name="password" type="password" required /></div>
        <input class="btn register__btn" type="button" value="註冊"/>
      </form>
    </div>
  </div>
  <script src="JS/all.js"></script>
</body>
</html>