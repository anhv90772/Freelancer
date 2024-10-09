<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');

// Kiểm tra người dùng đã đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: /client/login.php");
    exit();
}

// Lấy ID người dùng hiện tại
$current_user_id = $_SESSION['user']['id'];

// Kiểm tra xem ID bài đăng có được truyền qua không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID bài đăng không hợp lệ.'); window.location.href='quanlyduan.php';</script>";
    exit();
}

$job_id = intval($_GET['id']);

// Lấy thông tin bài đăng
$sql = "SELECT * FROM jobs WHERE id = $job_id AND user_id = $current_user_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo "<script>alert('Bài đăng không tồn tại hoặc bạn không có quyền chỉnh sửa.'); window.location.href='quanlyduan.php';</script>";
    exit();
}

$job = $result->fetch_assoc();

// Xử lý lưu thông tin bài đăng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_title = $_POST['job_title'];
    $field = $_POST['field'];
    $details = $_POST['details'];
    $salary_from = $_POST['salary_from'];
    $salary_to = $_POST['salary_to'];
    $deadline = $_POST['deadline'];

    // Cập nhật bài đăng
    $update_sql = "UPDATE jobs SET job_title = ?, field = ?, details = ?, salary_from = ?, salary_to = ?, deadline = ? WHERE id = $job_id";
    
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssss", $job_title, $field, $details, $salary_from, $salary_to, $deadline);
    
    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật bài đăng thành công!'); window.location.href='quanlyduan.php';</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi cập nhật bài đăng: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Bài Đăng</title>
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
        .btn-back {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Chỉnh Sửa Bài Đăng</h2>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="job_title" class="form-label">Tiêu Đề Bài Đăng</label>
            <input type="text" class="form-control" id="job_title" name="job_title" value="<?php echo htmlspecialchars($job['job_title']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="field" class="form-label">Lĩnh Vực</label>
            <input type="text" class="form-control" id="field" name="field" value="<?php echo htmlspecialchars($job['field']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="details" class="form-label">Chi Tiết</label>
            <textarea class="form-control" id="details" name="details" rows="5" required><?php echo htmlspecialchars($job['details']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="salary_from" class="form-label">Mức Lương Từ (VNĐ)</label>
            <input type="number" class="form-control" id="salary_from" name="salary_from" value="<?php echo htmlspecialchars($job['salary_from']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="salary_to" class="form-label">Mức Lương Đến (VNĐ)</label>
            <input type="number" class="form-control" id="salary_to" name="salary_to" value="<?php echo htmlspecialchars($job['salary_to']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Thời Hạn</label>
            <input type="date" class="form-control" id="deadline" name="deadline" value="<?php echo htmlspecialchars($job['deadline']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập Nhật</button>
    </form>
    <a href="quanlyduan.php" class="btn btn-back">Quay lại</a>
</div>
 <?php
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/footer.php');
?>
</body>
</html>
