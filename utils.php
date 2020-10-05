<?php
  require_once('conn.php');

  // 透過 username 拿到 user 資料
  function getUserFromUsername($username) {
    global $conn;
    $sql = "SELECT * FROM yu_users WHERE username = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $result = $stmt->execute();

    if (!$result) {
      die($conn->error);
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row;
  }

  // XSS
  function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES);
  }

  // 製造加鹽亂碼
  function getSalt() {
    $str = '';
    for($i = 1; $i <= 16; $i += 1) {
      $str .= chr(rand(65,90));
    }
    return $str;
  }

  // 取得讚總數
  function getLikes($comment_id) {
    global $conn;
    $sql = "SELECT COUNT(comm_id) AS total_liked, comm_id
      FROM yu_likes WHERE comm_id = ? GROUP BY comm_id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $comment_id);
    $result = $stmt->execute();

    if (!$result) {
      die($conn->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
      return 0;
    }
    
    $row = $result->fetch_assoc();
    $likes = $row['total_liked'];
    return $likes;
  }

  // 確認是否有按過讚
  function checkLiked($comment_id, $user_id) {
    global $conn;
    $sql = "SELECT * FROM yu_likes WHERE comm_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $comment_id, $user_id);
    $result = $stmt->execute();

    if (!$result) {
      die($conn->error);
    }

    $result = $stmt->get_result();
    return ($result->num_rows === 0) ? false : true;
  }
?>