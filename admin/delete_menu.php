<?php
include('db_connection.php');

if (isset($_GET['id'])) {
    $menu_id = $_GET['id'];

    // Hapus transaksi terkait
    $delete_transactions = $conn->prepare("DELETE FROM transactions WHERE id = ?");
    $delete_transactions->bind_param("i", $menu_id);
    $delete_transactions->execute();
    $delete_transactions->close();

    // Hapus menu setelah transaksi dihapus
    $delete_menu = $conn->prepare("DELETE FROM menu WHERE id = ?");
    $delete_menu->bind_param("i", $menu_id);
    if ($delete_menu->execute()) {
        header("Location: manage_menu.php?success=Menu berhasil dihapus");
        exit();
    } else {
        header("Location: manage_menu.php?error=Gagal menghapus menu");
        exit();
    }
}
?>
