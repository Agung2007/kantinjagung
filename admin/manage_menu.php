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
$sql = "SELECT * FROM menu";
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
        <!-- Logo Kantin -->
            <div class="flex items-center justify-center mb-6">
                <img src="../assets/images/ifsu.png" alt="Kantin Logo" class="w-24 h-24 object-cover rounded-full">
            </div>
            <h2 class="text-3xl font-bold mb-6 text-center">DAPOER IFSU</h2>
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
                                d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
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
                            d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
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
                                d="m7.875 14.25 1.214 1.942a2.25 2.25 0 0 0 1.908 1.058h2.006c.776 0 1.497-.4 1.908-1.058l1.214-1.942M2.41 9h4.636a2.25 2.25 0 0 1 1.872 1.002l.164.246a2.25 2.25 0 0 0 1.872 1.002h2.092a2.25 2.25 0 0 0 1.872-1.002l.164-.246A2.25 2.25 0 0 1 16.954 9h4.636M2.41 9a2.25 2.25 0 0 0-.16.832V12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 12V9.832c0-.287-.055-.57-.16-.832M2.41 9a2.25 2.25 0 0 1 .382-.632l3.285-3.832a2.25 2.25 0 0 1 1.708-.786h8.43c.657 0 1.281.287 1.709.786l3.284 3.832c.163.19.291.404.382.632M4.5 20.25h15A2.25 2.25 0 0 0 21.75 18v-2.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V18a2.25 2.25 0 0 0 2.25 2.25Z" />
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
        class="px-7 py-3 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-500 transition-all duration-300">
        + Tambah Menu
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
            <!-- Form tambah menu -->
            <form method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Menu</label>
                        <input type="text" name="name" id="name"
                            class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
                            required>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="number" name="price" id="price"
                            class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
                            required>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category" id="category"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
                        required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" name="stock" id="stock" min="0"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
                        required>
                </div>

                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="description" id="description"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
                        rows="3"></textarea>
                </div>

                <div class="mt-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Gambar (Opsional)</label>
                    <input type="file" name="image" id="image"
                        class="w-full mt-2 p-3 border border-gray-300 rounded-lg">
                </div>

                <div class="mt-6">
                    <button type="submit" name="add_menu"
                        class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-500 transition">
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
    <a href="edit_menu.php?id=<?= $row['id'] ?>" class="w-full">
        <button class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
            Edit
        </button>
    </a>
    <button onclick="confirmDelete(<?= $row['id'] ?>)" 
        class="w-full px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-200">
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