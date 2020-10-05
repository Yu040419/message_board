<?php
session_start();
require_once('conn.php');
include_once('utils.php');
?>
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
    
    include_once('templates/navbar.php'); ?>

    <div class='container'>
    <?php
    if (empty($_SESSION['username'])) { ?>
      <div class='remind row'>
        <div class='remind__text col-md-7'>請登入或註冊</div>
      </div>
    <?php
    } else { ?>

    <div class='my__profile row flex-md-column align-items-center'>
      <div class='my__profile--title col-md-6'>個人資料</div>
      <div class='my__profile--border col-md-6'>
        <div class='my__profile--block'>
          <div class='my__profile--nickname'>暱稱：
            <span name='nickname' class='my__profile--update--nickname'><?php echo escape($nickname) ?>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="ml-1 bi bi-pencil-square nickname edit__img" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <title>編輯暱稱</title>
              <path class="nickname" d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
              <path class="nickname" fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
            </svg>
            </span>
          </div>
          <form class='my__nickname--update hidden' action ='./handle_update.php' method='POST' >
            <input class='my__profile--input' name='nickname' type='text' placeholder='請輸入新暱稱' required/>
            <input type='button' class='submit nickname__btn' value='送出' />
          </form>
        </div>
        <div class='my__profile--block'>
          <div class='my__profile--username'>帳號：
            <span name='nickname' class='my__profile--update--username'><?php echo escape($username) ?>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="ml-1 bi bi-pencil-square username edit__img" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <title>編輯帳號</title>
              <path class="username" d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
              <path class="username" fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
            </svg>
            </span>
          </div>
          <form class='my__username--update hidden' action ='./handle_update.php' method='POST' >
            <input class='my__profile--input' name='username' type='text' placeholder='請輸入新帳號' required/>
            <input type='button' class='submit username__btn' value='送出' />
          </form>
        </div>
        <div class='my__profile--block'>
          <div class='my__profile--password'>密碼：
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square password edit__img" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <title>編輯密碼</title>
              <path class="password" d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
              <path class="password" fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
            </svg>
          </div>
          <form class='my__password--update hidden' action ='./handle_update.php' method='POST' >
            <input class='my__profile--input' name='current-password' type='password' placeholder='請輸入舊密碼' required/>
            <input class='my__profile--input' name='new-password' type='password' placeholder='請輸入新密碼' required/>
            <input class='my__profile--input' name='new-password-confirmed' type='password' placeholder='請再次輸入新密碼' required/>
            <input type='button' class='submit password__btn' value='送出' />
          </form>
        </div>
      </div>
    </div>
    <div class="my__comment mb-3">MY ARTICLES</div>
    <?php
    }
    ?>
    <div class="comment">
      <?php
        $sql = "SELECT U.nickname, U.username, C.comm_id, C.parent_id, C.text, C.create_at AS time, C.is_deleted  
          FROM yu_users AS U JOIN yu_comments AS C ON U.id = C.user_id 
          WHERE U.username = ? AND C.is_deleted IS NULL AND C.parent_id = 0
          ORDER BY time DESC LIMIT 50";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $result = $stmt->execute();

        if (!$result) {
          die($conn->error);
        }

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) { 
          
          // 載入文章
          include('templates/comments.php');
        }
        
        // 如果已登入且沒有文章
        if ($result->num_rows == 0 && !empty($_SESSION['username'])) { 
      ?>
          <div class='nocomment'>目前還沒有廢文，快去首頁分享吧！</div>

      <?php
        }
      ?>
    </div>
  </div>
  <script>let isLogin = <?php echo empty($_SESSION['username']) ? 'false' : 'true' ?></script>
  <script src="JS/all.js"></script>
</body>
</html>