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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link rel="shortcut icon" href="../assets/images/images.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-lg mt-12">
        <h2 class="text-4xl font-bold text-center text-blue-700 uppercase tracking-widest mb-6">
            Detail Pesanan
        </h2>

        <div class="mb-6 text-lg text-gray-700">
            <p><strong>Total Harga:</strong> 
                <span class="text-green-600 font-semibold">
                    Rp <?= number_format($order_info['total_price'], 0, ',', '.') ?>
                </span>
            </p>
            <p><strong>Tanggal Pesanan:</strong> <?= htmlspecialchars($order_info['order_date']) ?></p>
            <p><strong>Status:</strong> 
                <span class="px-3 py-1 rounded-full text-white 
                    <?= match ($order_info['status']) {
                        'pending' => 'bg-yellow-500',
                        'processed' => 'bg-blue-500',
                        'completed' => 'bg-green-500',
                        'canceled' => 'bg-red-500',
                        default => 'bg-gray-500',
                    } ?>">
                    <?= ucfirst(htmlspecialchars($order_info['status'])) ?>
                </span>
            </p>
        </div>

        <table class="w-full bg-gray-50 rounded-lg shadow-md">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left">Nama Menu</th>
                    <th class="px-6 py-3 text-center">Jumlah</th>
                    <th class="px-6 py-3 text-right">Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="border-b hover:bg-gray-100 transition">
                        <td class="px-6 py-3"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="px-6 py-3 text-center"><?= htmlspecialchars($row['quantity']) ?></td>
                        <td class="px-6 py-3 text-right text-green-600 font-semibold">
                            Rp <?= number_format($row['price'], 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Tombol Download PDF -->
        <div class="mt-6 text-center">
            <a href="generate_pdf.php?id=<?= $order_id ?>" 
                class="px-6 py-3 bg-green-500 text-white rounded-lg font-semibold shadow-md hover:bg-green-600 transition flex items-center justify-center gap-2">
                📄 Download PDF
            </a>
        </div>
    </div>

</body>
</html>

