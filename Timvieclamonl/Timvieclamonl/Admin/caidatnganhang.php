<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/Admin/header.php');

// Lấy dữ liệu thiết lập hiện tại
$sql = "SELECT * FROM nganhang LIMIT 1"; // Giả định có 1 dòng trong bảng nganhang
$result = $conn->query($sql);
$atm = $result->fetch_assoc();

// Xử lý khi người dùng gửi form để cập nhật thiết lập
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nganhang = $_POST['nganhang'];
    $stk = $_POST['stk'];
    $ctk = $_POST['ctk'];
    $linkqr = $_POST['linkqr'];


    // Cập nhật thiết lập
$sql = "UPDATE nganhang SET nganhang = ?, stk = ?, ctk = ?, linkqr = ? WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nganhang, $stk, $ctk, $linkqr); // Tất cả các tham số đều là chuỗi

    if ($stmt->execute()) {
        echo "<script>alert('Thay đổi thành công!'); window.location.href = 'caidatnganhang.php';</script>";
        exit();
    } else {
        echo "<script>alert('Có lỗi xảy ra khi cập nhật!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài đặt trang web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h2 class="mb-4">Cài đặt ngân hàng</h2>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="nganhang" class="form-label">Ngân hàng</label>
            <input type="text" class="form-control" id="nganhang" name="nganhang" value="<?php echo htmlspecialchars($atm['nganhang']); ?>" required>
        </div>

     

        <div class="mb-3">
            <label for="stk" class="form-label">Số tài khoản</label>
            <input type="text" class="form-control" id="stk" name="stk" value="<?php echo htmlspecialchars($atm['stk']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="ctk" class="form-label">Chủ Tài Khoản</label>
            <input type="text" class="form-control" id="admin_email" name="ctk" value="<?php echo htmlspecialchars($atm['ctk']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="linkqr" class="form-label">Link QR</label>
            <input type="text" class="form-control" id="linkqr" name="linkqr" value="<?php echo htmlspecialchars($atm['linkqr']); ?>" required>
        </div>
        
      

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="/index.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/Admin/footer.php');
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
