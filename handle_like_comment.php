<?php
  session_start();
  require_once('conn.php');
  include_once('utils.php');

  $comment_id = $_POST['commentId'];

  // 如果沒有登入
  if (empty($_SESSION['username'])) {
    echo json_encode(array(
      'message' => '請登入',
      'OK' => false,
    ));
    exit();
  }

  // get user id
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
  $user_id = $user['id'];

  // like comment
  $sql = "INSERT INTO yu_likes(comm_id, user_id) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ii', $comment_id, $user_id);
  $result = $stmt->execute();

  if (!$result) {
    echo json_encode(array(
      'message' => '按讚失敗',
      'OK' => false,
    ));
    exit();
  }

  // count likes
  $sql = "SELECT COUNT(comm_id) AS total_liked, comm_id
    FROM yu_likes WHERE comm_id = ? GROUP BY comm_id";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $comment_id);
  $result = $stmt->execute();

  if (!$result) {
    echo json_encode(array(
      'message' => '按讚失敗',
      'OK' => false,
    ));
    exit();
  }

  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $like_number = $row['total_liked'];
  echo json_encode(array(
    'OK' => true,
    'likes' => $like_number,
  ));
?>