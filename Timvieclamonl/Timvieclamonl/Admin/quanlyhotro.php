<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/Admin/header.php');



// Lấy danh sách ticket từ cơ sở dữ liệu
$sql = "SELECT * FROM ticket ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
<main class="content">
    <div class="container-fluid p-0">
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">Quản Lý Hỗ Trợ Khách Hàng</h1>
        </div>
        
         <div class="card">
            <div class="card-header pb-0">

                            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped">

        <thead>
            <tr>
                <th>ID</th>
                <th>Số điện thoại</th>
                <th>Email</th>
                <th>Thông tin hỗ trợ</th>
                <th>Thời gian tạo</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($ticket = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $ticket['id']; ?></td>
                        <td><?php echo htmlspecialchars($ticket['phone']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['email']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['message']); ?></td>
                        <td><?php echo $ticket['created_at']; ?></td>
                        
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Không có ticket nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
     </div>
            </div>
        </div>
    </div>
</main>

</div>

<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/Admin/footer.php');
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
