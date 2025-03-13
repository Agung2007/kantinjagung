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
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = doubleval($_POST['price']);
    $image = $_POST['old_image']; // Gunakan gambar lama jika tidak ada yang baru

    // Periksa apakah ada gambar baru yang di-upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "images/"; // Gunakan path relatif tanpa "../"
        $new_filename = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $new_filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Pastikan folder images ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Validasi tipe file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $target_file; // Simpan path baru
            } else {
                echo "Gagal mengunggah gambar.";
                exit;
            }
        } else {
            echo "Format gambar tidak didukung! Gunakan JPG, JPEG, PNG, atau GIF.";
            exit;
        }
    }

    // Update ke database
    $sql = "UPDATE menu SET name = ?, price = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsi", $name, $price, $image, $id);

    if ($stmt->execute()) {
        header("Location: manage_menu.php");
        exit;
    } else {
        echo "Gagal memperbarui menu: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <link rel="shortcut icon" href="../assets/images/images.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <div class="flex-1 p-10">
            <div class="bg-white p-6 rounded-lg shadow-xl mb-8">
                <h2 class="text-3xl font-semibold text-gray-700 mb-4">Edit Menu</h2>

                <!-- Form untuk mengedit menu -->
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($menu['id']) ?>">
                    <input type="hidden" name="old_image" value="<?= htmlspecialchars($menu['image']) ?>">

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Menu</label>
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($menu['name']) ?>" required class="w-full p-2 border border-gray-300 rounded-md">
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="number" name="price" id="price" value="<?= htmlspecialchars($menu['price']) ?>" required class="w-full p-2 border border-gray-300 rounded-md">
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700">Gambar Menu</label>
                        <input type="file" name="image" id="image" class="w-full p-2 border border-gray-300 rounded-md">
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-700">Gambar Saat Ini:</h3>
                        <!-- Path gambar diperbaiki -->
                        <img src="/kantinjagung/<?= htmlspecialchars($menu['image']) ?>" alt="Menu Image" class="w-16 h-16 object-cover rounded-md border">

                        <!-- Debugging Path -->
                        <p class="text-sm text-gray-500">Path: <?= htmlspecialchars($menu['image']) ?></p>
                    </div>

                    <button type="submit" name="update_menu" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update Menu</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
