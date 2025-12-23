<?php
session_start();
$_SESSION = array();
session_destroy();
header("Location: loginAdmin.php");
exit;
?>