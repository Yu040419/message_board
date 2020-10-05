<?php
  require_once('conn.php');
  include_once('utils.php');
  session_start();

  $username = $_POST['username'];
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    echo json_encode(array(
      'OK' => false,
      'message' => '請完整輸入帳號及密碼'
    ));
    exit();
  }

  $sql =  "SELECT * FROM yu_users WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $username);
  $result = $stmt->execute();

  if (!$result) {
    echo json_encode(array(
      'OK' => false,
      'message' => '登入失敗，請稍後再試一次'
    ));
    exit();
  }

  $result = $stmt->get_result();
  if ($result->num_rows === 0) {
    echo json_encode(array(
      'OK' => false,
      'message' => '帳號或密碼輸入錯誤'
    ));
    exit();
  }

  $row = $result->fetch_assoc();
  $salted_password = $password . $row['salt'];

  // 登入成功
  if (password_verify($salted_password, $row['password'])) {
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
      'message' => '登入成功'
    ));
  } else {
    echo json_encode(array(
      'OK' => false,
      'message' => '帳號或密碼輸入錯誤'
    ));
  }
?>
