<?php
session_start();
session_unset();  // Hapus semua variabel sesi
session_destroy(); // Hapus sesi pengguna

header("Location: index.php");
exit;
?>
