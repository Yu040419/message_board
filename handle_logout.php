<?php
  require_once('conn.php');
  setcookie(session_name(), '', time() - 3600);
  session_start();
  session_destroy();
  header('Location: index.php');
?>