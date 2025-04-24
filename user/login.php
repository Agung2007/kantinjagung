<?php
session_start();
include('db_connection.php');

$login_status = ""; // Variabel untuk menyimpan status login

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: menu.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $login_status = "error|Email dan password harus diisi!";
    } else {
        $sql = "SELECT * FROM users WHERE email = ? AND role = 'user'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $login_status = "success|Login Berhasil!";
            } else {
                $login_status = "error|Password salah!";
            }
        } else {
            $login_status = "error|Email tidak ditemukan!";
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
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
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
<body class="bg-gradient-to-r from-blue-600 to-yellow-300 flex items-center justify-center min-h-screen">
        <!-- Loading Screen -->
        <div id="loadingScreen" class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <div role="status">
            <svg aria-hidden="true" class="inline w-12 h-12 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
            <span class="sr-only">Loading...</span>
        </div>
    </div>

<div class="bg-[url('../assets/images/anggunreg.jpg')] bg-cover p-8 rounded-lg shadow-xl w-96"
     style="background-position: center top 70%;">
    <div class="flex justify-center mb-6">
        <img src="../assets/images/ifsu.png" alt="Logo Kantin" class="w-20 h-20 wave">
        </div>

        <h2 class="text-3xl font-bold text-center text-white drop-shadow-md mb-2">Login Pengguna</h2>
<p class="text-center text-gray-100 mb-6">Masuk untuk melanjutkan</p>

        <form method="POST">
    <!-- Input Username -->
<!-- Input Email -->
<div class="mb-4">
    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-red-500">
            <!-- Icon Gmail Style -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M20 4H4a2 2 0 00-2 2v12c0 1.1.9 2 2 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 2v.01L12 13 4 6.01V6h16z" />
            </svg>
        </span>
        <input type="email" id="email" name="email" placeholder="agungstecu@gmail.com"
            class="mt-1 block w-full pl-10 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm"
            required>
    </div>
</div>

<!-- Input Password -->
<div class="mb-6">
    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-blue-500">
            <!-- Icon Gembok Modern -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M17 8h-1V6a4 4 0 10-8 0v2H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V10a2 2 0 00-2-2zm-6 9a1 1 0 112 0 1 1 0 01-2 0zm3-9H10V6a2 2 0 114 0v2z" />
            </svg>
        </span>
        <input type="password" id="password" name="password" placeholder="Masukkan password"
            class="mt-1 block w-full pl-10 pr-10 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm"
            required>
        <button type="button" id="togglePassword" class="absolute inset-y-0 right-2 flex items-center text-gray-500">
            <!-- Mata terbuka -->
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

    <!-- Tombol Login -->
    <button type="submit"
    class="w-full py-2 px-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-md hover:from-blue-600 hover:to-blue-700 hover:shadow-lg active:scale-95 transition duration-300 ease-in-out flex items-center justify-center gap-2">
    <!-- Ikon login -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12H3m0 0l4-4m-4 4l4 4m9-10h2a2 2 0 012 2v12a2 2 0 01-2 2h-2" />
    </svg>
    <span>Login</span>
</button>
</form>

<!-- Link ke Register -->
<p class="mt-4 text-center text-white drop-shadow-sm">
    Belum punya akun?
    <a href="register.php" class="text-blue-300 hover:text-blue-400 underline font-semibold">
        Daftar
    </a>
</p>
    </div>

    <script>

document.addEventListener("DOMContentLoaded", function() {
            // Sembunyikan loading setelah 2 detik
            setTimeout(() => {
                document.getElementById("loadingScreen").style.display = "none";
                document.getElementById("loginContainer").classList.remove("hidden");
            }, 2000);
        });


        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($login_status)) { 
                list($type, $message) = explode("|", $login_status);
                echo "Swal.fire({ icon: '$type', title: '$message' }).then(() => { if ('$type' === 'success') window.location.href = 'menu.php'; });";
            } ?>
        });

        const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const eyeOpen = document.getElementById("eyeOpen");
    const eyeClosed = document.getElementById("eyeClosed");

    togglePassword.addEventListener("click", () => {
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
<a href="https://wa.me/08586270297" target="_blank"
  class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg flex items-center space-x-2 transition hover:scale-110 hover:bg-green-600">
  <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp" class="w-6 h-6">
  <span class="hidden md:inline font-semibold">Hubungi Admin</span>
</a>


</html>
