<?php
session_start();
session_unset();
session_destroy(); // Menghapus seluruh session login

header("Location: login.php");
exit;
?>