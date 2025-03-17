<?php
session_start();
include('db_connection.php');

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $transaction_id = $_POST['transaction_id'];
    $status = $_POST['status'];

    // Validasi input
    if (!in_array($status, ['pending', 'processed', 'completed', 'canceled'])) {
        die("Status tidak valid!");
    }

    // Update status di database
    $stmt = $conn->prepare("UPDATE transactions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $transaction_id);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Status berhasil diperbarui!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = 'transactions.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal memperbarui status!',
                text: '" . $stmt->error . "',
            });
        </script>";
    }
    
    $stmt->close();
}

// Ambil data transaksi
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Query dasar
$query = "SELECT t.id, u.username AS user_name, 
       GROUP_CONCAT(m.name SEPARATOR ', ') AS menu_name, 
       SUM(od.quantity) AS total_quantity, 
       t.total_price, t.status, t.payment_method, o.order_date AS created_at
FROM transactions t
JOIN users u ON t.user_id = u.id
JOIN orders o ON t.order_id = o.id
JOIN order_details od ON o.id = od.order_id
JOIN menu m ON od.menu_id = m.id
WHERE t.status IN ('pending', 'processed', 'completed', 'canceled')";

// Tambahkan kondisi pencarian jika ada
$search_param = "%$search%";
$params = [];
$types = "";

if (!empty($search)) {
    $query .= " AND (u.username LIKE ? OR m.name LIKE ? OR t.payment_method LIKE ?)";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

// Tambahkan filter berdasarkan rentang tanggal jika diisi
if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND (o.order_date BETWEEN ? AND ?)";
    $params[] = $start_date;
    $params[] = $end_date;
    $types .= "ss";
}

$query .= " GROUP BY t.id, u.username, t.total_price, t.status, t.payment_method, o.order_date
ORDER BY o.order_date DESC";

// Eksekusi query dengan parameter yang sesuai
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
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

</head>

<body class="bg-gray-50">
    <!-- Sidebar and Dashboard Container -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-900 text-white p-6 fixed h-screen">
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
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
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
            <h2 class="text-3xl font-semibold text-gray-700 mb-6">Transaksi</h2>
            <form method="GET" class="mb-4 flex items-center space-x-2">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari transaksi..." class="px-4 py-2 border rounded w-1/3">
    
    <label for="start_date" class="text-gray-700">Dari:</label>
    <input type="date" name="start_date" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>" class="px-4 py-2 border rounded">

    <label for="end_date" class="text-gray-700">Sampai:</label>
    <input type="date" name="end_date" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>" class="px-4 py-2 border rounded">

    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Cari</button>
</form>
            <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Pelanggan</th>
                        <th class="py-3 px-4 text-left">Menu</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                        <th class="py-3 px-4 text-left">Total Harga</th>
                        <th class="py-3 px-4 text-left">Metode Pembayaran</th> <!-- Tambahan -->
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
    $no = 1;
    while ($row = $result->fetch_assoc()) { ?>
                    <tr class="border-b">
                        <td class="py-3 px-4"> <?= $no ?> </td>
                        <td class="py-3 px-4"> <?= htmlspecialchars($row['user_name']) ?> </td>
                        <td class="py-3 px-4"> <?= htmlspecialchars($row['menu_name']) ?> </td>
                        <td class="py-3 px-4"> <?= $row['total_quantity'] ?> </td>
                        <td class="py-3 px-4"> Rp<?= number_format($row['total_price'], 0, ',', '.') ?> </td>
                        <td class="py-3 px-4">
                            <?php
            $payment_method = htmlspecialchars($row['payment_method']);
            $payment_logo = '';

            // Menentukan logo berdasarkan metode pembayaran
            if ($payment_method == 'Dana') {
                $payment_logo = '../assets/images/icon_dana.png';
            } elseif ($payment_method == 'ShopeePay') {
                $payment_logo = '../assets/images/pay.png';
            } elseif ($payment_method == 'COD') {
                $payment_logo = '../assets/images/cod.png';
            }

            // Menampilkan logo jika ada
            if (!empty($payment_logo)) {
                echo "<img src='$payment_logo' alt='$payment_method' class='w-10 h-10 inline'>";
            }
            echo " $payment_method";
            ?>
                        </td>
                        <td class="py-3 px-4"> <?= htmlspecialchars($row['status']) ?> </td>
                        <td class="py-3 px-4"> <?= $row['created_at'] ?> </td>
                        <td class="py-3 px-4">
    <?php if ($row['status'] != 'canceled') { ?>
        <form method="POST" class="flex space-x-2">
            <input type="hidden" name="transaction_id" value="<?= $row['id'] ?>">
            <select name="status" class="border rounded px-2 py-1">
                <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="processed" <?= $row['status'] == 'processed' ? 'selected' : '' ?>>Processed</option>
                <option value="completed" <?= $row['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>
            <button type="submit" name="update_status" class="bg-green-500 text-white px-3 py-1 rounded">Update</button>
        </form>
    <?php } else { ?>
        <span class="text-red-500 font-bold">Canceled</span>
    <?php } ?>
</td>
                    </tr>
                    <?php 
        $no++;
} 
?>
                </tbody>
            </table>
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
</script>


</html>