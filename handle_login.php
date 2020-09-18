<?php
  session_start();
  require_once('conn.php');
  include_once('utils.php');

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
  if (password_verify($password, $row['password'])) {
    $_SESSION['username'] = $username;
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
