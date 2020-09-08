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
				<div class='remind__text'>請登入或註冊</div>
			</div>
		<?php
		} else { ?>

		<div class='my__profile'>
			<div class='my__profile--title'>個人資料</div>
			<div class='my__profile--border'>
				<div class='my__profile--block'>
					<div class='my__profile--nickname'>暱稱：
						<span name='nickname' class='my__profile--update--nickname'><?php echo escape($nickname) ?>
							<img src='./img/edit.png' title='編輯暱稱' class='img nickname' />
						</span>
					</div>
					<form class='my__nickname--update hidden' action ='./handle_update.php' method='POST' >
						<input class='my__profile--input' name='nickname' type='text' placeholder='請輸入新暱稱' required/>
						<input type='button' class='submit nickname__btn' value='送出' />
					</form>
				</div>
				<div class='my__profile--block'>
					<div class='my__profile--username'>帳號：
						<span name='nickname' class='my__profile--update--username'><?php echo escape($username) ?>
							<img src='./img/edit.png' title='編輯帳號' class='img username' />
						</span>
					</div>
					<form class='my__username--update hidden' action ='./handle_update.php' method='POST' >
						<input class='my__profile--input' name='username' type='text' placeholder='請輸入新帳號' required/>
						<input type='button' class='submit username__btn' value='送出' />
					</form>
				</div>
				<div class='my__profile--block'>
					<div class='my__profile--password'>密碼：<img src='./img/edit.png' title='編輯密碼' class='img password' /></div>
					<form class='my__password--update hidden' action ='./handle_update.php' method='POST' >
						<input class='my__profile--input' name='current-password' type='password' placeholder='請輸入舊密碼' required/>
						<input class='my__profile--input' name='new-password' type='password' placeholder='請輸入新密碼' required/>
						<input class='my__profile--input' name='new-password-confirmed' type='password' placeholder='請再次輸入新密碼' required/>
						<input type='button' class='submit password__btn' value='送出' />
					</form>
				</div>
			</div>
		</div>
		<div class="my__comment">MY ARTICLES</div>
		<?php
		}
		?>
		<div class="comment">
			<?php
				$sql = "SELECT U.nickname, U.username, C.comm_id, C.parent_id, C.text, C.create_at AS time, C.is_deleted  
					FROM yu_users AS U JOIN yu_comments AS C ON U.id = C.user_id 
					WHERE U.username = ? AND C.is_deleted IS NULL AND C.parent_id = 0
					ORDER BY time DESC LIMIT 50";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('s', $username);
				$result = $stmt->execute();

				if (!$result) {
					die($conn->error);
				}

				$result = $stmt->get_result();
				while ($row = $result->fetch_assoc()) { 
					
					// 載入文章
					include('templates/comments.php');
				}
				
				// 如果已登入且沒有文章
				if ($result->num_rows == 0 && !empty($_SESSION['username'])) { 
			?>
					<div class='nocomment'>目前還沒有廢文，快去首頁分享吧！</div>

			<?php
				}
			?>
	</div>
	<script src="all.js"></script>

</body>
</html>