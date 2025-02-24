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
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center text-white">
            <!-- Logo Kantin -->
            <div class="flex items-center space-x-4">
                <img src="../assets/images/logo.jpg" alt="Logo Kantin" class="h-12 w-12 rounded-full">
                <h1 class="text-2xl font-semibold">Kantin IFSU</h1>
            </div>

            <!-- Profil Pengguna & Order History -->
            <div class="flex items-center space-x-6">
                <?php if ($user): ?>
                    <!-- Order History -->
                    <a href="order_history.php" class="flex items-center space-x-2 hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-5m-3-4h3m-3 0V6m0 4V6m0 4l-3-3m3 3l3-3"></path>
                        </svg>
                        <span>Order History</span>
                    </a>

                    <!-- Profil -->
                    <a href="profile.php" class="flex items-center space-x-2 hover:underline">
                        <img src="<?= htmlspecialchars($user['profile_picture'] ?? '../assets/images/default-avatar.png') ?>" alt="Profile" class="h-10 w-10 rounded-full object-cover">
                        <span><?= htmlspecialchars($user['username']) ?></span>
                    </a>
                <?php endif; ?>
                
                <a href="logout.php" class="px-4 py-2 bg-red-600 rounded-lg hover:bg-red-700">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-3xl font-bold text-center mb-8">Menu Makanan</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($menu = $result->fetch_assoc()): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <img src="../<?= htmlspecialchars($menu['image']) ?>" alt="<?= htmlspecialchars($menu['name']) ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($menu['name']) ?></h3>
                        <p class="text-xl text-green-600 font-semibold mt-2">Rp <?= number_format($menu['price'], 0, ',', '.') ?></p>
                        <a href="order.php?id=<?= $menu['id'] ?>" class="block mt-4 px-4 py-2 bg-blue-500 text-white text-center rounded-lg hover:bg-blue-600 transition-colors">Pesan Sekarang</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>
