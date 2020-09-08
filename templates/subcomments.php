<?php
	require_once('conn.php');
	include_once('utils.php');
	
	echo  "<div class='subcomments'>";

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
	while ($row_sub = $result_sub->fetch_assoc()) {

		echo		"<div class='subcomment__block'>";
		echo			"<div class='subcomment__block--info'>";
		
		$span = sprintf(
			"<span class='comment__username' data-name='%s'> (@%s )</span>",
			escape($row_sub['username']),
			escape($row_sub['username'])
		);

		if ($row_sub['username'] === $username) {
			$a = sprintf(
				"<img src='./img/delete.png' title='刪除廢文' class='img comment__delete' data-id='%d'/>",
				escape($row_sub['comm_id'])
			);
						
			echo  "<div class='subcomment__info--name'>" . escape($row_sub['nickname']);
			echo		$span;
			echo		"<img src='./img/edit.png' title='編輯廢文' class='img comment__edit' />"; 
			echo		$a; 
			

		} else {
			echo 	"<div class='subcomment__info--name'>" . escape($row_sub['nickname']);
			echo		$span;
		}

		if ($row['username'] === $row_sub['username']) {
			echo "<span class='origin' >樓主</span>";
		}

		echo	"</div>";
		echo     "<div class='subcomment__info--time'>" . escape($row_sub['time']) . "</div>";
		echo   "</div>";
	
		$text = sprintf(
			"<div class='subcomment__text' data-id='%d'>%s</div>",
			escape($row_sub['comm_id']),
			escape($row_sub['text'])
		);
	
		echo  $text;
		echo	"</div>";

	}

	echo	"</div>";

?>