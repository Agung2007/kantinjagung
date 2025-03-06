<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Please fill in all fields.'];
        header("Location: register.php");
        exit;
    }

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Email Gagal'];
        header("Location: register.php");
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Passwords Gagal'];
        header("Location: register.php");
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Username Atau Email sudah terpakai!'];
        header("Location: register.php");
        exit;
    }

    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Berhasil Daftar, Silahkan Login.'];
        header("Location: register.php"); // Tetap di halaman register dulu
        exit;
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => "Error: " . $stmt->error];
        header("Location: register.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen" style="background: url('../assets/images/kantin.png') no-repeat center center/cover;">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <div class="flex justify-center mb-6">
            <img src="../assets/images/ifsu.png" alt="Logo Admin" class="w-20 h-20">
        </div>

        <h2 class="text-2xl font-bold text-center mb-6">Register Admin</h2>

        <!-- Menampilkan alert dengan SweetAlert2 -->
        <?php if (isset($_SESSION['alert'])) : ?>
    <script>
        Swal.fire({
            icon: "<?php echo $_SESSION['alert']['type']; ?>",
            title: "<?php echo $_SESSION['alert']['type'] === 'error' ? 'Oops...' : 'Success!'; ?>",
            text: "<?php echo $_SESSION['alert']['message']; ?>",
            <?php if ($_SESSION['alert']['type'] === 'success') : ?>
                willClose: () => {
                    window.location.href = "login.php"; // Redirect ke login setelah alert sukses ditutup
                }
            <?php endif; ?>
        });
    </script>
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-lg">Register</button>
        </form>

        <div class="text-center mt-4">
            <p class="text-sm">Sudah punya akun? <a href="login.php" class="text-blue-500">Login di sini</a></p>
        </div>
    </div>
</body>
</html>
