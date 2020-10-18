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
  
  // 按讚圖示
  function heartIcon($like, $liked) {
    echo "<svg width='1.3em' height='1.3em' viewBox='0 0 16 16' class='bi bi-heart like " . $like . "' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>"; ?>
      <path fill-rule='evenodd' d='M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z'/>
    </svg>
    <?php
    echo "<svg width='1.3em' height='1.3em' viewBox='0 0 16 16' class='bi bi-heart-fill liked " . $liked . "' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>"; ?>
      <path fill-rule='evenodd' class='liked__heart' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z'/>
    </svg>
    <?php
  }
?>