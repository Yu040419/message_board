<?php
  session_start();
  require_once('conn.php');
  include_once('utils.php');

  $username = $_SESSION['username'];

  if (empty($_POST['id'])) {
    echo json_encode(array(
      'result' => 'failed',
      'message' => 'id is empty',
    ));
  } else {
    $comment_id = $_POST['id'];
    $parent_id = $_POST['id'];
  }

  // get user id
  $user = getUserFromUsername($username);
  $user_id = $user['id'];

  $sql =  "UPDATE yu_comments SET is_deleted = 1 
    WHERE comm_id = ? AND user_id = ? OR parent_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('iii', $comment_id, $user_id, $parent_id);
  $result = $stmt->execute();

  if (!$result) {
    echo json_encode(array(
      'result' => 'failed',
      'message' => '刪除失敗',
    ));
  } else {
    echo json_encode(array(
      'result' => 'success',
      'message' => '刪除成功',
    ));
  }

?>
