<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['useradmin'])) {
    header("Location: dangnhap.php");
    exit();
}

$user = $_SESSION['useradmin'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $current_password = md5($_POST['current_password']); // Mã hóa mật khẩu hiện tại bằng MD5
    $new_password = md5($_POST['new_password']); // Mã hóa mật khẩu mới bằng MD5
    $confirm_password = md5($_POST['confirm_password']); // Mã hóa xác nhận mật khẩu mới bằng MD5

    // Kiểm tra mật khẩu hiện tại
    if ($current_password === $user['password']) {
        // Kiểm tra sự khớp của mật khẩu mới và xác nhận
        if ($new_password === $confirm_password) {
            // Cập nhật mật khẩu trong cơ sở dữ liệu
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_password, $user['id']);

if ($stmt->execute()) {
    $success = "Mật khẩu đã được thay đổi thành công.";
    
    // Cập nhật phiên làm việc
    $_SESSION['useradmin']['password'] = $new_password;

    // Hiển thị thông báo và sau đó chuyển hướng
    echo "<script>
            alert('$success');
            window.location.href = '/Admin/index.php';
          </script>";
    exit(); // Dừng script để đảm bảo không có thêm mã nào được thực thi


            } else {
                $error = "Có lỗi xảy ra. Vui lòng thử lại.";
            }
        } else {
            $error = "Mật khẩu mới và xác nhận không khớp.";
        }
    } else {
        $error = "Mật khẩu hiện tại không đúng.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 100px;
            max-width: 400px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Đổi Mật Khẩu Admin</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="current_password" class="form-label">Mật Khẩu Hiện Tại</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Mật Khẩu Mới</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Xác Nhận Mật Khẩu Mới</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Đổi Mật Khẩu</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
