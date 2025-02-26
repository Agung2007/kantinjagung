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
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <!-- Sidebar and Dashboard Container -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-900 text-white p-6">
            <!-- Logo Kantin -->
            <div class="flex items-center justify-center mb-6">
                <img src="../assets/images/ifsu.png" alt="Kantin Logo" class="w-24 h-24 object-cover rounded-full">
            </div>
            <h2 class="text-3xl font-bold mb-6 text-center">KANTIN IFSU BERKAH</h2>
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
                    <a href="manage_menu.php"
                        class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd"
                                d="M2 2.75A.75.75 0 0 1 2.75 2h10.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 2.75Zm0 10.5a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75ZM2 6.25a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 6.25Zm0 3.5A.75.75 0 0 1 2.75 9h10.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 9.75Z"
                                clip-rule="evenodd" />
                        </svg>
                        Kelola Menu
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
                    <a href="logout.php"
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
        <div class="flex-1 p-10">
            <div class="bg-white p-6 rounded-lg shadow-xl mb-8">
                <h2 class="text-3xl font-semibold text-gray-700 mb-4">Admin Super Agung</h2>
                <p class="text-lg text-gray-500">Selamat datang di dashboard admin.</p>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-lg w-full">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">📊 Laporan Admin</h2>
    
    <div class="flex justify-between gap-6">

        <!-- Card Total Users -->
        <div class="flex-1 bg-blue-100 p-6 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-105 flex items-center">
            <div class="bg-blue-500 text-white p-4 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 20c4.418 0 8-1.79 8-4V9c0-2.21-3.582-4-8-4S4 6.79 4 9v7c0 2.21 3.582 4 8 4z"></path>
                    <circle cx="12" cy="9" r="3"></circle>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-xl font-semibold text-gray-700">Total Users</h3>
                <p class="text-4xl text-blue-700 font-bold mt-1"><?php echo $total_users; ?></p>
            </div>
        </div>

        <!-- Card Menu Items -->
        <div class="flex-1 bg-green-100 p-6 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-105 flex items-center">
            <div class="bg-green-500 text-white p-4 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-xl font-semibold text-gray-700">Menu Items</h3>
                <p class="text-4xl text-green-700 font-bold mt-1"><?php echo $total_menu; ?></p>
            </div>
        </div>

        <!-- Card Total Transactions -->
        <div class="flex-1 bg-yellow-100 p-6 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-105 flex items-center">
            <div class="bg-yellow-500 text-white p-4 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l3 7h7l-5.5 5 2.5 8-7-4-7 4 2.5-8L2 9h7z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-xl font-semibold text-gray-700">Total Transactions</h3>
                <p class="text-4xl text-yellow-700 font-bold mt-1"><?php echo $total_transactions; ?></p>
            </div>
        </div>

    </div>
</div>
</body>

</html>