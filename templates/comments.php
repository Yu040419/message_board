<?php

	// 主留言
	echo "<div class='comment__block'>";
	echo 	 "<div class='comment__block--info'>";

	$span = sprintf(
		"<span class='comment__username' data-name='%s'> (@%s )</span>",
		escape($row['username']),
		escape($row['username'])
	);

	if ($row['username'] === $username) {

		$a = sprintf(
			"<img src='./img/delete.png' title='刪除廢文' class='img comment__delete' data-id='%d'/>",
			escape($row['comm_id'])
		);

		echo  "<div class='comment__info--name'>" . escape($row['nickname']);
		echo		$span;
		echo		"<img src='./img/edit.png' title='編輯廢文' class='img comment__edit' />";
		echo		$a;
		echo	"</div>";
	
	} else {
		echo 	"<div class='comment__info--name'>" . escape($row['nickname']);
		echo		$span;
		echo	"</div>";
	}
	
	echo     "<div class='comment__info--time'>" . escape($row['time']) . "</div>";
	echo   "</div>";

	$text = sprintf(
		"<div class='comment__text' data-id='%d'>%s</div>",
		escape($row['comm_id']),
		escape($row['text'])
	);

	echo  $text;

	// 子留言
	include('subcomments.php');

	// 新增子留言
	if (!empty($_SESSION['username'])) {

		echo  "<form class='subcomment__form' action ='./handle_add_comment.php' method='POST' >";
		echo		"<textarea class='subcomment__input' name='text' placeholder='輸入回覆 . . .' required></textarea>";
		
		$parent_id = sprintf(
			"<input type='hidden' name='parent_id' value='%d' />",
			escape($row['comm_id'])
		);
	
		echo		$parent_id;
		echo		"<input type='button' class='btn add__comment' value='我要回覆' />";
		echo	"</form>";
	}
	
	echo "</div>";
?>