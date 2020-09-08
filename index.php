<?php
session_start();
require_once('conn.php');
include_once('utils.php');
?>
<!DOCTYPE html>

<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>FeiWen</title>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
	<link rel="stylesheet" href="CSS/normalize.css">
	<link rel="stylesheet" href="CSS/style.css">
</head>
<body>
	<div class="wrap">

		<?php
			include_once('templates/navbar.php');
			
			if (empty($_SESSION['username'])) { ?>
				<div class='remind'>
					<div class='remind__text'>註冊會員後即可發布您的廢文</div>
				</div>
			<?php
			} else { ?>
				<div class='new__comment'>
					<div class='new__comment--name'><?php echo escape($nickname) ?></div>
					<form class='new__comment--block' action='./handle_add_comment.php' method='POST' >
						<textarea name='text' class='new__comment--text' placeholder='輸入您的廢文' required></textarea>
						<input type='hidden' name='parent_id' value='0'/>
						<input type='button' class='btn add__comment' value='送出' />
					</form>
				</div>
		<?php
			}
		
		?> 
		<div class="latest">LATEST ARTICLES</div>
		<div class="comment">
			<?php
				$page = 1;
				if (!empty($_GET['page'])) {
					$page = intval($_GET['page']);
				}

				$comments_per_page = 20;
				$offset = ($page - 1) * $comments_per_page;
				$sql = "SELECT U.nickname, U.username, C.comm_id, C.parent_id, C.text, C.create_at AS time, C.is_deleted
					FROM yu_users AS U JOIN yu_comments AS C ON U.id = C.user_id
					WHERE C.is_deleted IS NULL AND C.parent_id = 0
					ORDER BY time DESC LIMIT ? OFFSET ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ii', $comments_per_page, $offset);
				$result = $stmt->execute();

				if (!$result) {
					die($conn->error);
				}

				$result = $stmt->get_result();
				while ($row = $result->fetch_assoc()) {
					
					// 留言
					include('templates/comments.php');
				}
			?>
	</div>
	<div class='page'>
		<?php
			$sql = "SELECT COUNT(comm_id) AS total, C.is_deleted 
				FROM yu_users AS U JOIN yu_comments AS C ON U.id = C.user_id
				WHERE C.is_deleted IS NULL AND C.parent_id = 0";
			$stmt = $conn->prepare($sql);
			$result = $stmt->execute();
			$result = $stmt->get_result();

			if (!$result) {
				die($conn->error);
			}

			$row = $result->fetch_assoc();
			$total_comments = $row['total'];
			$total_pages = ceil($total_comments / $comments_per_page);

			if ($page != 1) { ?>
				<a href='index.php?page=<?php echo $page - 1 ?>' class='page__btn'>上一頁</a>
				<?php	
			} 

			for ($i = 1; $i <= $total_pages; $i += 1) {
				if ($page == $i) {	?>
				<span class='on'><?php echo $i ?></span>
				<?php
				} else { ?>
				<a href='index.php?page=<?php echo $i ?>' class='page__btn'><?php echo $i ?></a>
				<?php
				}
			}
			
			if ($page != $total_pages) { ?>
				<a href='index.php?page=<?php echo $page + 1 ?>' class='page__btn'>下一頁</a>
				<?php	
			} ?>
	</div>

	<script src="all.js"></script>

</body>
</html>