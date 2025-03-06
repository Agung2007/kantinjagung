<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once('db_connection.php');

if (!file_exists(__DIR__ . '/../libs/tcpdf/tcpdf.php')) {
    die("Error: File TCPDF tidak ditemukan!");
}
require_once(__DIR__ . '/../libs/tcpdf/tcpdf.php');


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Pesanan tidak ditemukan.");
}

$order_id = intval($_GET['id']);

// Ambil data pesanan
$query = "SELECT total_price, order_date, status FROM orders WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_info = $stmt->get_result()->fetch_assoc();

if (!$order_info) {
    die("Pesanan tidak ditemukan.");
}

// Ambil detail pesanan 
$query = "SELECT md.name, od.quantity, od.price 
          FROM order_details od 
          JOIN menu md ON od.menu_id = md.id 
          WHERE od.order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

// Buat objek PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Restoran');
$pdf->SetTitle('Invoice Pesanan');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// Judul
$pdf->SetFont('helvetica', 'B', 16);  
$pdf->Cell(0, 10, 'Invoice Pesanan', 0, 1, 'C');

// Info pesanan
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, "Total Harga: $" . number_format($order_info['total_price'], 2), 0, 1);
$pdf->Cell(0, 10, "Tanggal Pesanan: " . $order_info['order_date'], 0, 1);
$pdf->Cell(0, 10, "Status: " . ucfirst($order_info['status']), 0, 1);
$pdf->Ln(5);

// Header tabel
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(80, 10, "Nama Menu", 1, 0);
$pdf->Cell(30, 10, "Jumlah", 1, 0, 'C');
$pdf->Cell(40, 10, "Harga", 1, 1, 'R');

// Isi tabel
$pdf->SetFont('helvetica', '', 12);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(80, 10, $row['name'], 1, 0);
    $pdf->Cell(30, 10, $row['quantity'], 1, 0, 'C');
    $pdf->Cell(40, 10, "$" . number_format($row['price'], 2), 1, 1, 'R');
}

// Output PDF
$pdf->Output("Transaksi kantin ifsu berkah_$order_id.pdf", "D"); // D = Download, I = Tampilkan di browser

exit;
