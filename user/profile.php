<?php
session_start();
include('db_connection.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Proses update profil jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['username'];
    $email = $_POST['email'];

    // Proses gambar profil (optional)
    $profile_picture = $user['profile_picture'];
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        // Gambar baru diupload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
        
        // Validasi file gambar
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "gif");

        // Cek apakah file adalah gambar
        if (in_array($imageFileType, $allowed_types)) {
            // Cek ukuran file (misalnya, 5MB)
            if ($_FILES['profile_picture']['size'] < 5000000) {
                // Pindahkan gambar yang diupload ke folder uploads
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                    $profile_picture = $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "File is too large. Max file size is 5MB.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Update data pengguna di database
    $update_query = "UPDATE users SET username = ?, email = ?, profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssi", $name, $email, $profile_picture, $user_id);
    $stmt->execute();

    // Update session jika diperlukan
    $_SESSION['username'] = $name;
    
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil',
                icon: 'success',
                draggable: true
            }).then(() => {
                window.location.href = 'menu.php';
            });
        });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 relative"
style="background: url('../assets/images/new.jpg') no-repeat center center/cover;">

    <div class="container mx-auto p-8 relative">
        <!-- Tombol Kembali -->
        <a href="menu.php" class="absolute top-4 left-4 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-full p-2">
            ✖
        </a>

        <h2 class="text-3xl font-semibold text-center mb-6">Edit Profil</h2>

<form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md space-y-6">
    
    <!-- Foto Profil -->
    <div class="flex justify-center">
        <label for="profile_picture" class="relative cursor-pointer group">
            <img id="profilePreview" 
                 src="<?= htmlspecialchars($user['profile_picture'] ?? '../assets/images/avatar.jpeg') ?>" 
                 alt="Profile Picture" 
                 class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-md">
            
            <!-- Camera icon overlay -->
            <div class="absolute bottom-1 right-1 bg-gray-200 p-1 rounded-full group-hover:bg-blue-500 transition">
                <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M15.232 5.232l3.536 3.536M9 11l6-6 3.536 3.536-6 6H9v-3.536z" />
                </svg>
            </div>
        </label>
        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="hidden" onchange="previewImage(event)">
    </div>

    <!-- Nama -->
    <div>
        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <!-- User Icon -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A4 4 0 019 16h6a4 4 0 013.879 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                </svg>
            </span>
            <input type="text" name="username" id="username"
                   value="<?= htmlspecialchars($user['username']) ?>"
                   class="w-full pl-10 p-3 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" required>
        </div>
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <!-- Mail Icon -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                </svg>
            </span>
            <input type="email" name="email" id="email"
                   value="<?= htmlspecialchars($user['email']) ?>"
                   class="w-full pl-10 p-3 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" required>
        </div>
    </div>

    <!-- Tombol Submit -->
    <button type="submit"
            class="w-full py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center gap-2 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        Simpan Profil
    </button>
</form>
    </div>
    <a href="https://wa.me/08586270297" target="_blank"
  class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg flex items-center space-x-2 transition hover:scale-110 hover:bg-green-600">
  <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp" class="w-6 h-6">
  <span class="hidden md:inline font-semibold">Hubungi Admin</span>
</a>

<script>
        function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const preview = document.getElementById('profilePreview');
            preview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

</script>



</body>
</html>
