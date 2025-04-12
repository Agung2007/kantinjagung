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
        echo "Menu tidak ditemukan!";
        exit;
    }
}

// Ambil daftar kategori dari tabel categories
$categories = [];
$category_query = "SELECT * FROM categories";
$category_result = $conn->query($category_query);

while ($cat = $category_result->fetch_assoc()) {
    $categories[] = $cat;
}

// Update data menu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_menu'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $price = doubleval($_POST['price']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description'] ?? ''); // Menambahkan deskripsi
    $image = $_POST['old_image']; // Gunakan gambar lama jika tidak ada yang baru

    // Periksa apakah ada gambar baru yang di-upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/";
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
                $image = "images/" . $new_filename;
            } else {
                echo "Gagal mengunggah gambar.";
                exit;
            }
        } else {
            echo "Format gambar tidak didukung! Gunakan JPG, JPEG, PNG, atau GIF.";
            exit;
        }
    }

    // Update ke database (dengan deskripsi)
    $sql = "UPDATE menu SET name = ?, price = ?, category = ?, description = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsssi", $name, $price, $category, $description, $image, $id);

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
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <div class="flex-1 p-10">

            <div class="bg-white p-6 rounded-lg shadow-xl mb-8 relative">
    <!-- Tombol X untuk kembali -->
    <a href="manage_menu.php" class="absolute top-4 right-4 text-gray-500 hover:text-red-500 transition duration-200" title="Kembali">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </a>

    <h2 class="text-3xl font-semibold text-gray-700 mb-4">Edit Menu</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($menu['id']) ?>">
    <input type="hidden" name="old_image" value="<?= htmlspecialchars($menu['image']) ?>">

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Menu</label>
        <input type="text" name="name" id="name" 
               placeholder="Contoh: Ayam Geprek" 
               value="<?= htmlspecialchars($menu['name']) ?>" 
               required
               class="w-full p-3 border border-gray-300 rounded-lg 
                      focus:ring-2 focus:ring-blue-400 outline-none shadow-sm">
    </div>

    <div class="mb-4">
        <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
        <input type="number" name="price" id="price" 
               placeholder="Contoh: 15000"
               value="<?= htmlspecialchars($menu['price']) ?>" 
               required
               class="w-full p-3 border border-gray-300 rounded-lg 
                      focus:ring-2 focus:ring-blue-400 outline-none shadow-sm">
    </div>

    <div class="mb-4">
        <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
        <select name="category" id="category"
                class="w-full p-3 border border-gray-300 rounded-lg 
                       focus:ring-2 focus:ring-blue-400 outline-none shadow-sm">
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['name']) ?>" 
                        <?= $cat['name'] == $menu['category'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="description" id="description" rows="4"
                  placeholder="Deskripsi singkat menu..."
                  class="w-full p-3 border border-gray-300 rounded-lg 
                         focus:ring-2 focus:ring-blue-400 outline-none shadow-sm"><?= htmlspecialchars($menu['description'] ?? '') ?></textarea>
    </div>

    <div class="mb-4">
        <label for="image" class="block text-sm font-medium text-gray-700">Gambar Menu</label>
        <input type="file" name="image" id="image"
               class="w-full p-3 border border-gray-300 rounded-lg 
                      focus:ring-2 focus:ring-blue-400 outline-none shadow-sm"
               onchange="previewImage(event)">
    </div>

    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-700">Gambar Saat Ini:</h3>
        <img id="preview" src="/<?= htmlspecialchars($menu['image']) ?>" 
             alt="Menu Image" 
             class="w-32 h-32 object-cover border rounded-md mt-2" 
             onerror="this.onerror=null; this.src='/images/default.png';">
        <p class="text-sm text-gray-500 mt-1">Path: <?= htmlspecialchars($menu['image']) ?></p>
    </div>

    <button type="submit" name="update_menu"
        class="inline-flex items-center gap-2 px-6 py-3 
               bg-gradient-to-r from-blue-500 to-indigo-600 
               text-white font-semibold rounded-lg 
               hover:shadow-lg hover:scale-105 active:scale-95 
               transition-all duration-300 ease-in-out ring-1 ring-blue-400">
        
        <!-- Ikon edit -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" 
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
        </svg>

        Update Menu
    </button>
</form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
