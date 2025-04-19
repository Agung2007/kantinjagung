<?php
session_start();

// Cek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Menyertakan koneksi database
include('db_connection.php');

// Ambil data pengguna dari database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Proses hapus data jika ada parameter delete_id
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // 1. Ambil semua order_id yang dimiliki user ini
    $order_stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ?");
    $order_stmt->bind_param("i", $delete_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();

    $order_ids = [];
    while ($row = $order_result->fetch_assoc()) {
        $order_ids[] = $row['id'];
    }

    // 2. Hapus transaksi yang terkait dengan order_id tadi
    if (!empty($order_ids)) {
        $placeholders = implode(',', array_fill(0, count($order_ids), '?'));
        $types = str_repeat('i', count($order_ids));
        $query = "DELETE FROM transactions WHERE order_id IN ($placeholders)";
        $stmt_trans = $conn->prepare($query);
        $stmt_trans->bind_param($types, ...$order_ids);
        $stmt_trans->execute();
    }

    // 3. Hapus orders dari user
    $stmt_orders = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
    $stmt_orders->bind_param("i", $delete_id);
    $stmt_orders->execute();

    // 4. Baru hapus user-nya
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        header("Location: manage_users.php?deleted=success");
        exit;
    } else {
        echo "<script>alert('Gagal menghapus user!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Users</title>
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            <div class="bg-white p-6 rounded-lg shadow-xl mb-8">
                <h2 class="text-3xl font-semibold text-gray-700 mb-4">Kelola User</h2>

<!-- Tabel Pengguna -->
<div class="overflow-x-auto">
    <table class="min-w-full table-auto bg-white border border-gray-200 rounded-lg shadow-lg">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="py-3 px-4 border-b text-left">ID</th>
                <th class="py-3 px-4 border-b text-left">Username</th>
                <th class="py-3 px-4 border-b text-left">Role</th>
                <th class="py-3 px-4 border-b text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; // Inisialisasi nomor urut
            while ($user = $result->fetch_assoc()): ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-3 px-4"><?php echo $no++; ?></td>
                    <td class="py-3 px-4"><?php echo htmlspecialchars($user['username']); ?></td>
                    <td class="py-3 px-4"><?php echo ucfirst(htmlspecialchars($user['role'])); ?></td>
                    <td class="py-3 px-4 text-center">
    <?php if ($user['role'] !== 'admin'): ?>
        <div class="flex justify-center gap-2">
            <!-- Tombol Edit -->
            <a href="edit_user.php?id=<?php echo $user['id']; ?>">
                <button class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 active:scale-95 transition-all duration-200 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Edit
                </button>
            </a>

            <!-- Tombol Delete -->
            <button onclick="confirmDelete(<?php echo $user['id']; ?>)"
                class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 active:scale-95 transition-all duration-200 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4m-4 0a1 1 0 00-1 1v1h6V5a1 1 0 00-1-1m-4 0h4" />
                </svg>
                Delete
            </button>
        </div>
    <?php endif; ?>
</td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
            </div>
        </div>
    </div>
</body>

<script>
function confirmDelete(userId) {
    Swal.fire({
        title: "Apakah yakin ingin menghapus?",
        text: "Data tidak dapat dikembalikan setelah dihapus!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "hapus",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "manage_users.php?delete_id=" + userId;
        }
    });
}

// Cek apakah ada parameter deleted di URL dan tampilkan notifikasi
document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('deleted')) {
        Swal.fire({
            title: "Berhasil!",
            text: "Pengguna berhasil di dihapus.",
            icon: "success",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
        }).then(() => {
            // Hapus parameter dari URL setelah ditampilkan
            window.history.replaceState(null, null, window.location.pathname);
        });
    }
});


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

</script>

</html>