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
    echo "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-semibold text-center mb-6">Your Profile</h2>

        <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full p-3 border border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full p-3 border border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="profile_picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                <input type="file" name="profile_picture" id="profile_picture" class="w-full p-3 border border-gray-300 rounded-md">
                <?php if ($user['profile_picture']) { ?>
                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" class="w-24 h-24 mt-4 rounded-full object-cover">
                <?php } ?>
            </div>

            <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-lg">Update Profile</button>
        </form>
    </div>

</body>
</html>
