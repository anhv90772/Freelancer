<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/header.php');
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
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

// Kiểm tra xem job_id có được truyền qua không
if (!isset($_GET['job_id']) || !is_numeric($_GET['job_id'])) {
    echo "<script>alert('ID bài đăng không hợp lệ.'); window.location.href='quanlyduan.php';</script>";
    exit();
}

$job_id = intval($_GET['job_id']);

// Lấy số người đã chào giá cho bài đăng này
$sql = "SELECT COUNT(*) as bid_count FROM proposals WHERE job_id = $job_id";
$result = $conn->query($sql);
$bid_count = $result->fetch_assoc()['bid_count'];

// Lấy thông tin bài đăng
$job_sql = "SELECT * FROM jobs WHERE id = $job_id";
$job_result = $conn->query($job_sql);
$job = $job_result->fetch_assoc();

// Kiểm tra thứ tự sắp xếp
$order = isset($_GET['order']) && $_GET['order'] == 'asc' ? 'ASC' : 'DESC';

// Lọc theo trạng thái (0: Chưa xem xét, 1: Trúng tuyển, 2: Từ chối)
$filter_status = isset($_GET['filter_status']) ? intval($_GET['filter_status']) : -1;

// Xử lý trạng thái "Trúng tuyển" hoặc "Từ chối"
if (isset($_POST['bid_id']) && isset($_POST['status'])) {
    $bid_id = intval($_POST['bid_id']);
    $status = intval($_POST['status']); // 1: Trúng tuyển, 2: Từ chối
    $update_sql = "UPDATE proposals SET trungtuyen = $status WHERE id = $bid_id AND job_id = $job_id";

    if ($conn->query($update_sql) === TRUE) {
        // Nếu cập nhật thành công, hiển thị thông báo thành công
        echo "<script>alert('Cập nhật thành công!'); window.location.href='quanlychaogia.php?job_id=$job_id&order=$order&filter_status=$filter_status';</script>";
    } else {
        // Nếu cập nhật không thành công, hiển thị thông báo lỗi
        echo "<script>alert('Cập nhật không thành công: " . addslashes($conn->error) . "'); window.location.href='quanlychaogia.php?job_id=$job_id';</script>";
    }
    exit();
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Chào Giá</title>
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
            background-color: #000; /* Màu nền đen cho tiêu đề */
            color: #000; /* Màu chữ đen */
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
    </style>
</head>
<body>
<div class="container">
    <h2>Quản Lý Chào Giá - <?php echo htmlspecialchars($job['job_title']); ?></h2>
    <p>Số người đã chào giá: <strong><?php echo $bid_count; ?></strong></p>

    <h4>Danh Sách Chào Giá:</h4>

    <!-- Nút lọc -->
    <div class="mb-3">
        <a href="?job_id=<?php echo $job_id; ?>&order=desc" class="btn btn-secondary">Mới Nhất</a>
        <a href="?job_id=<?php echo $job_id; ?>&order=asc" class="btn btn-secondary">Cũ Nhất</a>

        <!-- Thêm nút lọc theo trạng thái -->
        <a href="?job_id=<?php echo $job_id; ?>&filter_status=0" class="btn btn-warning">Chưa xem xét</a>
        <a href="?job_id=<?php echo $job_id; ?>&filter_status=1" class="btn btn-success">Trúng tuyển</a>
        <a href="?job_id=<?php echo $job_id; ?>&filter_status=2" class="btn btn-danger">Từ chối</a>
    </div>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID Chào Giá</th>
                <th>Người Chào Giá</th>
                <th>Số Tiền Chào Giá (VNĐ)</th>
                <th>Thời Gian Hoàn Thành</th>
                <th>Kinh Nghiệm</th>
                <th>Kế Hoạch Dự Án</th>
                <th>Thông Tin Liên Hệ</th>
                <th>Thời Gian Tạo</th>
                 <th>Trạng thái</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Lọc theo trạng thái nếu có
            $status_condition = $filter_status >= 0 ? "AND trungtuyen = $filter_status" : "";

            // Lấy danh sách chào giá, sắp xếp theo thời gian tạo và lọc theo trạng thái
            $bids_sql = "SELECT * FROM proposals WHERE job_id = $job_id $status_condition ORDER BY created_at $order";
            $bids_result = $conn->query($bids_sql);
            while ($bid = $bids_result->fetch_assoc()):
                // Lấy tên đầy đủ của người chào giá từ bảng users
                $sender_id = $bid['sender_id'];
                $user_sql = "SELECT fullname FROM users WHERE id = $sender_id";
                $user_result = $conn->query($user_sql);
                $user = $user_result->fetch_assoc();
                $fullname = $user ? htmlspecialchars($user['fullname']) : 'N/A'; // Kiểm tra nếu không có người dùng
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($bid['id']); ?></td>
                    <td><a href="viewprofile.php?id=<?php echo $sender_id; ?>"><?php echo $fullname; ?></a></td>
                    <td><?php echo number_format($bid['project_cost']); ?></td>
                    <td><?php echo htmlspecialchars($bid['completion_time']); ?></td>
                    <td><?php echo htmlspecialchars($bid['experience']); ?></td>
                    <td><?php echo htmlspecialchars($bid['project_plan']); ?></td>
                    <td><?php echo htmlspecialchars($bid['contact_info']); ?></td>
                                        <td><?php echo htmlspecialchars($bid['created_at']); ?></td>
<td>
    <?php 
        if ($bid['trungtuyen'] == 1) {
            echo "Trúng Tuyển";
        } elseif ($bid['trungtuyen'] == 2) {
            echo "Từ Chối";
        } elseif ($bid['trungtuyen'] == 0) {
            echo "Chờ Xem Xét";
        } else {
            echo "Không xác định"; // Trường hợp giá trị không hợp lệ
        }
    ?>
</td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="bid_id" value="<?php echo $bid['id']; ?>">
                            <button type="submit" name="status" value="1" class="btn btn-success">Trúng tuyển</button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="bid_id" value="<?php echo $bid['id']; ?>">
                            <button type="submit" name="status" value="2" class="btn btn-danger">Từ chối</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="quanlyduan.php" class="btn btn-primary">Quay lại</a>
</div>

 <?php
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/footer.php');
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
