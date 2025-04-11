<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db_connection.php');

    if (!isset($_POST['username'], $_POST['password'])) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Gagal login silahkan cek kembali user name dan password.'];
        header("Location: login.php");
        exit;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!$conn) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Gagal!'];
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
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Login Berhasil!'];
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
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen" style="background: url('../assets/images/kantin.png') no-repeat center center/cover;">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <div class="flex justify-center mb-6">
            <img src="../assets/images/ifsu.png" alt="Logo Admin" class="w-20 h-20">
        </div>

        <h2 class="text-2xl font-bold text-center mb-6">Login Admin</h2>
        <form method="POST">
        <div class="mb-4 relative">
  <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
  <div class="relative">
    <input type="text" id="username" name="username" placeholder="Masukkan Username" required
      class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:outline-none">
    <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
      <!-- Ikon User -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
        stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M5.121 17.804A10.97 10.97 0 0112 15c2.21 0 4.253.64 5.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
    </div>
  </div>
</div>

    <div class="mb-6 relative">
  <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
  <div class="relative">
    <input type="password" id="password" name="password" placeholder="••••••••" required
      class="mt-1 block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
    
    <!-- Ikon Kunci -->
    <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="currentColor"
        viewBox="0 0 24 24">
        <path d="M12 17a1.5 1.5 0 1 0 0-3 
                 1.5 1.5 0 0 0 0 3zm6-6V9a6 
                 6 0 0 0-12 0v2H4v10h16V11h-2zm-8 
                 0V9a4 4 0 1 1 8 0v2H10z"/>
      </svg>
    </div>

    <!-- Toggle Show/Hide -->
    <button type="button" id="togglePassword" class="absolute inset-y-0 right-2 flex items-center">
      <!-- Eye Open -->
      <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6.364 
             0a9.364 9.364 0 0 1-18.728 
             0 9.364 9.364 0 0 1 18.728 
             0Z" />
      </svg>
      <!-- Eye Closed -->
      <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 hidden" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 3l18 18M10.477 
             10.477A3 3 0 0 0 13.5 13.5m-2.477-3.023L12 
             12m0-6a9.364 9.364 0 0 1 
             8.364 6 9.364 9.364 0 0 1-1.479 
             2.315M6.153 6.153A9.364 9.364 0 
             0 0 3.636 12c.612 1.567 1.7 
             2.964 3.036 4.002" />
      </svg>
    </button>
  </div>
</div>

<button type="submit"
    class="w-full py-2 px-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow hover:from-blue-600 hover:to-blue-700 hover:shadow-lg active:scale-95 transition duration-300 ease-in-out flex items-center justify-center gap-2">
    <!-- Ikon Login -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12H3m0 0l4-4m-4 4l4 4m9-10h2a2 2 0 012 2v12a2 2 0 01-2 2h-2" />
    </svg>
    <span>Login</span>
</button>

    <div class="text-center mt-4">
        <p class="text-sm">Belum punya akun? <a href="register.php" class="text-blue-500">Daftar dulu</a></p>
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
<script>
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

</html>
