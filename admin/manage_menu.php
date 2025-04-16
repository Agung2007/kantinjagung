<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
include('db_connection.php');

// Ambil daftar kategori dari database
$categories = [];
$category_query = "SELECT name FROM categories";
$category_result = $conn->query($category_query);

if (!$category_result) {
    die("Query Error: " . $conn->error);
}

while ($cat = $category_result->fetch_assoc()) {
    $categories[] = $cat['name']; // Sesuaikan dengan nama kolom di tabel categories
}


// Menambahkan menu baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_menu'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock = $_POST['stock'];
    $description = $_POST['description']; // Ambil deskripsi
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../images/';
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = 'images/' . $image_name;
        }
    }

    if (!empty($image)) {
        $sql = "INSERT INTO menu (name, price, category, stock, description, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissss", $name, $price, $category, $stock, $description, $image);
    } else {
        $sql = "INSERT INTO menu (name, price, category, stock, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisss", $name, $price, $category, $stock, $description);
    }

    if ($stmt->execute()) {
        $success_message = "Menu berhasil ditambahkan!";
    }
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM menu";

if (!empty($search)) {
    $search_safe = $conn->real_escape_string($search);
    $sql .= " WHERE name LIKE '%$search_safe%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu</title>
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gray-50">
    <?php if (!empty($success_message)) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: "success",
                title: "Sukses!",
                text: "<?php echo $success_message; ?>",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            });
        });
    </script>
    <?php endif; ?>
    <!-- Sidebar and Dashboard Container -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-900 text-white p-6 fixed h-screen">
<!-- Logo dan Judul -->
<div class="flex items-center justify-center mb-4 gap-2">
    <!-- Gambar Logo -->
    <img src="../assets/images/ifsu.png" alt="Kantin Logo" class="w-16 h-16 object-cover rounded-full">

    <!-- Judul -->
    <h1 class="text-xl font-bold leading-tight">DAPOER IFSU</h1>
</div>

<!-- Divider -->
<hr class="border-t border-white/30 mb-4">
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
                <a href="manage_categories.php"
    class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700 transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 7.5l9 4.5 9-4.5M3 12l9 4.5 9-4.5M3 16.5l9 4.5 9-4.5" />
    </svg>
    Kategori
</a>
                </li>

                </li>


                <a href="manage_menu.php"
    class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700 transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h3.5l1-1h2l1 1H17a2 2 0 012 2v14a2 2 0 01-2 2z" />
    </svg>
    Kelola Menu
</a>
                </li>
                <li>

                <a href="manage_stock.php"
    class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-700 transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M4.5 6.75V19.5A2.25 2.25 0 0 0 6.75 21.75h10.5A2.25 2.25 0 0 0 19.5 19.5V6.75M4.5 6.75 12 3l7.5 3.75M12 12v6m0 0 3-3m-3 3-3-3" />
    </svg>
    Kelola Stok
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
                    <a href="javascript:void(0);" onclick="confirmLogout()"
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
        <div class="flex-1 p-8 ml-64 mt-8">
    <h2 class="text-3xl font-semibold text-gray-700 mb-6">Kelola Menu</h2>

<!-- Tombol tambah menu dengan posisi kanan & spacing -->
<div class="flex justify-end mt-10 mb-6">
<button onclick="openModal()"
    class="inline-flex items-center gap-2 px-6 py-3 
           bg-gradient-to-r from-green-600 to-emerald-500 
           text-white font-semibold rounded-lg shadow-md 
           hover:shadow-lg hover:scale-105 active:scale-95 
           transition-all duration-300 ease-in-out ring-1 ring-green-400">
    
    <!-- Ikon Plus -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
         viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M12 4v16m8-8H4" />
    </svg>
    
    Tambah Menu
</button>
</div>

<!-- Modal -->
<div id="menuModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div id="menuModalContent"
        class="bg-white w-full max-w-2xl rounded-xl shadow-xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="text-xl font-bold text-gray-800">Tambah Menu Baru</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[80vh]">
        <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl shadow-lg">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700">Nama Menu</label>
            <input type="text" name="name" id="name" placeholder="Contoh: Nasi Goreng Spesial"
                class="w-full mt-2 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition duration-200"
                required>
        </div>
        <div>
            <label for="price" class="block text-sm font-semibold text-gray-700">Harga (Rp)</label>
            <input type="number" name="price" id="price" placeholder="Contoh: 15000"
                class="w-full mt-2 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition duration-200"
                required>
        </div>
    </div>

    <div class="mt-4">
        <label for="category" class="block text-sm font-semibold text-gray-700">Kategori</label>
        <select name="category" id="category"
            class="w-full mt-2 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition duration-200"
            required>
            <option value="" disabled selected>Pilih Kategori</option>
            <?php foreach ($categories as $category) : ?>
                <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mt-4">
        <label for="stock" class="block text-sm font-semibold text-gray-700">Stok</label>
        <input type="number" name="stock" id="stock" min="0" placeholder="Contoh: 20"
            class="w-full mt-2 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition duration-200"
            required>
    </div>

    <div class="mt-4">
        <label for="description" class="block text-sm font-semibold text-gray-700">Deskripsi</label>
        <textarea name="description" id="description" placeholder="Tulis deskripsi singkat menu di sini..."
            class="w-full mt-2 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition duration-200"
            rows="3"></textarea>
    </div>

    <div class="mt-4">
        <label for="image" class="block text-sm font-semibold text-gray-700">Gambar (Opsional)</label>
        <input type="file" name="image" id="image"
            class="w-full mt-2 p-3 border border-gray-300 rounded-xl file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
    </div>

    <div class="mt-6">
        <button type="submit" name="add_menu"
            class="w-full flex justify-center items-center gap-2 py-3 
                   bg-gradient-to-r from-blue-600 to-indigo-600 
                   text-white font-semibold rounded-xl 
                   hover:shadow-lg hover:scale-[1.02] active:scale-95 
                   transition-all duration-300 ease-in-out ring-1 ring-blue-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            Tambahkan Menu
        </button>
    </div>
