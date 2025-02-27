<?php
// Cek apakah pengguna sudah login
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Koneksi database
include('db_connection.php');

// Pastikan ada parameter id pada URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-red-500 text-center'>ID Pesanan tidak ditemukan.</p>";
    exit;
}

$order_id = intval($_GET['id']); // Pastikan order_id adalah integer

// Ambil informasi umum pesanan
$query = "SELECT total_price, order_date, status FROM orders WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_info = $stmt->get_result()->fetch_assoc();

// Jika tidak ditemukan, tampilkan pesan error
if (!$order_info) {
    echo "<p class='text-red-500 text-center'>Pesanan tidak ditemukan.</p>";
    exit;
}

// Ambil detail pesanan
$query = "SELECT md.name, od.quantity, od.price 
          FROM order_details od 
          JOIN menu md ON od.menu_id = md.id 
          WHERE od.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
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
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-10">
        <h2 class="text-3xl font-semibold text-center mb-6">Order Details</h2>

        <div class="mb-6">
            <p><strong>Total Price:</strong> $<?= number_format($order_info['total_price'], 2) ?></p>
            <p><strong>Order Date:</strong> <?= htmlspecialchars($order_info['order_date']) ?></p>
            <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($order_info['status'])) ?></p>
        </div>

        <table class="w-full bg-gray-50 rounded-lg shadow-md">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-6 py-3 text-left">Menu Item</th>
                    <th class="px-6 py-3 text-center">Quantity</th>
                    <th class="px-6 py-3 text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="border-b">
                        <td class="px-6 py-3"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="px-6 py-3 text-center"><?= htmlspecialchars($row['quantity']) ?></td>
                        <td class="px-6 py-3 text-right">$<?= number_format($row['price'], 2) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
