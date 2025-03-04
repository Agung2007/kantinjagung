<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db_connection.php');

    if (!isset($_POST['username'], $_POST['password'])) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Please fill in both username and password.'];
        header("Location: login.php");
        exit;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!$conn) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Database connection failed!'];
        header("Location: login.php");
        exit;
    }

    $sql = "SELECT * FROM users WHERE username = ? AND role = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Login succes! Anda akan masuk ke Dashboard...'];
            header("Location: login.php"); // Tampilkan alert dulu, lalu redirect ke dashboard
            exit;
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Password Salah.'];
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Username salah.'];
        header("Location: login.php");
        exit;
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <div class="flex justify-center mb-6">
            <img src="../assets/images/ifsu.png" alt="Logo Admin" class="w-20 h-20">
        </div>

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
            <div class="text-center mt-4">
                <p class="text-sm">Belum Punya akun? <a href="register.php" class="text-blue-500">Daftar Dulu</a></p>
            </div>
        </form>
    </div>

    <!-- Menampilkan Alert -->
    <?php if (isset($_SESSION['alert'])) : ?>
        <script>
            Swal.fire({
                icon: "<?php echo $_SESSION['alert']['type']; ?>",
                title: "<?php echo $_SESSION['alert']['type'] === 'error' ? 'Oops...' : 'Success!'; ?>",
                text: "<?php echo $_SESSION['alert']['message']; ?>",
                <?php if ($_SESSION['alert']['type'] === 'success') : ?>
                    willClose: () => {
                        window.location.href = "dashboard.php"; // Redirect ke dashboard setelah alert sukses ditutup
                    }
                <?php endif; ?>
            });
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

</body>

</html>
