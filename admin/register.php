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
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
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
<!-- Username -->
<div class="mb-4">
  <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
  <div class="relative">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
      <!-- Ikon user -->
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
        <path fill-rule="evenodd"
          d="M12 2a5 5 0 0 1 5 5v1a5 5 0 0 1-10 0V7a5 5 0 0 1 5-5Zm-7 17a7 7 0 0 1 14 0v1H5v-1Z"
          clip-rule="evenodd" />
      </svg>
    </span>
    <input type="text" id="username" name="username" required placeholder="Masukkan username"
      class="block w-full rounded-md border border-gray-300 py-2 pl-10 pr-3 focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-400" />
  </div>
</div>

<!-- Email -->
<div class="mb-4">
  <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
  <div class="relative">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
        <path
          d="M2.25 6.75A2.25 2.25 0 0 1 4.5 4.5h15a2.25 2.25 0 0 1 2.25 2.25v.379l-9.75 6.5-9.75-6.5V6.75Zm0 2.621v7.879A2.25 2.25 0 0 0 4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V9.371l-9.166 6.11a.75.75 0 0 1-.834 0L2.25 9.371Z" />
      </svg>
    </span>
    <input type="email" id="email" name="email" required placeholder="agungstecu@gmail.com"
      class="block w-full rounded-md border border-gray-300 py-2 pl-10 pr-3 focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-400" />
  </div>
</div>

<!-- Password -->
<div class="mb-4">
  <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
  <div class="relative">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
        <path fill-rule="evenodd"
          d="M6 9V7a6 6 0 1 1 12 0v2a3 3 0 0 1 3 3v6a3 3 0 0 1-3 
          3H6a3 3 0 0 1-3-3v-6a3 3 0 0 1 3-3Zm10-2v2H8V7a4 4 0 0 1 
          8 0ZM12 13a1.5 1.5 0 0 1 1 2.598V17a1 1 0 1 1-2 
          0v-1.402A1.5 1.5 0 0 1 12 13Z"
          clip-rule="evenodd" />
      </svg>
    </span>
    <input type="password" id="password" name="password" required placeholder="Masukan Password"
      class="block w-full rounded-md border border-gray-300 py-2 pl-10 pr-10 focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-400" />
    <button type="button" id="togglePassword" class="absolute inset-y-0 right-2 flex items-center">
      <!-- Ikon open & close password -->
      <svg id="eyeOpen1" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6.364 
          0a9.364 9.364 0 0 1-18.728 0 9.364 9.364 0 0 1 
          18.728 0Z" />
      </svg>
      <svg id="eyeClosed1" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 hidden" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 3l18 18M10.477 10.477A3 3 0 0 0 13.5 
          13.5m-2.477-3.023L12 12m0-6a9.364 9.364 0 0 1 
          8.364 6 9.364 9.364 0 0 1-1.479 2.315M6.153 
          6.153A9.364 9.364 0 0 0 3.636 12c.612 1.567 
          1.7 2.964 3.036 4.002" />
      </svg>
    </button>
  </div>
</div>

<!-- Confirm Password -->
<div class="mb-6">
  <label for="confirm_password" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
  <div class="relative">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
        <path fill-rule="evenodd"
          d="M6 9V7a6 6 0 1 1 12 0v2a3 3 0 0 1 3 3v6a3 3 0 0 1-3 
          3H6a3 3 0 0 1-3-3v-6a3 3 0 0 1 3-3Zm10-2v2H8V7a4 4 0 0 1 
          8 0ZM12 13a1.5 1.5 0 0 1 1 2.598V17a1 1 0 1 1-2 
          0v-1.402A1.5 1.5 0 0 1 12 13Z"
          clip-rule="evenodd" />
      </svg>
    </span>
    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Ulangi password"
      class="block w-full rounded-md border border-gray-300 py-2 pl-10 pr-10 focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-400" />
    <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-2 flex items-center">
      <svg id="eyeOpen2" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6.364 
          0a9.364 9.364 0 0 1-18.728 0 9.364 9.364 0 0 1 
          18.728 0Z" />
      </svg>
      <svg id="eyeClosed2" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 hidden" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 3l18 18M10.477 10.477A3 3 0 0 0 13.5 
          13.5m-2.477-3.023L12 12m0-6a9.364 9.364 0 0 1 
          8.364 6 9.364 9.364 0 0 1-1.479 2.315M6.153 
          6.153A9.364 9.364 0 0 0 3.636 12c.612 1.567 
          1.7 2.964 3.036 4.002" />
      </svg>
    </button>
  </div>
</div>

<button type="submit"
    class="w-full py-2 px-4 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg shadow hover:from-indigo-600 hover:to-indigo-700 hover:shadow-lg active:scale-95 transition duration-300 ease-in-out flex items-center justify-center gap-2">
    <!-- Ikon Register -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 21v-2a4 4 0 00-3-3.87M12 7a4 4 0 100 8 4 4 0 000-8zm4-3h6m-3-3v6" />
    </svg>
    <span>Register</span>
</button>
</form>

<div class="text-center mt-4">
    <p class="text-sm">Sudah punya akun? <a href="login.php" class="text-blue-500">Login di sini</a></p>
</div>
</body>
<script>
        function togglePasswordVisibility(inputId, eyeOpenId, eyeClosedId) {
        const input = document.getElementById(inputId);
        const eyeOpen = document.getElementById(eyeOpenId);
        const eyeClosed = document.getElementById(eyeClosedId);

        if (input.type === "password") {
            input.type = "text";
            eyeOpen.classList.add("hidden");
            eyeClosed.classList.remove("hidden");
        } else {
            input.type = "password";
            eyeOpen.classList.remove("hidden");
            eyeClosed.classList.add("hidden");
        }
    }

    document.getElementById("togglePassword").addEventListener("click", function () {
        togglePasswordVisibility("password", "eyeOpen1", "eyeClosed1");
    });

    document.getElementById("toggleConfirmPassword").addEventListener("click", function () {
        togglePasswordVisibility("confirm_password", "eyeOpen2", "eyeClosed2");
    });

</script>
</html>
