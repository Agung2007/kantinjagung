<?php
include('db_connection.php');
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$query = "SELECT id, name, stock, image FROM menu";

if (!empty($search)) {
    $query .= " WHERE name LIKE '%$search%'";
}

$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Stok</title>
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
        <div class="flex-1 ml-64 p-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-3xl font-semibold text-gray-700 mb-4">Kelola Stok Menu</h2>
                <form method="GET" class="mb-6 flex items-center justify-start gap-2 w-full md:w-1/2">
  <div class="relative w-full">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
      <!-- Ikon Search -->
      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg>
    </span>
    <input type="text" name="search" placeholder="Cari nama menu..."
      value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
      class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" />
    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-blue-500">
      <!-- Ikon Tombol Search -->
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg>
    </button>
  </div>
</form>

                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-300 rounded-lg">
                    <thead class="bg-blue-500 text-white">
    <tr>
        <th class="border border-gray-300 px-6 py-3 text-left">Image</th>
        <th class="border border-gray-300 px-6 py-3 text-left">Nama Menu</th>
        <th class="border border-gray-300 px-6 py-3 text-center">Stok</th>
        <th class="border border-gray-300 px-6 py-3 text-center">Aksi</th>
    </tr>
</thead>
<tbody class="bg-gray-50">
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr class="hover:bg-gray-100">
        <td class="border border-gray-300 px-4 py-3 text-center">
            <img src="../<?= htmlspecialchars($row['image']) ?>" 
                 alt="<?= htmlspecialchars($row['name']) ?>" 
                 class="w-14 h-14 object-cover rounded-md mx-auto shadow" />
        </td>
        <td class="border border-gray-300 px-6 py-3"><?= htmlspecialchars($row['name']) ?></td>
        <td class="border border-gray-300 px-6 py-3 text-center">
            <input type="number" id="stock-<?= $row['id'] ?>" value="<?= $row['stock'] ?>" min="0"
                class="w-20 p-2 border border-gray-300 rounded text-center focus:outline-none focus:ring focus:ring-blue-300">
        </td>
        <td class="border border-gray-300 px-6 py-2 text-center align-middle">
            <button onclick="updateStock(<?= $row['id'] ?>)"
                class="inline-flex items-center gap-1 px-3 py-1.5 
                       bg-gradient-to-r from-blue-500 to-indigo-500 
                       text-white text-sm font-medium rounded 
                       shadow hover:shadow-md 
                       hover:scale-105 active:scale-95 
                       transition-all duration-300 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" 
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582M20 20v-5h-.581M5.636 18.364A9 9 0 105.637 5.636M19 12h2M12 19v2" />
                </svg>
                Update
            </button>
        </td>
    </tr>
    <?php } ?>
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateStock(menuId) {
            let stockValue = document.getElementById(`stock-${menuId}`).value;

            fetch('update_stock.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `menu_id=${menuId}&stock=${stockValue}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === "success") {
                    Swal.fire("Berhasil!", "Stok diperbarui!", "success");
                } else {
                    Swal.fire("Gagal!", "Gagal memperbarui stok.", "error");
                }
            });
        }
    </script>
</body>

</html>
