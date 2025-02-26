<?php
session_start();
include('db_connection.php');

// Jika sudah login, redirect ke halaman menu atau halaman lain
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: menu.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mendapatkan data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mengecek apakah data username dan password sudah diisi
    if (!isset($username) || !isset($password)) {
        echo "Username and password are required!";
        exit;
    }

    // Memeriksa username dan password di database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Jika user ditemukan
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Memeriksa password
        if (password_verify($password, $user['password'])) {
            // Menyimpan session pengguna
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            header("Location: menu.php");  // Arahkan ke halaman menu setelah login
            exit;
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
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
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-lg">Login</button>
        </form>
        <p class="mt-4 text-center">Belum punya akun? <a href="register.php" class="text-blue-500">Daftar Dulu</a></p>
    </div>
</body>
</html>
