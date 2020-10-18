<?php
  require_once('conn.php');
  include_once('utils.php');
?>
<div class='subcomments'>
<?php
  $parent_id = $row['comm_id'];

  $sql_sub = "SELECT U.nickname, U.username, C.comm_id, C.parent_id, C.text, C.create_at AS time, C.is_deleted
    FROM yu_users AS U JOIN yu_comments AS C ON U.id = C.user_id
    WHERE C.is_deleted IS NULL AND C.parent_id = ?
    ORDER BY time ASC";
  $stmt_sub = $conn->prepare($sql_sub);
  $stmt_sub->bind_param('i', $parent_id);
  $result_sub = $stmt_sub->execute();

  if (!$result_sub) {
    die($conn->error);
  }

  $result_sub = $stmt_sub->get_result();
  while ($row_sub = $result_sub->fetch_assoc()) {?>

    <div class='subcomment__block'>
      <div class='subcomment__block--info mb-1 d-flex justify-content-between align-items-center'>

      <?php
      // 如果是使用者的留言，有編輯及刪除選單
      if ($row_sub['username'] === $username) {?>
        <div class='d-flex align-items-center'>
          <span class='mb-0 card-title subcomment__username'><?php echo escape($row_sub['username'])?></span>
        
        <?php
        // 如果子留言跟父留言為同個使用者
        if ($row['username'] === $row_sub['username']) {?>
          <span class='origin'>樓主</span>
          <?php
        }?>

        </div>
        <div class='dropdown dropleft'>
          <button class='btn btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>
          <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
            <div class='comment__edit dropdown-item'>編輯</div>
            <div class='comment__delete dropdown-item' data-id='<?php echo escape($row['comm_id'])?>' data-type='comment'>刪除</div>
          </div>
        </div>
        <?php

      } else {?>
        <div class='d-flex align-items-center'>
          <span class='mb-0 card-title subcomment__username'><?php echo escape($row_sub['username'])?></span>

          <?php
          // 如果子留言跟父留言為同個使用者
          if ($row['username'] === $row_sub['username']) {?>
            <span class='origin'>樓主</span>
            <?php
          }?>
        </div>
        <?php
      }?>

      </div>
      <div class='subcomment__info--time mb-2'><?php echo escape($row_sub['time'])?></div>
      <p class='subcomment__text' data-id='<?php echo escape($row_sub["comm_id"])?>' parent-id='<?php echo escape($row["comm_id"])?>'><?php echo escape($row_sub["text"])?></p>
      <div class='like__area'>

      <?php
        // 未登入
        if (empty($_SESSION['username'])) {?>
          <div data-toggle='tooltip' data-placement='bottom' title='登入後即可按讚'>
            <svg width='1.3em' height='1.3em' viewBox='0 0 16 16' class='bi bi-heart like' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
              <path fill-rule='evenodd' d='M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z'/>
            </svg>
          </div>

          <?php
          // 有登入
        } else {

          // 確認有無按讚
          $liked = checkLiked($row_sub['comm_id'], $user_id);
          // 如果有按
          if ($liked) {
            heartIcon('hidden', NULL);
          } else {
            heartIcon(NULL, 'hidden');
          }
        }

        // 取得該留言讚數
        $likes = getLikes($row_sub['comm_id']);

        // 如果該留言有被按讚
        if ($likes > 0) {?>
          <span class='liked__text' data='<?php echo $likes?>'><?php echo $likes?> 人已按讚</span>
          <?php
        }?>
        </div>
      </div>
    <?php
  } ?>
  </div>