</form>
        </div>
    </div>
</div>

<!-- Animasi Buka/Tutup Modal -->
<script>
    function openModal() {
        const modal = document.getElementById('menuModal');
        const content = document.getElementById('menuModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('menuModal');
        const content = document.getElementById('menuModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
<form method="GET" class="mb-6 flex items-center justify-start gap-2 w-full md:w-1/2">
  <div class="relative w-full">
    <input type="text" name="search" placeholder="Cari nama menu..."
      value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
      class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" />
    
    <!-- Ikon search di dalam input (kiri) -->
    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg>
    </span>

    <!-- Tombol submit dengan ikon search (kanan) -->
    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-blue-600">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg>
    </button>
  </div>
</form>
            

            <!-- Tabel Daftar Menu -->
            <div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="px-6 py-3 text-center">No</th>
                <th class="px-6 py-3 text-left">Menu Name</th>
                <th class="px-6 py-3 text-center">Kategori</th>
                <th class="px-6 py-3 text-center">Price</th>
                <th class="px-6 py-3 text-center">Image</th>
                <th class="px-6 py-3 text-center">Stock</th>
                <th class="px-6 py-3 text-left">Deskripsi</th> <!-- Tambahkan kolom Deskripsi -->
                <th class="px-6 py-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                $image_path = !empty($row['image']) ? htmlspecialchars($row['image']) : 'assets/default.jpg';
            ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="px-6 py-4 text-center"><?= $no ?></td>
                    <td class="px-6 py-4"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="px-6 py-4 text-center"><?= htmlspecialchars($row['category']) ?></td>
                    <td class="px-6 py-4 text-center">Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                    <td class="px-6 py-4 flex justify-center">
                        <img src="../<?= $image_path ?>" alt="Menu Image" class="w-16 h-16 object-cover rounded-md border">
                    </td>
                    <td class="px-6 py-4 text-center"><?= htmlspecialchars($row['stock']) ?></td>
                    <td class="px-6 py-4"><?= nl2br(htmlspecialchars($row['description'] ?? '')) ?></td>
                    <td class="px-6 py-4 text-center">
                    <div class="flex gap-2">
<!-- Tombol Edit -->
<a href="edit_menu.php?id=<?= $row['id'] ?>">
    <button class="flex items-center justify-center gap-2 px-4 py-2 
                   bg-gradient-to-r from-blue-500 to-indigo-500 
                   text-white font-semibold rounded-lg 
                   shadow-md hover:shadow-xl 
                   hover:scale-105 active:scale-95 
                   transition-all duration-300 ease-in-out 
                   ring-1 ring-blue-400 w-fit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" 
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
        </svg>
        Edit
    </button>
</a>

<!-- Tombol Delete -->
<button onclick="confirmDelete(<?= $row['id'] ?>)" 
    class="flex items-center justify-center gap-2 px-4 py-2 
           bg-gradient-to-r from-red-500 to-pink-500 
           text-white font-semibold rounded-lg 
           shadow-md hover:shadow-xl 
           hover:scale-105 active:scale-95 
           transition-all duration-300 ease-in-out 
           ring-1 ring-red-400 w-fit ml-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" 
         viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
    </svg>
    Delete
</button>
                    </td>
                </tr>
            <?php
                $no++;
            }
            ?>
        </tbody>
    </table>
</div>
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
            confirmButtonText: "Ya, logout!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout.php"; // Redirect ke logout jika dikonfirmasi
            }
        });
    }

    
    function confirmDelete(menuId) {
        Swal.fire({
            title: "Apakah yakin ingin menghapus menu ini?",
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `delete_menu.php?id=${menuId}`;
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = urlParams.get("success");
        const errorMessage = urlParams.get("error");

        if (successMessage) {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Menu berhasil dihapus.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "manage_menu.php"; // Hapus parameter dari URL
            });
        }

        if (errorMessage) {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "Menu gagal dihapus.",
                confirmButtonColor: "#d33",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "manage_menu.php"; // Hapus parameter dari URL
            });
        }
    });

    

</script>




</html>