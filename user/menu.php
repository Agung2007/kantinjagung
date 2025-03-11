<?php
session_start();
include('db_connection.php');

// Cek koneksi ke database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ambil menu dari database tanpa kolom description
$sql = "SELECT id, name, price, image FROM menu";
$result = $conn->query($sql);

// Ambil data pengguna untuk menampilkan profil
$user_id = $_SESSION['user_id'] ?? null;
$user_query = "SELECT username, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Kantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../assets/images/images.png">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: white;
            /* Mengubah background utama menjadi putih */
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        @keyframes zoom-in {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.2);
            }
        }

        .zoom-in {
            animation: zoom-in 2s infinite alternate ease-in-out;
        }
    </style>
</head>

<body>
<body class="relative min-h-screen">


<nav class="relative bg-gradient-to-r from-blue-900 to-blue-600 p-4 shadow-lg fixed w-full top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <!-- Logo dan Judul -->
        <div class="flex items-center space-x-4">
            <img src="../assets/images/ifsu.png" alt="Logo Kantin" class="w-12 h-12 rounded-full border-2 border-white">
            <h1 class="text-lg md:text-2xl font-extrabold tracking-wide text-yellow-300">
                KANTIN IFSU BERKAH
            </h1>
        </div>

        <!-- Profil + Menu (Harus di Flex agar ke Kanan) -->
        <div class="flex items-center space-x-6">
            <!-- **Profil (Tetap Muncul di Mobile & Desktop)** -->
            <?php if ($user): ?>
            <a href="profile.php" class="flex items-center space-x-2">
                <img src="<?= htmlspecialchars($user['profile_picture'] ?? '../assets/images/avatar.jpeg') ?>"
                    alt="Profile" class="h-10 w-10 rounded-full border-2 border-white object-cover">
                <span class="text-white font-semibold"><?= htmlspecialchars($user['username']) ?></span>
            </a>
            <?php endif; ?>

            <!-- Menu Navbar (Desktop) -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="order_history.php" class="text-white hover:text-yellow-300 transition">Riwayat Pemesanan</a>

                <a href="javascript:void(0);" onclick="confirmLogout()" 
                class="px-4 py-2 bg-red-600 rounded-full hover:bg-red-700 transition font-bold text-white">
                    Logout
                </a>
            </div>

            <!-- Tombol Menu Mobile -->
            <button id="menu-toggle" class="md:hidden text-white text-2xl focus:outline-none">
                ☰
            </button>
        </div>
    </div>

    <!-- **DROPDOWN MENU MOBILE (WARNA PUTIH, POSISI DI BAWAH)** -->
    <div id="mobile-menu" 
        class="hidden absolute right-4 top-20 w-48 bg-white text-black border border-gray-300 shadow-lg rounded-lg z-50">
        <a href="order_history.php" class="block px-4 py-2 hover:bg-gray-100 transition">Riwayat Pesanan</a>

        <a href="javascript:void(0);" onclick="confirmLogout()" 
        class="block px-4 py-2 hover:bg-gray-100 transition">Logout</a>
    </div>
</nav>
<!-- Main Content -->
<div class="max-w-7xl mx-auto p-6 mt-8 bg-white/50 backdrop-blur-md rounded-lg shadow-lg py-10">
    <h2 class="text-4xl font-extrabold text-center mb-12 uppercase tracking-widest text-blue-700 animate-pulse">
        Menu Kantin
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php while ($menu = $result->fetch_assoc()): ?>
        <div class="relative bg-white/50 border border-gray-200 rounded-xl shadow-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl group backdrop-blur-lg">
            <!-- Bagian Depan (Gambar) -->
            <div class="relative w-full h-56 overflow-hidden rounded-t-xl">
                <img src="../<?= !empty($menu['image']) ? htmlspecialchars($menu['image']) : 'default.jpg' ?>"
                    alt="<?= htmlspecialchars($menu['name']) ?>"
                    class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110">
            </div>

            <!-- Detail Menu -->
            <div class="p-6 text-gray-900 flex flex-col flex-grow text-center">
                <h3 class="text-2xl font-semibold group-hover:text-blue-600 transition-colors duration-300">
                    <?= htmlspecialchars($menu['name']) ?>
                </h3>
                <p class="text-lg text-green-600 font-bold mt-2">
                    Rp <?= number_format($menu['price'], 0, ',', '.') ?>
                </p>

                <!-- Tombol Pesan -->
                <a href="order.php?id=<?= $menu['id'] ?>" 
                    class="mt-4 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white text-center rounded-lg font-bold 
                    hover:scale-110 hover:from-blue-700 hover:to-blue-500 transition-all duration-300">
                    Pesan Sekarang
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
<script>
    function confirmLogout() {
        Swal.fire({
            title: "Apa kamu yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, logout!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout.php"; // Redirect ke logout jika dikonfirmasi
            }
        });
    }

    document.getElementById("menu-toggle").addEventListener("click", function () {
        document.getElementById("mobile-menu").classList.toggle("hidden");
    });

    // Tutup menu jika klik di luar
    document.addEventListener("click", function (event) {
        let menu = document.getElementById("mobile-menu");
        let button = document.getElementById("menu-toggle");
        if (!menu.contains(event.target) && !button.contains(event.target)) {
            menu.classList.add("hidden");
        }
    });
</script>

</html>