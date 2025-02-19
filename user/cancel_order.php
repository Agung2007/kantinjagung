<?php
session_start();
include('db_connection.php');

if (isset($_POST['order_id']) && is_numeric($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];

    // Pastikan pesanan milik pengguna yang login dan status pesanan masih pending
    $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        if ($order['status'] == 'pending') {
            // Update status pesanan menjadi canceled
            $stmt = $conn->prepare("UPDATE orders SET status = 'canceled' WHERE id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            echo "Your order has been canceled.";
        } else {
            echo "Cannot cancel the order. It's already processed.";
        }
    } else {
        echo "Order not found.";
    }
} else {
    echo "Invalid request.";
}
?>
