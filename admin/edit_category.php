<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_categories.php");
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM categories WHERE id = $id");
$category = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_category'])) {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Kategori berhasil diperbarui!";
            header("Location: manage_categories.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui kategori!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="flex items-center justify-center min-h-screen">
        <form method="POST" class="bg-white p-6 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-semibold mb-4">Edit Kategori</h2>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="name" id="name" class="w-full p-3 mt-2 border rounded-md" value="<?= htmlspecialchars($category['name']) ?>" required>
            <button type="submit" name="update_category" class="mt-4 w-full py-3 bg-blue-600 text-white rounded-md">Update</button>
        </form>
    </div>
</body>
</html>
