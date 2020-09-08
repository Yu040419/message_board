<?php
session_start();
require_once('conn.php');
include_once('utils.php');

$nickname = $_POST["nickname"];
$username = $_POST["username"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

if (empty($nickname) || empty($username) || empty($password)) {
	header('Location: register.php?errcode=1');
	die();
}

$sql = "INSERT INTO yu_users(nickname, username, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $nickname, $username, $password);
$result = $stmt->execute();

if ($conn->errno === 1062) {
  header('Location: register.php?errcode=2');
} else if ($result) {
	$_SESSION['username'] = $username;
  header('Location: index.php');
}
?>