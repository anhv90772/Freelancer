<?php
ob_start();  // Bắt đầu Output Buffering
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/header.php');

// Kiểm tra người dùng đã đăng nhập
if (!isset($_SESSION['user'])) {
    header("Location: /client/login.php");
    exit();
}

// Lấy user_id của người dùng đã đăng nhập
$current_user_id = $_SESSION['user']['id'];

// Lấy danh sách các bài chào giá đã trúng tuyển (trungtuyen = 1) của người dùng hiện tại
$sql = "
    SELECT proposals.*, jobs.job_title, 
           sender.fullname AS fullnamesender, 
           receiver.fullname AS fullnamereceiver
    FROM proposals
    JOIN jobs ON proposals.job_id = jobs.id
    JOIN users AS sender ON proposals.sender_id = sender.id
    JOIN users AS receiver ON proposals.receiver_id = receiver.id
    WHERE proposals.trungtuyen = 1
    AND (proposals.sender_id = $current_user_id OR proposals.receiver_id = $current_user_id)
    ORDER BY proposals.created_at DESC
";

$result = $conn->query($sql);
$conn->close();


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Freelancer - Chào Giá Trúng Tuyển</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h2 {
            color: #343a40;
        }
        table {
            margin-top: 20px;
        }
        th {
            background-color: #000;
            color: #fff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        footer {
            margin-top: 20px;
            text-align: center;
            color: #6c757d;
        }
        th {
            background-color: #000;
            color: #000; /* Màu chữ đen */
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Quản Lý Freelancer - Trúng Tuyển</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID Dự Án</th>
                    <th>Dự Án</th>
                    <th>Chủ Dự Án</th>
                    <th>Số Tiền Chào Giá (VNĐ)</th>
                    <th>Thời Gian Hoàn Thành</th>
                    <th>Kinh Nghiệm</th>
                    <th>Kế Hoạch Dự Án</th>
                    <th>Thông Tin Liên Hệ</th>
                    <th>Thời Gian Tạo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($bid = $result->fetch_assoc()): ?>
                    <tr>
                        <td><a href="/client/xemduan.php?id=<?php echo htmlspecialchars($bid['job_id']); ?>"><?php echo htmlspecialchars($bid['job_id']); ?></a></td>
                        <td><?php echo htmlspecialchars($bid['job_title']); ?></td>
                        
                        <!-- Sử dụng receiver_id từ kết quả truy vấn -->
                        <td><a href="viewprofile.php?id=<?php echo htmlspecialchars($bid['receiver_id']); ?>"><?php echo htmlspecialchars($bid['fullnamereceiver']); ?></a></td>
                        
                        <td><?php echo number_format($bid['project_cost']); ?></td>
                        <td><?php echo htmlspecialchars($bid['completion_time']); ?></td>
                        <td><?php echo htmlspecialchars($bid['experience']); ?></td>
                        <td><?php echo htmlspecialchars($bid['project_plan']); ?></td>
                        <td><?php echo htmlspecialchars($bid['contact_info']); ?></td>
                        <td><?php echo htmlspecialchars($bid['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Không có bài chào giá nào được trúng tuyển.</p>
    <?php endif; ?>

    <a href="quanlyduan.php" class="btn btn-primary">Quay lại</a>
</div>


<?php
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/footer.php');
?>
</body>
</html>
