<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include('db_connection.php');

$success_message = $error_message = "";

// Tambah kategori
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $success_message = "Kategori berhasil ditambahkan!";
        } else {
            $error_message = "Gagal menambahkan kategori!";
        }
    }
}

// Ambil daftar kategori
$result = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">

</head>
<body class="bg-gray-50">
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
        <div class="flex-1 p-8 ml-64">
            <h2 class="text-3xl font-semibold text-gray-700 mb-6">Kelola Kategori</h2>

<!-- Modal Form Tambah Kategori -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div id="categoryModalContent"
        class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl transform scale-95 opacity-0 transition-all duration-300">
        
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Tambah Kategori</h2>
        
        <form method="POST">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="name" id="name" placeholder="Contoh: Minuman, Makanan, Snack..."
                class="w-full p-3 mt-2 border border-gray-300 rounded-lg 
                       focus:outline-none focus:ring-2 focus:ring-blue-500 
                       transition duration-200 shadow-sm" required>

                <div class="mt-6 flex justify-end gap-2">
    <!-- Tombol Batal -->
    <button type="button" onclick="closeModal()"
        class="inline-flex items-center gap-1 px-4 py-2 
               bg-gradient-to-r from-gray-200 to-gray-300 
               text-gray-800 font-medium rounded-lg 
               hover:shadow-md hover:scale-105 active:scale-95 
               transition-all duration-300 ease-in-out ring-1 ring-gray-400">
        <!-- Ikon X -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12" />
        </svg>
        Batal
    </button>

    <!-- Tombol Simpan -->
    <button type="submit" name="add_category"
        class="inline-flex items-center gap-1 px-5 py-2 
               bg-gradient-to-r from-blue-600 to-indigo-500 
               text-white font-semibold rounded-lg 
               hover:shadow-md hover:scale-105 active:scale-95 
               transition-all duration-300 ease-in-out ring-1 ring-blue-400">
        <!-- Ikon Simpan -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 13l4 4L19 7" />
        </svg>
        Simpan
    </button>
</div>
        </form>
    </div>
</div>

            <!-- Tombol buka modal -->
            <div class="flex justify-end mb-4">
            <button onclick="openModal()"
    class="inline-flex items-center gap-2 px-6 py-2.5 
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
    
    Tambah Kategori
</button>

</div>


            <!-- Tabel Kategori -->
            <div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
        <thead class="bg-blue-500 text-white text-left">
            <tr>
                <th class="px-6 py-3">No</th>
                <th class="px-6 py-3">Nama Kategori</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = $result->fetch_assoc()) {
            ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="px-6 py-3 text-gray-700"><?= $no ?></td>
                    <td class="px-6 py-3 text-gray-700"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="text-center">
    <div class="flex justify-center gap-2">
        <!-- Tombol Edit -->
        <a href="edit_category.php?id=<?= $row['id'] ?>">
            <button class="flex items-center gap-1 px-2 py-1 
                           bg-blue-500 text-white text-sm 
                           rounded-md hover:bg-blue-600 
                           transition-all duration-200 shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" 
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
                Edit
            </button>
        </a>

        <!-- Tombol Hapus -->
        <button onclick="confirmDelete(<?= $row['id'] ?>)" 
            class="flex items-center gap-1 px-2 py-1 
                   bg-red-500 text-white text-sm 
                   rounded-md hover:bg-red-600 
                   transition-all duration-200 shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" 
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
            </svg>
            Hapus
        </button>
    </div>
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

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: "Apakah yakin ingin menghapus kategori ini?",
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `delete_category.php?id=${id}`;
                }
            });
        }

    // Menampilkan alert jika berhasil atau gagal
    <?php if (!empty($success_message)): ?>
        Swal.fire({
            title: "Berhasil!",
            text: "<?= $success_message ?>",
            icon: "success",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "manage_categories.php"; // Refresh halaman setelah sukses
        });
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        Swal.fire({
            title: "Gagal!",
            text: "<?= $error_message ?>",
            icon: "error",
            confirmButtonText: "OK"
        });
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        Swal.fire({
            title: "Berhasil!",
            text: "<?= $_SESSION['success_message'] ?>",
            icon: "success",
            confirmButtonText: "OK"
        });
        <?php unset($_SESSION['success_message']); // Hapus session setelah alert muncul ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        Swal.fire({
            title: "Gagal!",
            text: "<?= $_SESSION['error_message'] ?>",
            icon: "error",
            confirmButtonText: "OK"
        });
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        Swal.fire({
            title: "Berhasil!",
            text: "<?= $_SESSION['success_message'] ?>",
            icon: "success",
            confirmButtonText: "OK"
        });
        <?php unset($_SESSION['success_message']); // Hapus session setelah alert muncul ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        Swal.fire({
            title: "Gagal!",
            text: "<?= $_SESSION['error_message'] ?>",
            icon: "error",
            confirmButtonText: "OK"
        });
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    function openModal() {
    document.getElementById('categoryModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

function openModal() {
        const modal = document.getElementById('categoryModal');
        const content = document.getElementById('categoryModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('categoryModal');
        const content = document.getElementById('categoryModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }



    </script>
</body>
</html>
