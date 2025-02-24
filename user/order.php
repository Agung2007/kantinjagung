<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil daftar menu dari database
$menu_query = "SELECT * FROM menu";
$menu_result = $conn->query($menu_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];

    // Validasi input
    if (empty($menu_id) || empty($quantity) || $quantity <= 0) {
        die("Error: Data tidak valid!");
    }

    // Mulai transaksi database
    $conn->begin_transaction();

    try {
        // Ambil harga menu
        $stmt_menu = $conn->prepare("SELECT price FROM menu WHERE id = ?");
        $stmt_menu->bind_param("i", $menu_id);
        $stmt_menu->execute();
        $result_menu = $stmt_menu->get_result();
        $menu = $result_menu->fetch_assoc();
        $stmt_menu->close();

        if (!$menu) {
            throw new Exception("Menu tidak ditemukan.");
        }

        $total_price = $menu['price'] * $quantity;

        // Simpan ke tabel `orders`
        $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_price, order_date, status) VALUES (?, ?, NOW(), 'pending')");
        $stmt_order->bind_param("id", $user_id, $total_price);
        $stmt_order->execute();
        $order_id = $stmt_order->insert_id; // Ambil ID order yang baru dibuat
        $stmt_order->close();

        // Simpan ke tabel `order_details`
        $stmt_details = $conn->prepare("INSERT INTO order_details (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt_details->bind_param("iiid", $order_id, $menu_id, $quantity, $menu['price']);
        $stmt_details->execute();
        $stmt_details->close();

        // Simpan ke tabel `transactions`
        $stmt_transaction = $conn->prepare("INSERT INTO transactions (user_id, order_id, total_price, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
        $stmt_transaction->bind_param("iid", $user_id, $order_id, $total_price);
        $stmt_transaction->execute();
        $stmt_transaction->close();

        // Commit transaksi agar data benar-benar tersimpan
        $conn->commit();

        echo "<script>alert('Order berhasil! Silakan tunggu konfirmasi.'); window.location='menu.php';</script>";
    } catch (Exception $e) {
        $conn->rollback(); // Batalkan semua perubahan jika terjadi kesalahan
        echo "Gagal melakukan order: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-xl font-bold mb-4 text-center">Order Menu</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Menu:</label>
                <select name="menu_id" required class="w-full mt-1 p-2 border rounded-md">
                    <?php while ($menu = $menu_result->fetch_assoc()) { ?>
                        <option value="<?= $menu['id'] ?>">
                            <?= $menu['name'] ?> - Rp<?= number_format($menu['price'], 0, ',', '.') ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah:</label>
                <input type="number" name="quantity" required class="w-full mt-1 p-2 border rounded-md">
            </div>
            <div class="flex justify-between">
                <a href="menu.php" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">Kembali</a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Pesan</button>
            </div>
        </form>
    </div>
</body>
</html>
