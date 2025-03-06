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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showOrderDetails(orderId) {
            fetch(`order_details.php?id=${orderId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('order-detail-content').innerHTML = data;
                    document.getElementById('order-detail-modal').classList.remove('hidden');
                });
        }

        function confirmCancel(orderId) {
    Swal.fire({
        title: "Yakin ingin membatalkan pesanan?",
        text: "Pesanan yang dibatalkan tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, batalkan!"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("cancel_order.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "order_id=" + encodeURIComponent(orderId)
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: data.status === "success" ? "Berhasil" : "Gagal",
                    text: data.message,
                    icon: data.status === "success" ? "success" : "error"
                }).then(() => {
                    if (data.status === "success") {
                        location.reload(); // Refresh halaman setelah pembatalan
                    }
                });
            })
            .catch(error => console.error("Error:", error));
        }
    });
}
        function closeModal() {
            document.getElementById('order-detail-modal').classList.add('hidden');
        }
    </script>
</head>
<body class="bg-blue-50">
    <div class="max-w-5xl mx-auto p-6 mt-10 bg-white rounded-lg shadow-xl">
        <h2 class="text-4xl font-bold text-center mb-6 text-blue-600 uppercase tracking-widest">
            Riwayat Pesanan
        </h2>

        <div class="mb-6 text-center">
            <a href="menu.php" class="px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold shadow-md hover:bg-blue-600 transition">
                ⬅ Kembali ke Menu
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg border border-gray-200">
                <thead>
                    <tr class="bg-blue-500 text-white uppercase">
                        <th class="px-5 py-3">No</th>
                        <th class="px-5 py-3">Order ID</th>
                        <th class="px-5 py-3">Total Harga</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Aksi</th>
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
                        <tr class="border-b border-gray-200 hover:bg-blue-100 transition">
                            <td class="px-5 py-3 text-center"><?= $no++ ?></td>
                            <td class="px-5 py-3 text-center"><?= htmlspecialchars($row['id']) ?></td>
                            <td class="px-5 py-3 text-center text-green-600 font-semibold">
                                Rp <?= number_format($row['total_price'], 0, ',', '.') ?>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-3 py-1 rounded-full text-white <?= $status_color ?>">
                                    <?= htmlspecialchars(ucfirst($row['status'])) ?>
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center"><?= htmlspecialchars($row['order_date']) ?></td>
                            <td class="px-5 py-3 text-center flex justify-center space-x-2">
                                <button onclick="showOrderDetails(<?= $row['id'] ?>)" 
                                    class="px-5 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 shadow-md transition">
                                    🔍 Lihat Detail
                                </button>
                                <?php if ($row['status'] == 'pending'): ?>
    <button onclick="confirmCancel(<?= $row['id'] ?>)" 
        class="px-5 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 shadow-md transition">
        ❌ Batalkan
    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DETAIL PESANAN -->
    <div id="order-detail-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden transition-all">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full transform scale-95 transition-all duration-300">
            <h3 class="text-2xl font-bold mb-4 text-gray-800">Detail Pesanan</h3>
            <div id="order-detail-content" class="text-gray-700"></div>
            <button onclick="closeModal()" 
                class="mt-4 px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                 Tutup
            </button>
        </div>
    </div>
</body>
</html>
