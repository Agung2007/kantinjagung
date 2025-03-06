<?php
session_start();
include('db_connection.php');

$login_status = ""; // Variabel untuk menyimpan status login

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: menu.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!isset($username) || !isset($password)) {
        $login_status = "error|Username and password are required!";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $login_status = "success|Login Berhasil!";
            } else {
                $login_status = "error|Invalid password!";
            }
        } else {
            $login_status = "error|User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
@keyframes wave {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(5deg); }
    50% { transform: rotate(-5deg); }
    75% { transform: rotate(5deg); }
}
.wave {
    animation: wave 1.5s infinite ease-in-out;
}
</style>

</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen" style="background: url('../assets/images/kantin.png') no-repeat center center/cover;">
    <div class="bg-white p-8 rounded-lg shadow-xl w-96">
        <div class="flex justify-center mb-6">
        <img src="../assets/images/ifsu.png" alt="Logo Kantin" class="w-20 h-20 wave">
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Login</h2>
        <p class="text-center text-gray-500 mb-6">Masuk untuk melanjutkan</p>

        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
            <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">Login</button>
        </form>

        <p class="mt-4 text-center text-gray-600">Belum punya akun? 
            <a href="register.php" class="text-blue-500 hover:underline">Daftar Dulu</a>
        </p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($login_status)) { 
                list($type, $message) = explode("|", $login_status);
                echo "Swal.fire({ icon: '$type', title: '$message' }).then(() => { if ('$type' === 'success') window.location.href = 'menu.php'; });";
            } ?>
        });
    </script>
</body>
</html>
