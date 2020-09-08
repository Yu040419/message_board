<?php
session_start();
require_once('conn.php');
include_once('utils.php');

if (empty($_POST['text'])) {
  echo json_encode(array(
    'result' => 'failed',
    'message' => '請輸入內容'
  ));
}

// get user id & nickname
$username = $_SESSION['username'];
$user = getUserFromUsername($username);
$user_id = $user['id'];
$nickname = $user['nickname'];

// add comment
$text = $_POST['text'];
$parent_id = $_POST['parentID'];
$sql = "INSERT INTO yu_comments(user_id, parent_id, text) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iis', $user_id, $parent_id, $text);
$result = $stmt->execute();

if (!$result) {
  echo json_encode(array(
    'result' => 'failed',
    'message' => '新增失敗'
  ));
} else {
  // the latest comment_id
  $new_id = $stmt->insert_id;

  // get comment's created_at
  $sql = "SELECT * FROM yu_comments WHERE comm_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $new_id);
  $result = $stmt->execute();

  if (!$result) {
    echo json_encode(array(
      'result' => 'failed',
      'message' => '新增失敗'
    ));

  } else {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $time = $row['create_at'];
    echo json_encode(array(
      'result' => 'success',
      'id' => $new_id,
      'username' => $username,
      'nickname' => $nickname,
      'time' => $time,
      'message' => '新增成功'
    ));
  }
}
?>