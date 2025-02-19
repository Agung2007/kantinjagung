<?php
// Cek apakah pengguna sudah login
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Koneksi database
include('db_connection.php');
$user_id = $_SESSION['user_id'];

// Ambil riwayat pesanan pengguna
$query = "SELECT o.id, o.total_price, o.created_at, o.status 
          FROM orders o 
          WHERE o.user_id = ? 
          ORDER BY o.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-semibold text-center mb-6">Order History</h2>
        <table class="min-w-full bg-white shadow-md rounded-lg mb-6">
            <thead>
                <tr>
                    <th class="px-4 py-3">Order ID</th>
                    <th class="px-4 py-3">Total Price</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="border-b">
                        <td class="px-4 py-3"><?= htmlspecialchars($row['id']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['total_price']) ?> USD</td>
                        <td class="px-4 py-3"><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
