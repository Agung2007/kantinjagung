<?php
session_start();
include('db_connection.php');

// Cek koneksi ke database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Proses pencarian
$search = $_GET['search'] ?? '';
$search_param = "%$search%";

// Pagination setup
$limit = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Hindari halaman < 1
$start = ($page - 1) * $limit;


$totalPages = 1; // Default 1 halaman (tidak digunakan jika filter aktif)

if (empty($selected_category) && empty($search)) {
    $totalQuery = "SELECT COUNT(id) AS total FROM menu WHERE name LIKE ?";
    $params = [$search_param];
    $types = "s";

    $totalStmt = $conn->prepare($totalQuery);
    $totalStmt->bind_param($types, ...$params);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totalRow = $totalResult->fetch_assoc();
    $totalMenu = $totalRow['total'];
    $totalPages = ceil($totalMenu / $limit);
}


// Ambil kategori dari URL dan hindari SQL Injection
$selected_category = $_GET['category'] ?? '';
$selected_category = mysqli_real_escape_string($conn, $selected_category);

// Ambil daftar kategori unik dari tabel menu
$categories_query = "SELECT DISTINCT category FROM menu WHERE category IS NOT NULL AND category != ''";
$categories_result = $conn->query($categories_query);

// Buat query untuk menghitung total menu
$totalQuery = "SELECT COUNT(id) AS total FROM menu WHERE name LIKE ?";
$params = [$search_param];
$types = "s";

// Tambahkan filter kategori jika dipilih
if (!empty($selected_category)) {
    $totalQuery .= " AND category = ?";
    $params[] = $selected_category;
    $types .= "s";
}

$totalStmt = $conn->prepare($totalQuery);
$totalStmt->bind_param($types, ...$params);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalMenu = $totalRow['total'];
$totalPages = ceil($totalMenu / $limit);

// Ambil menu berdasarkan pencarian, kategori, dan pagination
$sql = "SELECT id, name, price, image, category, stock, description FROM menu WHERE name LIKE ?";
$params = [$search_param];
$types = "s";

if (!empty($selected_category)) {
    $sql .= " AND category = ?";
    $params[] = $selected_category;
    $types .= "s";
}

// Urutkan produk terbaru
$sql .= " ORDER BY id DESC";

