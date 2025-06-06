<?php
session_start();

// Pastikan koneksi database sudah dimasukkan
include('db_connection.php'); // Sesuaikan dengan path yang benar

// Cek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Mengambil jumlah total users dengan validasi query
$total_users = 0;
$result_users = $conn->query("SELECT COUNT(*) AS total_users FROM users");
if ($result_users) {
    $total_users = $result_users->fetch_assoc()['total_users'];
}

// Mengambil jumlah total menu items dengan validasi query
$total_menu = 0;
$result_menu = $conn->query("SELECT COUNT(*) AS menu FROM menu");
if ($result_menu) {
    $total_menu = $result_menu->fetch_assoc()['menu'];
}

// Mengambil jumlah total transaksi dengan validasi query
$total_transactions = 0;
$result_transactions = $conn->query("SELECT COUNT(*) AS total_transactions FROM transactions");
if ($result_transactions) {
    $total_transactions = $result_transactions->fetch_assoc()['total_transactions'];
}

// Mengambil total pendapatan berdasarkan tanggal tertentu
$total_revenue = 0;
$query_revenue = "SELECT SUM(total_price) AS total_revenue FROM transactions WHERE status = 'completed'";

// Jika ada input tanggal tertentu
if (!empty($_GET['date'])) {
    $query_revenue .= " AND DATE(transaction_date) = ?";
}

$stmt = $conn->prepare($query_revenue);

if (!empty($_GET['date'])) {
    $stmt->bind_param("s", $_GET['date']);
}

$stmt->execute();
$result_revenue = $stmt->get_result();
$row = $result_revenue->fetch_assoc();
$total_revenue = $row['total_revenue'] ? $row['total_revenue'] : 0;
$stmt->close();
//produk terlaris
$top_products_query = "
    SELECT m.id, m.name AS menu_name, m.image, SUM(od.quantity) AS total_quantity, m.price
    FROM order_details od
    JOIN menu m ON od.menu_id = m.id
    JOIN orders o ON od.order_id = o.id
    JOIN transactions t ON t.order_id = o.id
    WHERE t.status = 'completed'
    GROUP BY m.id, m.name, m.image, m.price
    ORDER BY total_quantity DESC
    LIMIT 5";

$top_products_result = $conn->query($top_products_query);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-50">

    <!-- Sidebar and Dashboard Container -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-900 text-white p-6 fixed h-screen">
<!-- Logo dan Judul -->
<div class="flex items-center justify-center mb-4 gap-2">
    <!-- Gambar Logo -->
    <img src="../assets/images/ifsu.png" alt="Kantin Logo" class="w-16 h-16 object-cover rounded-full">

    <!-- Judul -->
    <h1 class="text-xl font-bold leading-tight">DAPOER IFSU</h1>
</div>

<!-- Divider -->
<hr class="border-t border-white/30 mb-4">
            <ul class="space-y-4">
            <li>
            <a href="dashboard.php"
                        class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="manage_users.php"
                        class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        Kelola User
                    </a>
                </li>
                <li>
                <a href="manage_categories.php"
    class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700 transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 7.5l9 4.5 9-4.5M3 12l9 4.5 9-4.5M3 16.5l9 4.5 9-4.5" />
    </svg>
    Kategori
</a>
                </li>

                </li>


                <a href="manage_menu.php"
    class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700 transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h3.5l1-1h2l1 1H17a2 2 0 012 2v14a2 2 0 01-2 2z" />
    </svg>
    Kelola Menu
</a>
                </li>
                <li>

                <a href="manage_stock.php"
    class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700 transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M4.5 6.75V19.5A2.25 2.25 0 0 0 6.75 21.75h10.5A2.25 2.25 0 0 0 19.5 19.5V6.75M4.5 6.75 12 3l7.5 3.75M12 12v6m0 0 3-3m-3 3-3-3" />
    </svg>
    Kelola Stok
