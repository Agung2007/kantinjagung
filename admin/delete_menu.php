<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
include('db_connection.php');

// Cek jika parameter id ada
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus menu berdasarkan id
    $sql = "DELETE FROM menu WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    // Menjalankan query
    if ($stmt->execute()) {
        // Jika berhasil dihapus, arahkan kembali ke halaman manage menu
        header("Location: manage_menu.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "No menu id provided!";
}
?>
