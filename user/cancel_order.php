<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

if (!isset($_POST['order_id']) || !is_numeric($_POST['order_id'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request. Order ID not received."]);
    exit;
}

$order_id = $_POST['order_id'];
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

// Cek apakah pesanan milik user dan masih pending
$stmt = $conn->prepare("SELECT id, status FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Gagal."]);
    exit;
}

$order = $result->fetch_assoc();

if ($order['status'] !== 'pending') {
    echo json_encode(["status" => "error", "message" => "Gagal."]);
    exit;
}

// Update status pesanan menjadi canceled
$stmt = $conn->prepare("UPDATE orders SET status = 'canceled' WHERE id = ?");
$stmt->bind_param("i", $order_id);
$order_updated = $stmt->execute();

// Update status di transactions juga
$stmt = $conn->prepare("UPDATE transactions SET status = 'canceled' WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$transaction_updated = $stmt->execute();

if ($order_updated && $transaction_updated) {
    echo json_encode(["status" => "success", "message" => "Berhasil membatalkan."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to cancel order."]);
}
?>
