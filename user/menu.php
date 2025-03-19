<?php
session_start();
include('db_connection.php');

// Cek koneksi ke database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Proses pencarian
$search = $_GET['search'] ?? '';
$sql = "SELECT id, name, price, image FROM menu WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = "%$search%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();

// Ambil data pengguna untuk menampilkan profil
$user_id = $_SESSION['user_id'] ?? null;
$user_query = "SELECT username, profile_picture FROM users WHERE id = ?";
$stmt_user = $conn->prepare($user_query);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
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
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">

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


<nav class="fixed top-0 left-0 w-full bg-gradient-to-r from-blue-900 to-blue-600 p-4 shadow-lg z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <!-- Logo dan Judul -->
        <div class="flex items-center space-x-4">
            <img src="../assets/images/ifsu.png" alt="Logo Kantin" class="w-12 h-12 rounded-full border-2 border-white">
            <h1 class="text-lg md:text-2xl font-extrabold tracking-wide text-yellow-300">
                KANTIN IFSU BERKAH
            </h1>
        </div>

        <!-- Profil + Menu -->
        <div class="flex items-center space-x-6">
            <?php if ($user): ?>
            <a href="profile.php" class="flex items-center space-x-2">
                <img src="<?= htmlspecialchars($user['profile_picture'] ?? '../assets/images/avatar.jpeg') ?>"
                    alt="Profile" class="h-10 w-10 rounded-full border-2 border-white object-cover">
                <span class="text-white font-semibold"><?= htmlspecialchars($user['username']) ?></span>
            </a>
            <?php endif; ?>

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

    <!-- **DROPDOWN MENU MOBILE** -->
    <div id="mobile-menu" 
        class="hidden absolute right-4 top-20 w-48 bg-white text-black border border-gray-300 shadow-lg rounded-lg z-50">
        <a href="order_history.php" class="block px-4 py-2 hover:bg-gray-100 transition">Riwayat Pesanan</a>

        <a href="javascript:void(0);" onclick="confirmLogout()" 
        class="block px-4 py-2 hover:bg-gray-100 transition">Logout</a>
    </div>
</nav>
<!-- Main Content -->
<div class="max-w-7xl mx-auto p-6 mt-20 bg-white/50 backdrop-blur-md rounded-lg shadow-lg py-10">
    <h2 class="text-4xl font-extrabold text-center mb-6 uppercase tracking-widest text-blue-700 animate-pulse">Menu Kantin</h2>
    
    <!-- Search Bar -->
    <div class="flex justify-center mb-6 px-4">
        <form method="GET" class="flex w-full max-w-md">
            <input type="text" name="search" placeholder="Cari menu..." class="px-4 py-2 border rounded-l-md focus:outline-none w-full" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-md">Cari</button>
        </form>
    </div>
<!-- produk dan harga -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-4">
        <?php while ($menu = $result->fetch_assoc()): ?>
        <div class="relative bg-white/50 border border-gray-200 rounded-xl shadow-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl group backdrop-blur-lg">
            <div class="relative w-full h-64 overflow-hidden rounded-t-xl">
                <img src="../<?= !empty($menu['image']) ? htmlspecialchars($menu['image']) : 'default.jpg' ?>" alt="<?= htmlspecialchars($menu['name']) ?>" class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110">
            </div>
            <div class="p-6 text-gray-900 flex flex-col flex-grow text-center">
                <h3 class="text-2xl font-semibold group-hover:text-blue-600 transition-colors duration-300"><?= htmlspecialchars($menu['name']) ?></h3>
                <p class="text-lg text-green-600 font-bold mt-2">Rp <?= number_format($menu['price'], 0, ',', '.') ?></p>
                <a href="order.php?id=<?= $menu['id'] ?>" class="mt-4 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white text-center rounded-lg font-bold hover:scale-110 hover:from-blue-700 hover:to-blue-500 transition-all duration-300">Pesan Sekarang</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>    </div>
</div>

<footer class="bg-gray-50">
  <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">

      <p class="mt-4 text-center text-sm text-gray-500 lg:mt-0 lg:text-right">
        Copyright &copy; 2025. Kantin Ifsu berkah.
      </p>
    </div>
  </div>
</footer>

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
<a href="https://wa.me/08586270297" target="_blank" class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg flex items-center space-x-2 transition hover:scale-110 hover:bg-green-600">
    <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp" class="w-6 h-6">
    <span class="hidden md:inline font-semibold">Hubungi Admin</span>
</a>


</html>