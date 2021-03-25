<?php
  require_once('conn.php');
  include_once('utils.php');
  session_start();

  $nickname = $_POST["nickname"];
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (empty($nickname) || empty($username) || empty($password)) {
    echo json_encode(array(
      'OK' => false,
      'message' => '請完整輸入暱稱、帳號及密碼'
    ));
    exit();
  }

  $salt = getSalt();
  $password .= $salt;
  $password = password_hash($password, PASSWORD_DEFAULT);

  $sql = "INSERT INTO yu_users(nickname, username, password, salt) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ssss', $nickname, $username, $password, $salt);
  $result = $stmt->execute();

  if ($conn->errno === 1062) {
    echo json_encode(array(
      'OK' => false,
      'message' => '此帳號已有人使用'
    ));
  } else if ($result) {
    $_SESSION['username'] = $username;
    $maxlifetime = time() + 3600 * 24 * 7; // 一周
    $secure = false; // gandi 提供的是 http
    $httponly = true; // prevent JavaScript access to session cookie
    $samesite = 'lax';

    if (PHP_VERSION_ID < 70300) {
      setcookie(session_name(), session_id(), $maxlifetime, '/; samesite='.$samesite, $_SERVER['HTTP_HOST'], $secure, $httponly);
    } else {
      setcookie(session_name(), session_id(), $maxlifetime, '/', $_SERVER['HTTP_HOST'], $secure, $httponly, $samesite);
    }

    echo json_encode(array(
      'OK' => true,
      'message' => '註冊成功'
    ));
  } else {
    echo json_encode(array(
      'OK' => false,
      'message' => '註冊失敗，請稍後再試一次'
    ));
  }
?>