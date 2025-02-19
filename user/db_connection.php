<?php
$servername = "localhost";  // Nama server MySQL
$username = "root";         // Username MySQL
$password = "";             // Password MySQL (jika ada)
$dbname = "kantin";         // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
