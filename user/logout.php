<?php
session_start();
session_unset();  // Hapus semua variabel sesi
session_destroy(); // Hapus sesi pengguna

// Redirect ke halaman login atau landing page
header("Location: login.php");
exit;
?>
