<?php
// Kết nối database
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/header.php');

session_start();

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

// Xử lý form đăng bài
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form và làm sạch đầu vào
    $job_title = $conn->real_escape_string($_POST['job_title'] ?? '');
    $field = $conn->real_escape_string($_POST['field'] ?? '');
    $details = $conn->real_escape_string($_POST['details'] ?? '');
    $skills = $conn->real_escape_string($_POST['skills'] ?? '');
    $deadline = $conn->real_escape_string($_POST['deadline'] ?? '');
    $salary_from = $conn->real_escape_string($_POST['salary_from'] ?? '');
    $salary_to = $conn->real_escape_string($_POST['salary_to'] ?? '');

    // Kiểm tra tính hợp lệ
    if (empty($job_title) || empty($field) || empty($details) || empty($skills) || empty($deadline) || empty($salary_from) || empty($salary_to)) {
        $message = 'Vui lòng điền đầy đủ thông tin.';
        echo "<script>alert('$message');</script>";
    } else {
        // Chèn dữ liệu vào bảng jobs với user_id
        $sql = "INSERT INTO jobs (job_title, field, details, skills, deadline, salary_from, salary_to, duyet, user_id) 
                VALUES ('$job_title', '$field', '$details', '$skills', '$deadline', '$salary_from', '$salary_to', '0', '$user_id')";
        
        if ($conn->query($sql) === TRUE) {
            $message = 'Đăng bài thành công!';
            echo "<script>alert('$message');</script>";
        } else {
            $message = 'Lỗi: ' . $conn->error; // Hiển thị lỗi SQL
            echo "<script>alert('$message');</script>";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Dự Án</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0f0f0, #d9e5e7);
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 700px;
            margin-top: 50px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }
        h2 {
            color: #5D6D9A;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: 600;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            transition: all 0.3s ease;
            font-weight: 600;
            border-radius: 25px;
            padding: 10px 20px;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #007bff);
            transform: translateY(-2px);
        }
        .form-control {
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Đăng Bài Tuyển Freelancer</h2>
    <form action="" method="POST">
        <?php if ($message): ?>
            <p class="text-danger"><?php echo $message; ?></p>
        <?php endif; ?>
        <div class="mb-3">
            <label for="job_title" class="form-label">Tên Công Việc</label>
            <input type="text" class="form-control" id="job_title" name="job_title" required>
        </div>
        <div class="mb-3">
            <label for="field" class="form-label">Lĩnh Vực Cần Tuyển</label>
            <select class="form-select" id="field" name="field" required>
                <option value="" disabled selected>Chọn lĩnh vực</option>
                <option value="Công nghệ thông tin">Công nghệ thông tin</option>
                <option value="Dịch thuật">Dịch thuật</option>
                <option value="Marketing">Marketing</option>
                <option value="Kiến trúc">Kiến trúc</option>
                <option value="Thiết kế">Thiết kế</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="details" class="form-label">Nội Dung Chi Tiết</label>
            <textarea class="form-control" id="details" name="details" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label for="skills" class="form-label">Kỹ Năng Yêu Cầu</label>
            <input type="text" class="form-control" id="skills" name="skills" required>
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Hạn cuối nhận chào giá của freelancer</label>
            <input type="date" class="form-control" id="deadline" name="deadline" required>
        </div>
        <div class="mb-3">
            <label for="salary_from" class="form-label">Mức Lương Từ</label>
            <input type="number" class="form-control" id="salary_from" name="salary_from" required>
        </div>
        <div class="mb-3">
            <label for="salary_to" class="form-label">Mức Lương Đến</label>
            <input type="number" class="form-control" id="salary_to" name="salary_to" required>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Đính kèm file (nếu có)</label>
            <input type="file" class="form-control" id="file" name="file">
        </div>
        <button type="submit" class="btn btn-primary">Đăng Bài</button>
    </form>
</div>
 <?php
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/footer.php');
?>
</body>
</html>
