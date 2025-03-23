<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    if ($conn->query("DELETE FROM categories WHERE id = $id")) {
        $_SESSION['success_message'] = "Kategori berhasil dihapus!";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus kategori!";
    }
}

header("Location: manage_categories.php");
exit;
?>
