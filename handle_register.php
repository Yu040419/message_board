<?php
session_start();
require_once('conn.php');
include_once('utils.php');

$nickname = $_POST["nickname"];
$username = $_POST["username"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

if (empty($nickname) || empty($username) || empty($password)) {
  echo json_encode(array(
    'OK' => false,
    'message' => '請完整輸入暱稱、帳號及密碼'
  ));
  exit();
}

$sql = "INSERT INTO yu_users(nickname, username, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $nickname, $username, $password);
$result = $stmt->execute();

if ($conn->errno === 1062) {
  echo json_encode(array(
    'OK' => false,
    'message' => '此帳號已有人使用'
  ));
} else if ($result) {
  $_SESSION['username'] = $username;
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