<?php
  session_start();
  require_once('conn.php');
  include_once('utils.php');

  // 如果沒有登入
  if (empty($_SESSION['username'])) {
    echo json_encode(array(
      'OK' => false,
      'message' => '請登入',
    ));
    exit();
  }

  // get user id & nickname
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
  $user_id = $user['id'];
  $nickname = $user['nickname'];

  // 更改暱稱
  if (!empty($_POST["newData"]) && ($_POST["name"] === 'nickname')) {
    $nickname = $_POST["newData"];
    $sql = "UPDATE yu_users SET nickname = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $nickname, $username);
    $result = $stmt->execute();

    if (!$result) {
      echo json_encode(array(
        'result' => 'failed',
        'message' => '暱稱編輯失敗，請稍後再試一次'
      ));
      exit();
    }

    echo json_encode(array(
      'result' => 'success',
      'message' => '暱稱編輯成功'
    ));
    
  // 更改帳號
  } else if (!empty($_POST["newData"]) && ($_POST["name"] === 'username')) {
    $new_username = $_POST["newData"];
    $sql = "UPDATE yu_users SET username = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $new_username, $username);
    $result = $stmt->execute();

    // 如果此帳號已有人使用
    if ($conn->errno === 1062) {
      echo json_encode(array(
        'result' => 'failed',
        'message' => '此帳號已有人使用'
      ));
    } else if ($result) {
      $_SESSION['username'] = $new_username;
      echo json_encode(array(
        'result' => 'success',
        'message' => '帳號編輯成功'
      ));
    } else {
      echo json_encode(array(
        'result' => 'failed',
        'message' => '編輯失敗，請稍後再試一次'
      ));
    }

  // 更改密碼
  } else if (!empty($_POST["currentPassword"]) && !empty($_POST["newPassword"]) && !empty($_POST["newPasswordConfirmed"])) {
    $old_password = $_POST["currentPassword"];
    $new_password = password_hash($_POST["newPassword"], PASSWORD_DEFAULT);
    
    // 取得舊密碼
    $sql =  "SELECT * FROM yu_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $result = $stmt->execute();
    
    if(!$result) {
      echo json_encode(array(
        'result' => 'failed',
        'message' => '密碼更新失敗，請稍後再試一次'
      ));
      exit();
    }
  
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // 如果舊密碼驗證失敗
    if (!password_verify($old_password, $row['password'])) {
      echo json_encode(array(
        'result' => 'failed',
        'message' => '舊密碼輸入錯誤'
      ));
      exit();
    }

    // 如果舊密碼驗證成功
    $sql = "UPDATE yu_users SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $new_password, $username);
    $result = $stmt->execute();

    if (!$result) {
      echo json_encode(array(
        'result' => 'failed',
        'message' => '密碼更新失敗，請稍後再試一次'
      ));
      exit();
    }

    echo json_encode(array(
      'result' => 'success',
      'message' => '密碼更新成功'
    ));

  // 編輯留言
  } else if (!empty($_POST["newText"])) {
    $new_text = $_POST["newText"];
    $comment_id = $_POST["id"];
    $sql = "UPDATE yu_comments SET text = ? WHERE comm_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $new_text, $comment_id, $user_id);
    $result = $stmt->execute();

    if (!$result) {
      echo json_encode(array(
        'result' => 'failed',
        'message' => '編輯失敗'
      ));
      exit();
    } 

    echo json_encode(array(
      'result' => 'success',
      'message' => '編輯成功'
    ));

  }

?>