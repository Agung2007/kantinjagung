<?php
// Cek apakah pengguna sudah login
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Koneksi database
include('../includes/db_connection.php');
$order_id = $_GET['id'];

// Ambil detail pesanan
$query = "SELECT md.name, od.quantity, od.price 
          FROM order_details od 
          JOIN menu md ON od.menu_item_id = md.id 
          WHERE od.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

// Ambil informasi umum pesanan
$query = "SELECT total_price, order_date, status FROM orders WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_info = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-semibold text-center mb-6">Order Details</h2>

        <div class="mb-6">
            <p><strong>Total Price:</strong> <?= htmlspecialchars($order_info['total_price']) ?> USD</p>
            <p><strong>Order Date:</strong> <?= htmlspecialchars($order_info['order_date']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($order_info['status']) ?></p>
        </div>

        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="px-6 py-3">Menu Item</th>
                    <th class="px-6 py-3">Quantity</th>
                    <th class="px-6 py-3">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="border-b">
                        <td class="px-6 py-3"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($row['quantity']) ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($row['price']) ?> USD</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
