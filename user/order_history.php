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

// Ambil riwayat pesanan pengguna dengan status terbaru dari transaksi
$query = "SELECT o.id, o.total_price, o.order_date, 
                 COALESCE(t.status, o.status) AS status 
          FROM orders o 
          LEFT JOIN transactions t ON o.id = t.order_id 
          WHERE o.user_id = ? 
          ORDER BY o.order_date DESC";

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
    <script>
        function showOrderDetails(orderId) {
            fetch(`order_details.php?id=${orderId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('order-detail-content').innerHTML = data;
                    document.getElementById('order-detail-modal').classList.remove('hidden');
                });
        }

        function closeModal() {
            document.getElementById('order-detail-modal').classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto p-6">
        <h2 class="text-3xl font-bold text-center mb-6 text-blue-600">Order History</h2>

        <div class="mb-4">
            <a href="menu.php" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition">
                ⬅ Kembali ke Menu
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-lg rounded-lg">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Order ID</th>
                        <th class="px-4 py-3">Total Price</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Order Date</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = $result->fetch_assoc()) { 
                        $status_color = match ($row['status']) {
                            'pending' => 'bg-yellow-500',
                            'processed' => 'bg-blue-500',
                            'completed' => 'bg-green-500',
                            'canceled' => 'bg-red-500',
                            default => 'bg-gray-500',
                        };
                    ?>
                        <tr class="border-b hover:bg-gray-100 transition">
                            <td class="px-4 py-3 text-center"><?= $no++ ?></td>
                            <td class="px-4 py-3 text-center"><?= htmlspecialchars($row['id']) ?></td>
                            <td class="px-4 py-3 text-center text-green-600 font-semibold">
                                Rp <?= number_format($row['total_price'], 0, ',', '.') ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 rounded-full text-white <?= $status_color ?>">
                                    <?= htmlspecialchars(ucfirst($row['status'])) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center"><?= htmlspecialchars($row['order_date']) ?></td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="showOrderDetails(<?= $row['id'] ?>)" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Lihat Detail
                                 </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DETAIL PESANAN -->
    <div id="order-detail-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md">
            <h3 class="text-xl font-semibold mb-4">Detail Pesanan</h3>
            <div id="order-detail-content"></div>
            <button onclick="closeModal()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Tutup
            </button>
        </div>
    </div>

</body>
</html>
