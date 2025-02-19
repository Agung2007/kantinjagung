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

    // Ambil harga menu
    $menu_query = "SELECT price FROM menu WHERE id = ?";
    $stmt = $conn->prepare($menu_query);
    $stmt->bind_param("i", $menu_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $menu = $result->fetch_assoc();
    
    if ($menu) {
        $total_price = $menu['price'] * $quantity;

        // Simpan transaksi
        $insert_query = "INSERT INTO transactions (user_id, menu_id, quantity, total_price, status) VALUES (?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iiid", $user_id, $menu_id, $quantity, $total_price);
        if ($stmt->execute()) {
            echo "Order berhasil! Silakan tunggu konfirmasi.";
        } else {
            echo "Gagal melakukan order.";
        }
    } else {
        echo "Menu tidak ditemukan.";
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
