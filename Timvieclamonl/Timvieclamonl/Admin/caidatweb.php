<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/Admin/header.php');

// Lấy dữ liệu thiết lập hiện tại
$sql = "SELECT * FROM settings LIMIT 1"; // Giả định có 1 dòng trong bảng settings
$result = $conn->query($sql);
$settings = $result->fetch_assoc();

// Xử lý khi người dùng gửi form để cập nhật thiết lập
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image_link = $_POST['image_link'];
    $admin_email = $_POST['admin_email'];
    $admin_phone = $_POST['admin_phone'];
    $timelamviec = $_POST['timelamviec'];

    // Cập nhật thiết lập
    $sql = "UPDATE settings SET title = ?, description = ?, image_link = ?, admin_email = ?, admin_phone = ?, timelamviec = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $description, $image_link, $admin_email, $admin_phone, $timelamviec);

    if ($stmt->execute()) {
        echo "<script>alert('Thay đổi thành công!'); window.location.href = 'caidatweb.php';</script>";
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
    <h2 class="mb-4">Cài đặt trang web</h2>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($settings['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($settings['description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="image_link" class="form-label">Link ảnh trang web</label>
            <input type="text" class="form-control" id="image_link" name="image_link" value="<?php echo htmlspecialchars($settings['image_link']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="admin_email" class="form-label">Email Admin</label>
            <input type="email" class="form-control" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($settings['admin_email']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="admin_phone" class="form-label">Số điện thoại Admin</label>
            <input type="text" class="form-control" id="admin_phone" name="admin_phone" value="<?php echo htmlspecialchars($settings['admin_phone']); ?>" required>
        </div>
        
         <div class="mb-3">
            <label for="timelamviec" class="form-label">Time làm việc</label>
            <input type="text" class="form-control" id="timelamviec" name="timelamviec" value="<?php echo htmlspecialchars($settings['timelamviec']); ?>" required>
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
