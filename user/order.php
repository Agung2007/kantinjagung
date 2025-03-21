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

$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];
    $payment_method = $_POST['payment_method'];

    // Validasi input
    if (empty($menu_id) || empty($quantity) || $quantity <= 0 || empty($payment_method)) {
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
        $order_id = $stmt_order->insert_id;
        $stmt_order->close();

        // Simpan ke tabel `order_details`
        $stmt_details = $conn->prepare("INSERT INTO order_details (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt_details->bind_param("iiid", $order_id, $menu_id, $quantity, $menu['price']);
        $stmt_details->execute();
        $stmt_details->close();

        // Simpan ke tabel `transactions` dengan metode pembayaran
        $stmt_transaction = $conn->prepare("INSERT INTO transactions (user_id, order_id, total_price, payment_method, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
        $stmt_transaction->bind_param("iids", $user_id, $order_id, $total_price, $payment_method);
        $stmt_transaction->execute();
        $stmt_transaction->close();

        // Commit transaksi
        $conn->commit();
        $success = true;
    } catch (Exception $e) {
        $conn->rollback();
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
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if ($success) { ?>
                Swal.fire({
                    title: "Order Berhasil!",
                    icon: "success",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.location = 'menu.php';
                });
            <?php } ?>
        });
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen" 
    style="background: url('../assets/images/new.jpg') no-repeat center center/cover;">

    <div class="bg-white/90 backdrop-blur-md p-8 rounded-xl shadow-lg w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Pesan Menu</h2>
        
        <form method="POST" class="space-y-5">
            <!-- Pilih Menu -->
<div>
    <label class="block text-sm font-medium text-gray-700">Pilih Menu:</label>
    <select name="menu_id" required 
        class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
        <?php while ($menu = $menu_result->fetch_assoc()) { ?>
            <option value="<?= $menu['id'] ?>" class="text-sm md:text-base lg:text-lg">
                <?= htmlspecialchars($menu['name']) ?> - Rp<?= number_format($menu['price'], 0, ',', '.') ?>
            </option>
        <?php } ?>
    </select>
</div>

            <!-- Jumlah -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah:</label>
                <input type="number" name="quantity" required min="1" value="1"
                    class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Metode Pembayaran -->
            <div class="grid grid-cols-3 gap-4">
    <label class="flex flex-col items-center cursor-pointer border-2 border-gray-300 rounded-lg p-2 transition-all peer-checked:border-blue-500">
        <input type="radio" name="payment_method" value="Dana" required class="hidden peer">
        <img src="../assets/images/icon_dana.png" alt="Dana" class="w-12 h-12 transition-transform peer-checked:scale-110">
        <span class="text-sm text-gray-700 mt-1 peer-checked:text-blue-500">Dana</span>
    </label>
    
    <label class="flex flex-col items-center cursor-pointer border-2 border-gray-300 rounded-lg p-2 transition-all peer-checked:border-blue-500">
        <input type="radio" name="payment_method" value="ShopeePay" class="hidden peer">
        <img src="../assets/images/pay.png" alt="ShopeePay" class="w-12 h-12 transition-transform peer-checked:scale-110">
        <span class="text-sm text-gray-700 mt-1 peer-checked:text-blue-500">ShopeePay</span>
    </label>
    
    <label class="flex flex-col items-center cursor-pointer border-2 border-gray-300 rounded-lg p-2 transition-all peer-checked:border-blue-500">
        <input type="radio" name="payment_method" value="COD" class="hidden peer">
        <img src="../assets/images/cod.png" alt="COD" class="w-12 h-12 transition-transform peer-checked:scale-110">
        <span class="text-sm text-gray-700 mt-1 peer-checked:text-blue-500">COD</span>
    </label>
</div>

            <!-- Tombol -->
            <div class="flex justify-between items-center">
                <a href="menu.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-all">
                    Kembali
                </a>
                <button type="submit" 
                    class="px-6 py-2 bg-blue-500 text-white rounded-md font-semibold hover:bg-blue-600 transition-all">
                    Pesan
                </button>
            </div>
        </form>
    </div>
    <a href="https://wa.me/08586270297" target="_blank"
  class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg flex items-center space-x-2 transition hover:scale-110 hover:bg-green-600">
  <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp" class="w-6 h-6">
  <span class="hidden md:inline font-semibold">Hubungi Admin</span>
</a>



</body>
</html>
