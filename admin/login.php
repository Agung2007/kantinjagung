<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menyertakan koneksi database
    include('db_connection.php');

    // Memastikan data yang dikirimkan ada
    if (!isset($_POST['username'], $_POST['password'])) {
        echo "Please fill in both username and password.";
        exit;
    }

    // Mengambil username dan password dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek koneksi database
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query untuk mengambil user dengan username yang diberikan
    $sql = "SELECT * FROM users WHERE username = ? AND role = 'admin'";
    
    // Persiapkan query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);  // Mengikat parameter
    $stmt->execute();  // Eksekusi query
    $result = $stmt->get_result();  // Ambil hasil query

    // Memeriksa apakah user ditemukan
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Ambil data user
        // Memeriksa password
        if (password_verify($password, $user['password'])) {
            // Jika berhasil login, simpan session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            header("Location: dashboard.php");  // Arahkan ke halaman dashboard
            exit;
        } else {
            echo "Invalid password";  // Password salah
        }
    } else {
        echo "Admin not found";  // User tidak ditemukan
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center mb-6">Login Admin</h2>
        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" required
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-lg">Login</button>
            <!-- Menambahkan tautan ke halaman daftar -->
            <div class="text-center mt-4">
                <p class="text-sm">Don't have an account? <a href="register.php" class="text-blue-500">Register here</a>
                </p>
            </div>

        </form>
    </div>
</body>

</html>