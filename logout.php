<?php 
 session_start();

 $_SESSION =[];

Session_destroy();

setcookie("PHPSESSID", "", time() - 3600, "/");

header("Location: login.php");
exit();


?>