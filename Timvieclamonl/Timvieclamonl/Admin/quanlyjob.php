<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/Admin/header.php');

// Khởi tạo biến cho tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$approved = isset($_GET['approved']) ? $_GET['approved'] : 0; // Kiểm tra nếu có yêu cầu xem công việc đã duyệt

// Xây dựng truy vấn SQL
$sql = "SELECT * FROM jobs";

if ($approved) {
    $sql .= " WHERE duyet = 1"; // Chỉ lấy công việc đã duyệt
} else {
    $sql .= " WHERE duyet = 0"; // Chỉ lấy công việc chưa duyệt
}

if (!empty($search)) {
    $sql .= " AND job_title LIKE '%" . $conn->real_escape_string($search) . "%'"; // Tìm kiếm theo tiêu đề công việc
}

// Thêm phần sắp xếp theo ID giảm dần
$sql .= " ORDER BY id DESC"; // Sắp xếp ID từ cao đến thấp

$result = $conn->query($sql);

// Xử lý duyệt công việc
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $job_id = $_GET['id'];
    $status = 1; // Đặt trạng thái thành 1 (đã duyệt)

    // Cập nhật trạng thái công việc
    $sql = "UPDATE jobs SET duyet = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $job_id);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật trạng thái công việc thành công!'); window.location.href='quanlyjob.php';</script>";
        exit();
    } else {
        echo "<script>alert('Có lỗi xảy ra khi cập nhật trạng thái công việc!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Dự Án</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h2 class="mb-4">Quản lý Dự Án</h2>

    <!-- Thanh tìm kiếm -->
    <form action="" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tiêu đề công việc" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        </div>
    </form>

    <!-- Nút xem công việc đã duyệt -->
    <a href="?approved=1" class="btn btn-info mb-3">Xem bài đã duyệt</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề công việc</th>
                <th>Lĩnh vực</th>
                <th>Chi tiết</th>
                <th>Kỹ năng</th>
                <th>Thời hạn</th>
                <th>Giá từ</th>
                <th>Giá đến</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($job = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $job['id']; ?></td>
                        <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($job['field']); ?></td>
                        <td><?php echo htmlspecialchars($job['details']); ?></td>
                        <td><?php echo htmlspecialchars($job['skills']); ?></td>
                        <td><?php echo $job['deadline']; ?></td>
                        <td><?php echo number_format($job['salary_from'], 0, ',', '.'); ?>đ</td>
                        <td><?php echo number_format($job['salary_to'], 0, ',', '.'); ?>đ</td>
                        <td><?php echo $job['duyet'] ? 'Đã duyệt' : 'Chưa duyệt'; ?></td>
                        <td>
                            <?php if ($job['duyet'] == 0): ?>
                                <a href="?action=approve&id=<?php echo $job['id']; ?>" class="btn btn-success" onclick="return confirm('Bạn có chắc chắn muốn duyệt công việc này?');">Duyệt</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">Không có công việc nào <?php echo $approved ? 'đã duyệt' : 'chưa duyệt'; ?>.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/Admin/footer.php');
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