</a>
                </li>
                <li>

                    <a href="transactions.php"
                        class="flex items-center p-2 gap-3 rounded-lg hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        Transaksi
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" onclick="confirmLogout()"
                        class="flex items-center gap-3 p-2 rounded-lg hover:bg-red-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8 ml-64">
            <div class="bg-white p-6 rounded-lg shadow-xl mb-8">
                <h2 class="text-3xl font-semibold text-gray-700 mb-4">Dashboard Admin</h2>
                <p class="text-lg text-gray-500">Selamat datang di dashboard admin.</p>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-lg w-full">
                <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">📊 Laporan Admin</h2>

                <div class="flex justify-between gap-6">

                    <!-- Card Total Users -->
                    <div
                        class="flex-1 bg-blue-100 p-6 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-105 flex items-center">
                        <div class="bg-blue-500 text-white p-4 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold text-gray-700">Total Users</h3>
                            <p class="text-4xl text-blue-700 font-bold mt-1"><?php echo $total_users; ?></p>
                        </div>
                    </div>

                    <!-- Card Menu Items -->
                    <div
                        class="flex-1 bg-green-100 p-6 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-105 flex items-center">
                        <div class="bg-green-500 text-white p-4 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold text-gray-700">Total Menu</h3>
                            <p class="text-4xl text-green-700 font-bold mt-1"><?php echo $total_menu; ?></p>
                        </div>
                    </div>

                    <!-- Card Total Transactions -->
                    <div
                        class="flex-1 bg-yellow-100 p-6 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-105 flex items-center">
                        <div class="bg-yellow-500 text-white p-4 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold text-gray-700">Total Transaksi</h3>
                            <p class="text-4xl text-yellow-700 font-bold mt-1"><?php echo $total_transactions; ?></p>
                        </div>
                    </div>

                </div>

                <!-- pendapatan -->
                <div class="mt-8 text-center">
                <form method="GET" action="dashboard.php" class="mb-4 flex items-center gap-4">
    <label for="date" class="text-gray-700 font-semibold">Pilih Tanggal:</label>
    <input type="date" id="date" name="date" class="border p-2 rounded-lg" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">
    <button type="submit"
    class="inline-flex items-center gap-2 px-5 py-2 
           bg-gradient-to-r from-blue-500 to-indigo-600 
           text-white font-semibold rounded-lg 
           hover:shadow-md hover:scale-105 active:scale-95 
           transition-all duration-300 ease-in-out ring-1 ring-blue-400">

    <!-- Ikon filter -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
         viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17v-4.586L3.293 6.707A1 1 0 013 6V4z" />
    </svg>
    Filter
</button>
</form>
                    <h3 class="text-2xl font-semibold text-gray-700">Total Pendapatan</h3>
                    <p class="text-5xl text-purple-700 font-bold mt-2">Rp
                        <?php echo number_format($total_revenue, 0, ',', '.'); ?></p>
                    <?php if (isset($_GET['start_date']) && isset($_GET['end_date'])): ?>
                    <p class="text-gray-500 mt-2">Dari <?php echo date("d M Y", strtotime($_GET['start_date'])); ?>
                        sampai <?php echo date("d M Y", strtotime($_GET['end_date'])); ?></p>
                    <?php endif; ?>
                </div>
                <!-- Produk terlaris -->
                <div class="mt-8 p-6 bg-white shadow-md rounded-lg">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-4">Produk Terlaris</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <?php while ($row = $top_products_result->fetch_assoc()) { ?>
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                            <img src="../<?= htmlspecialchars($row['image']) ?>"
                                alt="<?= htmlspecialchars($row['menu_name']) ?>" class="w-full h-40 object-cover">
                            <div class="p-4">
                                <h4 class="text-lg font-semibold"><?= htmlspecialchars($row['menu_name']) ?></h4>
                                <p class="text-gray-600">Terjual: <span
                                        class="font-semibold"><?= $row['total_quantity'] ?></span></p>
                                <p class="text-gray-800 font-bold">Rp<?= number_format($row['price'], 0, ',', '.') ?>
                                </p>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>




            </div>
</body>

</html>
<script>
    function confirmLogout() {
        Swal.fire({
            title: "Apa kamu yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, logout!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout.php"; // Redirect ke logout jika dikonfirmasi
            }
        });
    }
</script>


</html>