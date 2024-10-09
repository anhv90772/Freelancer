<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/header.php');

// Kiểm tra id bài đăng
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Dự án không tồn tại.");
}

// Lấy id bài đăng từ URL
$job_id = intval($_GET['id']);

// Lấy thông tin chi tiết bài đăng từ cơ sở dữ liệu
$sql = "SELECT j.job_title, j.field, j.details, j.skills, j.salary_from, j.salary_to, j.deadline, u.fullname, j.user_id AS job_user_id 
        FROM jobs j 
        JOIN users u ON j.user_id = u.id
        WHERE j.id = $job_id AND j.duyet = 1"; // Chỉ lấy bài đã duyệt

$result = $conn->query($sql);

// Kiểm tra bài đăng có tồn tại
if ($result->num_rows === 0) {
    die("Dự án không tồn tại hoặc chưa được duyệt.");
}

// Lấy dữ liệu bài đăng
$job = $result->fetch_assoc();

// Kiểm tra deadline
$current_date = date('Y-m-d'); // Lấy ngày hiện tại
if ($current_date > $job['deadline']) {
    die("Không thể gửi chào giá vì dự án đã quá hạn.");
}

// Truy vấn đếm số lượng chào giá cho bài đăng
$count_sql = "SELECT COUNT(*) AS total_proposals FROM proposals WHERE job_id = $job_id";
$count_result = $conn->query($count_sql);

// Lấy số lượng chào giá
$total_proposals = 0; // Khởi tạo biến đếm
if ($count_result) {
    $count_row = $count_result->fetch_assoc();
    $total_proposals = $count_row['total_proposals'];
}

// Xử lý gửi chào giá
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ biểu mẫu
    $project_cost = $_POST['project_cost'];
    $completion_time = $_POST['completion_time'];
    $experience = $_POST['experience'];
    $project_plan = $_POST['project_plan'];
    $contact_info = $_POST['contact_info'];
    $user_id = $_SESSION['user']['id']; // ID người dùng hiện tại

    // Kiểm tra dữ liệu đầu vào
    if (empty($project_cost) || empty($completion_time) || empty($experience) || empty($project_plan) || empty($contact_info)) {
        echo "<script>alert('Vui lòng điền đầy đủ thông tin.');</script>";
        exit();
    }

    // Kiểm tra receiver_id
    $receiver_id = $job['job_user_id']; // Đảm bảo biến này được khởi tạo
    if (empty($receiver_id)) {
        echo "<script>alert('Không tìm thấy receiver_id.');</script>";
        exit();
    }

    // Thêm chào giá vào cơ sở dữ liệu
    $sql = "INSERT INTO proposals (job_id, sender_id, receiver_id, project_cost, completion_time, experience, project_plan, contact_info, trungtuyen) 
            VALUES ($job_id, $user_id, $receiver_id, $project_cost, '$completion_time', '$experience', '$project_plan', '$contact_info','0')";
    
    // Thực hiện truy vấn và kiểm tra kết quả
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Gửi chào giá thành công!');</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi thực hiện truy vấn: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem Chi Tiết Bài Đăng</title>
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
        .job-detail {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .job-title {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
            margin-top: 20px;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
        .strong {
            font-weight: bold;
        }
        textarea {
            width: 100%;
            height: 100px;
            resize: none; /* Không cho phép người dùng thay đổi kích thước */
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Xem Chi Tiết Bài Đăng</h2>
    <div class="job-detail">
      <center>  <div class="job-title"><?php echo htmlspecialchars($job['job_title']); ?></div></center> 
        <p><span class="strong">Lĩnh Vực:</span> <?php echo htmlspecialchars($job['field']); ?></p>
        <p><span class="strong">Chi Tiết:</span> <?php echo nl2br(htmlspecialchars($job['details'])); ?></p>
        <p><span class="strong">Kỹ Năng:</span> <?php echo nl2br(htmlspecialchars($job['skills'])); ?></p>
        <p><span class="strong">Mức Lương:</span>  <?php 
        echo number_format(htmlspecialchars($job['salary_from']), 0, ',', '.') . ' - ' . number_format(htmlspecialchars($job['salary_to']), 0, ',', '.') . ' VNĐ'; 
    ?></p>
        <p><span class="strong">Thời Hạn:</span> <?php echo htmlspecialchars($job['deadline']); ?></p>
        <p><span class="strong">Người Đăng:</span> <?php echo htmlspecialchars($job['fullname']); ?></p>
                <p><span class="strong">Chào giá:</span> <?php echo $total_proposals; ?></p>

    </div>

    <h2>Thông Tin Chào Giá</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="project_cost" class="form-label">Chi phí dự án bạn muốn nhận (VNĐ):</label>
            <input type="number" class="form-control" id="project_cost" name="project_cost" required>
        </div>
        <div class="mb-3">
            <label for="completion_time" class="form-label">Dự kiến hoàn thành trong:</label>
            <input type="text" class="form-control" id="completion_time" name="completion_time" required>
        </div>
        <div class="mb-3">
            <label for="experience" class="form-label">Tôi đã có kinh nghiệm:</label>
            <textarea class="form-control" id="experience" name="experience" required></textarea>
        </div>
        <div class="mb-3">
            <label for="project_plan" class="form-label">Dự định thực hiện dự án:</label>
            <textarea class="form-control" id="project_plan" name="project_plan" required></textarea>
        </div>
        <div class="mb-3">
            <label for="contact_info" class="form-label">Thông tin liên hệ của bạn:</label>
            <textarea class="form-control" id="contact_info" name="contact_info" required></textarea>
        </div>
        
        <div class="mb-3">
            <label for="completion_time" class="form-label">Tệp đính kèm ( nếu có ):</label>
            <input type="file" class="form-control">
        </div>
     <button type="submit" class="btn btn-primary">Gửi Chào Giá</button> 
        <br>
        <a href="/home.php" class="btn btn-back">Quay Lại</a>
    </form>
</div>

 <?php
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/footer.php');
?>
</body>
</html>
