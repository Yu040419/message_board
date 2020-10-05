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

  $comment_id = $_POST['commentId'];

  // get user id
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
  $user_id = $user['id'];

  // remove like comment
  $sql = "DELETE FROM yu_likes WHERE comm_id = ? AND user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ii', $comment_id, $user_id);
  $result = $stmt->execute();

  if (!$result) {
    echo json_encode(array(
      'message' => '退讚失敗',
      'OK' => false,
    ));
    exit();
  }

  echo json_encode(array(
    'OK' => true,
  ));
?>