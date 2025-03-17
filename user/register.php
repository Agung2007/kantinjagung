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
        $register_status = "error|Username Sudah Di Gunakan!";
    } else {
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            $register_status = "success|Registrasi Berhasil";
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
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
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
<body class="bg-gray-100 flex items-center justify-center min-h-screen" style="background: url('../assets/images/new,jpg') no-repeat center center/cover;">
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
            <div class="mb-6 relative">
    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
    <div class="relative">
        <input type="password" id="password" name="password" required
            class="mt-1 block w-full p-2 border border-gray-300 rounded-md pr-10">
        <button type="button" id="togglePassword" class="absolute inset-y-0 right-2 flex items-center">
            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 4.5c-7.5 0-9.75 7.5-9.75 7.5s2.25 7.5 9.75 7.5 9.75-7.5 9.75-7.5-2.25-7.5-9.75-7.5Zm0 10.5a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
            </svg>
            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 hidden">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12s2.25 7.5 9.75 7.5S21.75 12 21.75 12s-2.25-7.5-9.75-7.5S3 12 3 12ZM9 9l6 6m0-6-6 6" />
            </svg>
        </button>
    </div>
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

        const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const eyeOpen = document.getElementById("eyeOpen");
    const eyeClosed = document.getElementById("eyeClosed");

    togglePassword.addEventListener("click", function () {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeOpen.classList.add("hidden");
            eyeClosed.classList.remove("hidden");
        } else {
            passwordInput.type = "password";
            eyeOpen.classList.remove("hidden");
            eyeClosed.classList.add("hidden");
        }
    });

    </script>
</body>
</html>
