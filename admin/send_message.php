<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message']) && isset($_POST['user_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $user_id = intval($_POST['user_id']);
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chats (user_id, admin_id, message, sender) VALUES (?, ?, ?, 'admin')");
        $stmt->bind_param("iis", $user_id, $admin_id, $message);
        $stmt->execute();
        $stmt->close();
    }
}
