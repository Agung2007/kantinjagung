<?php
session_start();

// Cek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Menyertakan koneksi database
include('db_connection.php');

// Cek apakah ada ID pengguna yang dikirimkan untuk di-edit
if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$user_id = $_GET['id'];

// Mengambil data pengguna dari database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Proses pembaruan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Update data pengguna
    $update_sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $username, $role, $user_id);
    $stmt->execute();
    
    // Set session notifikasi
    $_SESSION['update_success'] = true;
    header("Location: edit_user.php?id=$user_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <h2 class="text-2xl font-bold text-center mb-6">Edit User</h2>
            <form method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="role" name="role" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                    </select>
                </div>
                <div class="flex justify-between gap-4">
    <!-- Tombol Update User -->
    <button type="submit"
        class="w-full flex items-center justify-center gap-2 py-2 
               bg-gradient-to-r from-blue-500 to-indigo-600 
               text-white font-semibold rounded-lg 
               hover:shadow-md hover:scale-105 active:scale-95 
               transition-all duration-300 ease-in-out ring-1 ring-blue-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 13l4 4L19 7" />
        </svg>
        Update User
    </button>

    <!-- Tombol Batal -->
    <a href="manage_users.php"
        class="w-full flex items-center justify-center gap-2 py-2 
               bg-gradient-to-r from-gray-300 to-gray-400 
               text-gray-800 font-semibold rounded-lg 
               hover:shadow-md hover:scale-105 active:scale-95 
               transition-all duration-300 ease-in-out ring-1 ring-gray-400 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12" />
        </svg>
        Batal
    </a>
</div>
            </form>
        </div>
    </div>
</body>
    <?php if (isset($_SESSION['update_success'])): ?>
        <script>
            Swal.fire({
                title: 'Berhasil!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'manage_users.php';
            });
        </script>
        <?php unset($_SESSION['update_success']); ?>
    <?php endif; ?>
</body>
</html>
