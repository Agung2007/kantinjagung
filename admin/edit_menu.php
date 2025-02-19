<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
include('db_connection.php');

// Mengambil ID menu dari URL
$id = $_GET['id'] ?? null;

if ($id) {
    // Query untuk mengambil data menu berdasarkan ID
    $sql = "SELECT * FROM menu WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $menu = $result->fetch_assoc();
    } else {
        echo "Menu not found!";
        exit;
    }
}

// Update data menu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_menu'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $menu['image']; // Mempertahankan gambar lama jika tidak ada yang di-upload

    // Mengecek apakah ada gambar baru yang di-upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = 'images/' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    // Query untuk memperbarui data menu
    $sql = "UPDATE menu SET name = ?, price = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $name, $price, $image, $id);
    $stmt->execute();

    header("Location: manage_menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar (Similar to the previous sidebar code) -->

        <!-- Main Content -->
        <div class="flex-1 p-10">
            <div class="bg-white p-6 rounded-lg shadow-xl mb-8">
                <h2 class="text-3xl font-semibold text-gray-700 mb-4">Edit Menu</h2>

                <!-- Form untuk mengedit menu -->
                <form method="POST" enctype="multipart/form-data">
                    <div class="flex space-x-4 mb-6">
                        <input type="text" name="name" value="<?= $menu['name'] ?>" required class="w-1/2 p-2 border border-gray-300 rounded-md">
                        <input type="number" name="price" value="<?= $menu['price'] ?>" required class="w-1/2 p-2 border border-gray-300 rounded-md">
                        <input type="file" name="image" class="w-1/2 p-2 border border-gray-300 rounded-md">
                        <button type="submit" name="update_menu" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update Menu</button>
                    </div>
                </form>

                <div class="mt-6">
                    <h3 class="text-xl font-semibold text-gray-700">Current Image:</h3>
                    <img src="<?= $menu['image'] ?>" alt="Current Menu Image" class="w-32 h-32 object-cover mt-2">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
