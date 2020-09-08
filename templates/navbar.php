<?php
 require_once('conn.php');
 include_once('utils.php');
?>

<header id="warning">本站為練習用網站，因教學用途刻意忽略資安的實作，註冊時請勿使用任何真實的帳號或密碼</header>
<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="./index.php">FeiWen</a>  
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
    <div class="navbar-nav">

      <?php
      if (empty($_SESSION['username'])) { ?>

        <a class="nav-item nav-link" href="./login.php">登入</a>
        <a class="nav-item nav-link" href="./register.php">註冊</a>
      <?php
      } else {
        $username = $_SESSION['username'];
        $user = getUserFromUsername($username);
        $nickname = $user['nickname'];?>
 
        <a class='nav-item nav-link' href='./profile.php'><?php echo escape($nickname) ?></a>
        <a class='nav-item nav-link' href='./handle_logout.php' >登出</a>
      <?php
      }
      ?>
    </div>
  </div>
</nav>