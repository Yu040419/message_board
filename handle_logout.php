<?php
require_once('conn.php');
session_start();
session_destroy();
header('Location: index.php');
?>