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
<!-- Username -->
<div class="mb-4 relative">
    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
    <div class="relative">
        <input type="text" id="username" name="username" placeholder="AgungRpl1"
            class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md 
                   focus:ring-2 focus:ring-blue-400 focus:outline-none">
        <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
            <!-- Ikon User -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A9.003 9.003 0 0112 15c2.45 0 4.668.99 6.243 2.596M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    </div>
    <!-- Teks peringatan samar -->
    <p class="mt-1 text-sm text-gray-500 italic">* Username wajib asli</p>
</div>

<!-- Email -->
<div class="mb-4 relative">
  <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
  <div class="relative">
    <input type="email" id="email" name="email" placeholder="agungstecu@gmail.com" required
      class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none">
    <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
      <!-- Ikon Amplop Email -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M20 4H4a2 2 0 00-2 2v12c0 1.1.9 2 2 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 2v.01L12 13 4 6.01V6h16z" />
            </svg>
    </div>
  </div>
</div>

<!-- Password -->
<div class="mb-6 relative">
  <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
  <div class="relative">
    <input type="password" id="password" name="password" placeholder="••••••••" required
      class="mt-1 block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none">
    
    <!-- Ikon Kunci -->
    <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M17 8h-1V6a4 4 0 10-8 0v2H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V10a2 2 0 00-2-2zm-6 9a1 1 0 112 0 1 1 0 01-2 0zm3-9H10V6a2 2 0 114 0v2z" />
            </svg>
    </div>

    <!-- Tombol Toggle -->
    <button type="button" id="togglePassword" class="absolute inset-y-0 right-2 flex items-center">
      <!-- Mata Terbuka -->
      <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M12 4.5c-7.5 0-9.75 7.5-9.75 7.5s2.25 7.5 9.75 7.5 9.75-7.5 9.75-7.5-2.25-7.5-9.75-7.5Zm0 10.5a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
      </svg>
      <!-- Mata Tertutup -->
      <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 hidden" fill="none"
        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 12s2.25 7.5 9.75 7.5S21.75 12 21.75 12s-2.25-7.5-9.75-7.5S3 12 3 12ZM9 9l6 6m0-6-6 6" />
      </svg>
    </button>
  </div>
</div>
<button type="submit"
    class="w-full py-2 px-4 bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-semibold rounded-xl shadow-md hover:from-purple-600 hover:to-indigo-600 hover:shadow-lg active:scale-95 transition duration-300 ease-in-out flex items-center justify-center gap-2">
    <!-- Ikon Register -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M16 21v-2a4 4 0 00-3-3.87M12 7a4 4 0 100 8 4 4 0 000-8zm4-3h6m-3-3v6" />
    </svg>
    <span>Register</span>
</button>
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
    <a href="https://wa.me/08586270297" target="_blank"
  class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg flex items-center space-x-2 transition hover:scale-110 hover:bg-green-600">
  <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp" class="w-6 h-6">
  <span class="hidden md:inline font-semibold">Hubungi Admin</span>
</a>


</body>
</html>
