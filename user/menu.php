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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-600 to-purple-800 min-h-screen text-white">

    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-indigo-600 to-purple-800 p-5 shadow-lg font-semibold fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img src="../assets/images/ifsu.png" alt="Logo Kantin"
                    class="h-12 w-12 rounded-full border-2 border-white">
                <h1 class="text-3xl font-extrabold tracking-wide text-yellow-300">Kantin IFSU BERKAH</h1>
            </div>
            <div class="flex items-center space-x-6">
                <?php if ($user): ?>
                <a href="order_history.php"
                    class="flex items-center space-x-2 text-white hover:text-yellow-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span>Order History</span>
                </a>
                <a href="chat.php"
                    class="flex items-center space-x-2 text-white hover:text-yellow-300 transition bg-green-600 px-4 py-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                    <span>Chat Admin</span>
                </a>
                <a href="profile.php" class="flex items-center space-x-2 text-white hover:text-yellow-300 transition">
                    <img src="<?= htmlspecialchars($user['profile_picture'] ?? '../assets/images/avatar.jpeg') ?>"
                        alt="Profile" class="h-10 w-10 rounded-full border-2 border-white object-cover">
                    <span><?= htmlspecialchars($user['username']) ?></span>
                </a>
                <?php endif; ?>
                <a href="logout.php"
                    class="px-5 py-2 bg-red-600 rounded-full hover:bg-red-700 transition font-bold">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto p-6 mt-20">
        <h2 class="text-4xl font-bold text-center mb-8 uppercase tracking-widest">Menu kantin</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php while ($menu = $result->fetch_assoc()): ?>
            <div
                class="card bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-all flex flex-col h-full">
                <img src="../<?= htmlspecialchars($menu['image']) ?>" alt="<?= htmlspecialchars($menu['name']) ?>"
                    class="w-full h-52 object-cover">
                <div class="p-6 text-gray-900 flex flex-col flex-grow">
                    <div class="flex-grow">
                        <h3 class="text-2xl font-semibold"><?= htmlspecialchars($menu['name']) ?></h3>
                        <p class="text-lg text-green-600 font-bold mt-2">Rp
                            <?= number_format($menu['price'], 0, ',', '.') ?></p>
                    </div>
                    <a href="order.php?id=<?= $menu['id'] ?>"
                        class="mt-4 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-center rounded-lg font-bold hover:opacity-90 transition">
                        Pesan Sekarang
                    </a>


                </div>
            </div>
            <?php endwhile; ?>
        </div>

    </div>