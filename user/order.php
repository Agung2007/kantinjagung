<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_logged_in'])) {
    header(header: "Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$menu_id_from_url = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$menu_id_from_url) {
    die("Menu tidak ditemukan!");
}

// Ambil data menu berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM menu WHERE id = ?");
$stmt->bind_param("i", $menu_id_from_url);
$stmt->execute();
$result = $stmt->get_result();
$selected_menu = $result->fetch_assoc();
$stmt->close();

if (!$selected_menu) {
    die("Menu tidak tersedia!");
}
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];
    $payment_method = $_POST['payment_method'];
    $catatan = isset($_POST['catatan']) ? trim($_POST['catatan']) : null;


    // Validasi input
    if (empty($menu_id) || empty($quantity) || $quantity <= 0 || empty($payment_method)) {
        die("Error: Data tidak valid!");
    }

    // Mulai transaksi database
    $conn->begin_transaction();

    try {
        
        // Ambil harga menu
        $stmt_menu = $conn->prepare("SELECT price, stock FROM menu WHERE id = ?");
        $stmt_menu->bind_param("i", $menu_id);
        $stmt_menu->execute();
        $result_menu = $stmt_menu->get_result();
        $menu = $result_menu->fetch_assoc();
        $stmt_menu->close();
        
        if (!$menu) {
            throw new Exception("Menu tidak ditemukan.");
        }
        
        if ($menu['stock'] < $quantity) {
            throw new Exception("Stok tidak mencukupi.");
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
        $stmt_transaction = $conn->prepare("INSERT INTO transactions (user_id, order_id, total_price, payment_method, status, created_at, catatan) VALUES (?, ?, ?, ?, 'pending', NOW(), ?)");
        $stmt_transaction->bind_param("iidss", $user_id, $order_id, $total_price, $payment_method, $catatan);
                $stmt_transaction->execute();
        $stmt_transaction->close();

        // Pr Stok
//         $stmt_update_stock = $conn->prepare("UPDATE menu SET stock = stock - ? WHERE id = ?");
// $stmt_update_stock->bind_param("ii", $quantity, $menu_id);
// $stmt_update_stock->execute();
// $stmt_update_stock->close();


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

        document.addEventListener("DOMContentLoaded", function () {
    const menuSelect = document.querySelector("select[name='menu_id']");
    const quantityInput = document.querySelector("#quantity");

    menuSelect.addEventListener("change", function () {
    let selectedOption = menuSelect.options[menuSelect.selectedIndex];
    let stok = parseInt(selectedOption.getAttribute("data-stock")) || 0;

    quantityInput.max = stok;

    if (stok === 0) {
        quantityInput.value = 0;
        quantityInput.disabled = true;
    } else {
        quantityInput.disabled = false;
        if (parseInt(quantityInput.value) > stok) {
            quantityInput.value = stok; // batasin kalau sebelumnya kelebihan
        }
        ubahJumlah(0); // panggil fungsi agar tombol + dan - tetap sinkron
    }
});
});

function ubahJumlah(delta) {
        const input = document.getElementById("jumlahInput");
        let current = parseInt(input.value);
        if (!isNaN(current)) {
            let newVal = current + delta;
            if (newVal >= 1) {
                input.value = newVal;
            }
        }
    }


    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen" 
    style="background: url('../assets/images/new.jpg') no-repeat center center/cover;">

    <div class="bg-white/90 backdrop-blur-md p-8 rounded-xl shadow-lg w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Pesan Menu</h2>
        
        <form method="POST" class="space-y-5">
            <!-- Pilih Menu -->
            <div class="mb-4">
    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Menu:</label>
    <input type="hidden" name="menu_id" value="<?= $selected_menu['id'] ?>">

    <div class="flex items-center justify-between bg-white border border-gray-300 rounded-lg shadow-sm p-4">
        <!-- Informasi menu -->
        <div class="flex-1">
            <p class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($selected_menu['name']) ?></p>
            <p class="text-sm text-gray-600">Harga: <span class="text-blue-600 font-medium">Rp<?= number_format($selected_menu['price'], 0, ',', '.') ?></span></p>
            <p class="text-sm text-gray-600">Stok Tersisa: 
                <span class="font-semibold <?= $selected_menu['stock'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                    <?= $selected_menu['stock'] ?>
                </span>
            </p>
        </div>

        <!-- Gambar menu -->
        <div class="ml-4">
            <img src="../<?= htmlspecialchars($selected_menu['image']) ?>" alt="<?= htmlspecialchars($selected_menu['name']) ?>" class="w-20 h-20 object-cover rounded-lg shadow">
        </div>
    </div>
</div>

<div class="flex items-center justify-between gap-3">
    <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Jumlah:</label>
    
    <div class="flex items-center border border-gray-300 rounded-full overflow-hidden bg-white shadow-sm">
        <!-- Tombol kurang -->
        <button type="button" onclick="ubahJumlah(-1)" class="px-3 py-2 hover:bg-gray-100">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
            </svg>
        </button>

        <!-- Input jumlah -->
        <input type="number" name="quantity" id="jumlahInput" value="1" min="1" max="<?= $selected_menu['stock'] ?>" required
    class="w-12 text-center border-0 focus:ring-0 text-sm font-medium text-gray-800 bg-transparent" />

        <!-- Tombol tambah -->
        <button type="button" onclick="ubahJumlah(1)" class="px-3 py-2 hover:bg-gray-100">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </div>
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

<!-- Catatan -->
<div>
    <label class="block text-sm font-medium text-gray-700">Kelas atau alamat pengantaran:</label>
    <textarea name="catatan" rows="3" 
        class="w-full mt-1 p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
        placeholder="Contoh: 12 Rpl 1, Rps, Tefa, Masjid"
        required maxlength="10"></textarea>
</div>


<p class="text-sm text-black/60 italic mt-2">* Harga sudah termasuk biaya ongkir</p>


<!-- Tombol -->
<div class="flex justify-between items-center mt-6">
    <!-- Tombol Kembali -->
    <a href="menu.php" 
       class="inline-flex items-center gap-2 px-5 py-2 rounded-lg 
              bg-gradient-to-r from-gray-300 to-gray-400 text-gray-800 
              font-medium shadow hover:shadow-md hover:scale-105 active:scale-95 
              transition-all duration-300 ease-in-out">
        <!-- Ikon panah -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali
    </a>

    <!-- Tombol Pesan -->
<!-- Tombol Pesan -->
<button type="submit"
    class="inline-flex items-center gap-2 px-6 py-2 rounded-lg 
           bg-gradient-to-r from-blue-500 to-indigo-600 text-white 
           font-semibold shadow hover:shadow-lg hover:scale-105 active:scale-95 
           transition-all duration-300 ease-in-out ring-1 ring-blue-300">
    
    <!-- Ikon keranjang -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
         viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.3 5.2a1 1 0 001 1.3h10.6a1 1 0 001-1.3L17 13M9 21h.01M15 21h.01" />
    </svg>

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
