<?php
include('db_connection.php');

$register_status = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $register_status = "error|Username already taken!";
    } else {
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $register_status = "success|Registration successful!";
        } else {
            $register_status = "error|Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @keyframes wave {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(5deg);
            }

            50% {
                transform: rotate(-5deg);
            }

            75% {
                transform: rotate(5deg);
            }
        }

        .wave {
            animation: wave 1.5s infinite ease-in-out;
        }
    </style>

</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen" style="background: url('../assets/images/kantin.png') no-repeat center center/cover;">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <div class="flex justify-center mb-6">
        <img src="../assets/images/ifsu.png" alt="Logo Kantin" class="w-20 h-20 wave">
        </div>
        
        <h2 class="text-2xl font-bold text-center mb-6">Daftar Pengguna</h2>
        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-lg">Register</button>
        </form>
        <p class="mt-4 text-center">Sudah punya akun? <a href="login.php" class="text-blue-500">Login</a></p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($register_status)) { 
                list($type, $message) = explode("|", $register_status);
                echo "Swal.fire({ icon: '$type', title: '$message' }).then(() => { if ('$type' === 'success') window.location.href = 'login.php'; });";
            } ?>
        });
    </script>
</body>
</html>
