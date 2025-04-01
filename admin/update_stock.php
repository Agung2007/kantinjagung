<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['menu_id']) && isset($_POST['stock'])) {
    $menu_id = $_POST['menu_id'];
    $stock = intval($_POST['stock']);

    $sql = "UPDATE menu SET stock = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $stock, $menu_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