// Pagination hanya jika tidak pakai filter
if (empty($selected_category) && empty($search)) {
    $sql .= " LIMIT ?, ?";
    $params[] = $start;
    $params[] = $limit;
    $types .= "ii";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Ambil data pengguna (jika login)
$user_id = $_SESSION['user_id'] ?? null;
$user = null;
if ($user_id) {
    $user_query = "SELECT username, profile_picture FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($user_query);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $user_result = $stmt_user->get_result();
    $user = $user_result->fetch_assoc();
}
?>

<?php
// Ambil 5 pelanggan dengan jumlah pesanan terbanyak
$top_customers_query = "
    SELECT u.username, u.profile_picture, COUNT(o.id) as total_orders
    FROM users u
    JOIN orders o ON u.id = o.user_id
    GROUP BY u.id
    ORDER BY total_orders DESC
    LIMIT 5
";
$top_customers_result = $conn->query($top_customers_query);
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
                DAPOER IFSU
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

    <!-- Dropdown Pelanggan Setia -->
    <div class="relative group">
    <button class="text-white hover:text-yellow-300 transition focus:outline-none">
        Pelanggan Setia ▼
    </button>
    <div class="absolute hidden group-hover:block bg-white text-black border rounded-lg shadow-lg w-56 mt-2 z-10">
        <?php 
        $position = 1;
        while ($customer = $top_customers_result->fetch_assoc()): 
            // Tentukan SVG mahkota berdasarkan posisi
            $crown = '';
            if ($position == 1) {
                $crown = '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3l2.39 4.78L18 8.33l-4.5 4.39L14.78 18 10 15.27 5.22 18l1.28-5.28L2 8.33l5.61-.55L10 3z" /></svg>';
            } elseif ($position == 2) {
                $crown = '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3l2.39 4.78L18 8.33l-4.5 4.39L14.78 18 10 15.27 5.22 18l1.28-5.28L2 8.33l5.61-.55L10 3z" /></svg>';
            } elseif ($position == 3) {
                $crown = '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3l2.39 4.78L18 8.33l-4.5 4.39L14.78 18 10 15.27 5.22 18l1.28-5.28L2 8.33l5.61-.55L10 3z" /></svg>';
            } else {
                $crown = '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 3l2 4 4 .5-3 3 .9 4.5L10 13l-3.9 2 1-4.5-3-3 4-.5 2-4z" /></svg>';
            }
        ?>
            <a href="profile.php?user=<?= htmlspecialchars($customer['username']) ?>" 
               class="flex items-center px-4 py-2 hover:bg-gray-100">
                <div class="flex items-center gap-2">
                    <?= $crown ?>
                    <img src="<?= htmlspecialchars($customer['profile_picture'] ?? '../assets/images/avatar.jpeg') ?>" 
                         class="w-8 h-8 rounded-full border">
                    <div class="text-sm font-semibold">
                        <?= htmlspecialchars($position) ?>. <?= htmlspecialchars($customer['username']) ?>
                    </div>
                </div>
            </a>
        <?php 
            $position++;
        endwhile; ?>
    </div>
</div>

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
<!-- MAIN CONTENT -->
<div class="max-w-7xl mx-auto p-6 mt-20 bg-white/50 backdrop-blur-md rounded-lg shadow-lg py-10">
    <h2 class="text-4xl font-extrabold text-center mb-6 uppercase tracking-widest text-blue-700">Menu Kantin</h2>

<!-- FILTER KATEGORI -->
<div class="flex justify-center mb-6 px-4">
    <div class="relative inline-flex">
        <span class="inline-flex divide-x divide-gray-300 overflow-hidden rounded border border-gray-300 bg-white shadow-sm">
            <button
                type="button"
                class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 hover:text-gray-900 focus:relative"
                aria-haspopup="true"
                aria-expanded="false"
            >
                <?php echo empty($selected_category) ? "Semua Kategori" : htmlspecialchars($selected_category); ?>
            </button>

            <button
                type="button"
                class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 hover:text-gray-900 focus:relative"
                aria-label="Menu"
                onclick="document.getElementById('categoryDropdown').classList.toggle('hidden')"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-4"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
        </span>

        <div
            id="categoryDropdown"
            role="menu"
            class="absolute left-1/2 transform -translate-x-1/2 top-12 z-50 w-56 overflow-hidden rounded border border-gray-300 bg-white shadow-sm hidden"
            >
            <a
                href="?category="
                class="block px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 hover:text-gray-900"
                role="menuitem"
            >
                Semua Kategori
            </a>
            <?php while ($category = $categories_result->fetch_assoc()): ?>
                <a
                    href="?category=<?= urlencode($category['category']) ?>"
                    class="block px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 hover:text-gray-900"
                    role="menuitem"
                    <?= $selected_category == $category['category'] ? 'style="background-color: #E5E7EB;"' : '' ?>
                >
                    <?= htmlspecialchars($category['category']) ?>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- SEARCH FORM -->
<div class="flex justify-center mb-6 px-4">
    <form method="GET" class="flex w-full max-w-md space-x-2">
        <input type="hidden" name="category" value="<?= htmlspecialchars($selected_category) ?>">
        <input type="text" name="search" placeholder="Cari menu..." 
            class="px-4 py-2 border border-gray-300 rounded-md w-full focus:ring-2 focus:ring-blue-400 focus:outline-none transition">

        <button type="submit"
            class="inline-flex items-center gap-2 px-4 py-2 
                   bg-gradient-to-r from-blue-500 to-indigo-600 
                   text-white font-semibold rounded-md 
                   hover:shadow-md hover:scale-105 active:scale-95 
                   transition-all duration-300 ease-in-out ring-1 ring-blue-400">
            
            <!-- Ikon cari -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.5 10.5a7.5 7.5 0 0013.65 6.15z" />
            </svg>

            Cari
        </button>
    </form>
</div>
<!-- produk dan harga -->
<div id="menuContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-4 opacity-0 scale-90 transition-all duration-700 ease-out">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($menu = $result->fetch_assoc()): ?>
            <div class="relative bg-white/50 border border-gray-200 rounded-xl shadow-lg overflow-hidden transform transition duration-500 hover:scale-105 hover:shadow-2xl group backdrop-blur-lg">
                <div class="relative w-full h-64 overflow-hidden rounded-t-xl">
                    <img src="../<?= !empty($menu['image']) ? htmlspecialchars($menu['image']) : 'default.jpg' ?>" 
                         alt="<?= htmlspecialchars($menu['name']) ?>" 
                         class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110">
                </div>
                <div class="p-6 text-gray-900 flex flex-col flex-grow text-center">
                <h3 class="text-2xl font-semibold truncate group-hover:text-blue-600 transition-colors duration-300">
    <?= htmlspecialchars($menu['name']) ?>
</h3>
                    <p class="text-lg text-green-600 font-bold mt-2">Rp <?= number_format($menu['price'], 0, ',', '.') ?></p>
<!-- Deskripsi selalu ditampilkan -->
<p class="text-sm text-gray-700 mt-2 line-clamp-2">
    <?= !empty($menu['description']) ? nl2br(htmlspecialchars($menu['description'])) : 'Tidak ada deskripsi'; ?>
</p>

<!-- Kondisi Stok -->
<?php if ($menu['stock'] > 0): ?>
    <p class="text-sm font-medium <?= $menu['stock'] < 11 ? 'text-red-600' : 'text-gray-700' ?>">
        Stok: <?= $menu['stock'] ?>
        <?php if ($menu['stock'] < 5): ?>
            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-600 text-xs rounded-full">⚠ Hampir Habis</span>
        <?php endif; ?>
    </p>

    <!-- Tombol Pesan -->
    <a href="order.php?id=<?= $menu['id'] ?>" 
       class="mt-4 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white text-center rounded-lg font-bold hover:scale-110 hover:from-blue-700 hover:to-blue-500 transition-all duration-300 block text-sm">
       Pesan Sekarang
    </a>
<?php else: ?>
    <p class="text-sm text-red-500 font-semibold mt-2">Stok Habis</p>
    <button class="mt-4 px-6 py-3 bg-gray-400 text-white text-center rounded-lg font-bold cursor-not-allowed text-sm" disabled>
        Tidak Tersedia
    </button>
<?php endif; ?>

                </div>
            </div>
            
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-span-full text-center py-10">
    <p class="text-gray-600 text-lg font-semibold">Menu tidak ditemukan.</p>
</div>
    <?php endif; ?>
</div>
    <!-- Pagination -->
    <?php if (empty($selected_category) && empty($search)): ?>
    <div class="flex justify-center mt-6">
        <a href="?page=<?= max(1, $page - 1) ?>" class="px-4 py-2 border">Prev</a>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="px-4 py-2 <?= $i == $page ? 'bg-blue-500 text-white' : 'border' ?>"> <?= $i ?> </a>
        <?php endfor; ?>
        <a href="?page=<?= min($totalPages, $page + 1) ?>" class="px-4 py-2 border">Next</a>
    </div>
<?php endif; ?>
</div>
  </div>
</div>

<footer class="bg-gray-50">
  <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">

      <p class="mt-4 text-center text-sm text-gray-500 lg:mt-0 lg:text-right">
        Copyright &copy; 2025. Dapoer Ifsu.
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

    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            document.getElementById("menuContainer").classList.remove("opacity-0", "scale-90");
        }, 500); // Delay agar terasa lebih smooth setelah login
    });

    function filterCategory() {
        let selectedCategory = document.getElementById("categoryFilter").value;
        window.location.href = "?category=" + encodeURIComponent(selectedCategory);
    }


</script>
<a href="https://wa.me/08586270297" target="_blank" class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg flex items-center space-x-2 transition hover:scale-110 hover:bg-green-600">
    <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp" class="w-6 h-6">
    <span class="hidden md:inline font-semibold">Hubungi Admin</span>
</a>


</html>