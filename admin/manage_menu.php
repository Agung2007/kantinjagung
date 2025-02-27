<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
include('db_connection.php');

// Menambahkan menu baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_menu'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = null;

    // Mengecek apakah ada gambar yang di-upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../images/'; // Folder penyimpanan
        $image_name = time() . '_' . basename($_FILES['image']['name']); // Rename untuk mencegah nama duplikat
        $target_file = $upload_dir . $image_name;

        // Pindahkan file ke folder images
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = 'images/' . $image_name; // Simpan path relatif ke database
        }
    }
  

    // Query untuk menambahkan menu ke database
    if (!empty($image)) {
        $sql = "INSERT INTO menu (name, price, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $name, $price, $image); // "sis" → String, Integer, String
        $stmt->execute();

        // Redirect ke halaman manage menu
        header("Location: manage_menu.php");
        exit;
    } else {
        echo "Gagal mengupload gambar.";
    }
}
// Mengambil daftar menu dari database
$sql = "SELECT * FROM menu";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    <a href="admin_chat.php"
                        class="flex items-center p-2 gap-3 rounded-lg hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z"/>
                        </svg> Chat User
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
        <div class="flex-1 p-8">
            <h2 class="text-3xl font-semibold text-gray-700 mb-6">Manage Menu</h2>

            <!-- Form untuk menambah menu baru -->
            <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Menu Name</label>
                        <input type="text" name="name" id="name"
                            class="w-full p-3 mt-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" name="price" id="price"
                            class="w-full p-3 mt-2 border border-gray-300 rounded-md" required>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Image (Optional)</label>
                    <input type="file" name="image" id="image"
                        class="w-full p-3 mt-2 border border-gray-300 rounded-md">
                </div>
                <div class="mt-6">
                    <button type="submit" name="add_menu"
                        class="w-full py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors">Add
                        Menu</button>
                </div>
            </form>

            <!-- Tabel Daftar Menu -->
            <div class="overflow-x-auto bg-white p-6 rounded-lg shadow-md">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Menu Name</th>
                            <th class="px-4 py-3">Price</th>
                            <th class="px-4 py-3">Image</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$no = 1;
while ($row = $result->fetch_assoc()) {
    // Pastikan path gambar benar (gunakan htmlspecialchars untuk keamanan)
    $image_path = !empty($row['image']) ? htmlspecialchars($row['image']) : 'assets/default.jpg';

    echo "<tr class='border-b'>
        <td class='px-4 py-3 text-center'>{$no}</td>
        <td class='px-4 py-3'>{$row['name']}</td>
        <td class='px-4 py-3'>Rp " . number_format($row['price'], 0, ',', '.') . "</td>
        <td class='px-4 py-3'>
            <img src='../{$image_path}' alt='Menu Image' class='w-16 h-16 object-cover rounded-md border'>
        </td>
        <td class='px-4 py-3 text-center'>
            <a href='edit_menu.php?id={$row['id']}' class='text-blue-600 hover:underline font-medium'>Edit</a> |
            <a href='delete_menu.php?id={$row['id']}' class='text-red-600 hover:underline font-medium' onclick='return confirm(\"Apakah yakin ingin menghapus?\")'>Delete</a>
        </td>
    </tr>";

    $no++;
}
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>