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
        // Ambil nama kategori sebelum diubah
        $old_category_name = $category['name'];

        // Update kategori di tabel categories
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        
        if ($stmt->execute()) {
            // Update kategori di tabel menu
            $stmt_menu = $conn->prepare("UPDATE menu SET category = ? WHERE category = ?");
            $stmt_menu->bind_param("ss", $name, $old_category_name);
            $stmt_menu->execute();
            $stmt_menu->close();

            $_SESSION['success_message'] = "Kategori berhasil diperbarui dan menu ikut berubah!";
            header("Location: manage_categories.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui kategori!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <!-- SweetAlert untuk Notifikasi -->
    <?php if (isset($_SESSION['success_message'])) : ?>
        <script>
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "<?php echo $_SESSION['success_message']; ?>",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "manage_categories.php";
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
</body>
</html>
