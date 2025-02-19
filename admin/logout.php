<?php
session_start();

// Menghancurkan sesi untuk logout
session_destroy();

// Redirect ke halaman login setelah logout
header("Location: login.php");
exit;
?>
