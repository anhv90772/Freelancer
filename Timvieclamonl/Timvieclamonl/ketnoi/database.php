<?php
$conn = new mysqli('localhost', 'mxnhcsinhthanh_demo2', 'mxnhcsinhthanh_demo2', 'mxnhcsinhthanh_demo2');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối ko được: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$sql = "SELECT * FROM settings LIMIT 1"; // Giả định có 1 dòng trong bảng settings
$result = $conn->query($sql);
$thongtinweb = $result->fetch_assoc();


$sql2 = "SELECT * FROM nganhang LIMIT 1"; // Giả định có 1 dòng trong bảng nganhang
$result = $conn->query($sql2);
$thongtinbank = $result->fetch_assoc();


?>
