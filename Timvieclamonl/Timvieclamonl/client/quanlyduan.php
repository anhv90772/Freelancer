<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/header.php');
// Kiểm tra và thông báo giá trị của user
$user = $_SESSION['user'] ?? null;

if ($user === null) {
    header("Location: /index.php");
    exit();
}

// Lấy work_type từ phiên
$work_type = $user['work_type'] ?? null;

// Kiểm tra giá trị work_type
if ($work_type !== 'doanhnghiep') {
    header("Location: /index.php");
    exit();
}

// Khởi tạo biến để hiển thị thông báo
$message = '';

// Lấy user_id từ phiên
$user_id = $user['id'] ?? null;

// Kiểm tra user_id
if ($user_id === null) {
    header("Location: /index.php");
    exit();
}
// Kiểm tra người dùng đã đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: /client/login.php");
    exit();
}

// Lấy ID người dùng hiện tại
$current_user_id = $_SESSION['user']['id'];

// Lấy danh sách các bài đăng của người dùng hiện tại
$sql = "SELECT j.id, j.job_title, j.field, j.details, j.salary_from, j.salary_to, j.deadline, j.duyet 
        FROM jobs j 
        WHERE j.user_id = $current_user_id";

// Thực hiện truy vấn
$result = $conn->query($sql);

// Kiểm tra có bài đăng nào không
if ($result->num_rows === 0) {
    $jobs = [];
} else {
    $jobs = $result->fetch_all(MYSQLI_ASSOC);
}

// Xử lý xóa bài đăng
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $job_id = intval($_GET['id']);
    
    // Xóa bài đăng chỉ khi ID của bài đăng thuộc về người dùng hiện tại
    $delete_sql = "DELETE FROM jobs WHERE id = $job_id AND user_id = $current_user_id";
    
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Xóa bài đăng thành công!'); window.location.href='quanlyduan.php';</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi xóa bài đăng: " . $conn->error . "');</script>";
    }
}

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Dự Án</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
            background: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }
        .job-item {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }
        .job-title {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .btn-edit, .btn-delete {
            margin-right: 5px;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
            margin-top: 20px;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Quản Lý Dự Án</h2>

    <?php if (empty($jobs)): ?>
        <p>Không có bài đăng nào.</p>
    <?php else: ?>
        <?php foreach ($jobs as $job): ?>
            <div class="job-item">
                <div class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></div>
                <p><strong>Lĩnh Vực:</strong> <?php echo htmlspecialchars($job['field']); ?></p>
                <p><strong>Chi Tiết:</strong> <?php echo nl2br(htmlspecialchars($job['details'])); ?></p>
                <p><strong>Mức Lương:</strong> <?php echo htmlspecialchars($job['salary_from']) . ' - ' . htmlspecialchars($job['salary_to']); ?> VNĐ</p>
                <p><strong>Thời Hạn:</strong> <?php echo htmlspecialchars($job['deadline']); ?></p>
                <p><strong>Trạng Thái:</strong> <?php echo $job['duyet'] ? 'Đã duyệt' : 'Chưa duyệt'; ?></p>
                <a href="editduan.php?id=<?php echo $job['id']; ?>" class="btn btn-warning btn-edit">Chỉnh sửa</a>
                <a href="?action=delete&id=<?php echo $job['id']; ?>" class="btn btn-danger btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa bài đăng này không?');">Xóa</a>
                 <a href="quanlychaogia.php?job_id=<?php echo $job['id']; ?>" class="btn btn-warning btn-edit">Chào giá của bài này</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="/client/dangduan.php" class="btn btn-primary">Thêm Dự Án Mới</a>
</div>
 <?php
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/footer.php');
?>
</body>
</html>
