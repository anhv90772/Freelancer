<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/Admin/header.php');

// Khởi tạo biến để lưu loại công việc đã lọc
$work_type_filter = '';

// Lấy danh sách người dùng từ cơ sở dữ liệu
$query = "SELECT * FROM users"; // Thay đổi bảng và cột theo cơ sở dữ liệu của bạn
$query .= " ORDER BY id DESC"; // Sắp xếp theo ID giảm dần (các ID cao nhất sẽ xuất hiện trước)

// Xử lý tìm kiếm
if (isset($_POST['search'])) {
    $searchTerm = $conn->real_escape_string($_POST['searchTerm']); // Bảo vệ chống SQL Injection
    $query .= " WHERE fullname LIKE '%$searchTerm%'"; // Thay đổi cột theo cơ sở dữ liệu của bạn
    $query .= " ORDER BY id DESC"; // Sắp xếp lại sau khi tìm kiếm
}

// Xử lý lọc loại công việc
if (isset($_POST['filter'])) {
    $work_type_filter = $_POST['work_type'];
    if ($work_type_filter) {
        $query .= (strpos($query, 'WHERE') === false ? ' WHERE ' : ' AND ') . "work_type = '$work_type_filter'";
        $query .= " ORDER BY id DESC"; // Sắp xếp lại sau khi lọc
    }
}

$result = $conn->query($query);
$users = $result->fetch_all(MYSQLI_ASSOC); // Lấy danh sách người dùng

// Xử lý xóa người dùng
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // Chuyển đổi thành số nguyên để bảo vệ
    if ($id > 0) {
        $deleteQuery = "DELETE FROM users WHERE id = $id"; // Thay đổi cột và bảng theo cơ sở dữ liệu của bạn
        $conn->query($deleteQuery);
    }
    header("Location: /Admin/quanlykhachhang.php"); // Chuyển hướng sau khi xóa
    exit;
}
?>

<main class="content">
    <div class="container-fluid p-0">
        <div class="mb-3">
            <h1 class="h3 d-inline align-middle">Quản Lý Tài Khoản Người Dùng</h1>
        </div>

        <div class="card">
            <div class="card-header pb-0">
                <h5 class="card-title mb-0">Danh Sách Người Dùng</h5>
                <form class="d-flex mt-3" method="POST" action="">
                    <input class="form-control me-2" type="search" name="searchTerm" placeholder="Tìm kiếm người dùng..." aria-label="Search">
                    <button class="btn btn-outline-success" type="submit" name="search">Tìm Kiếm</button>
                    <select name="work_type" class="form-select ms-2" aria-label="Lọc theo loại công việc">
                        <option value="">Chọn loại công việc</option>
                        <option value="lamviec" <?php echo $work_type_filter === 'lamviec' ? 'selected' : ''; ?>>Freelance</option>
                        <option value="doanhnghiep" <?php echo $work_type_filter === 'doanhnghiep' ? 'selected' : ''; ?>>Doanh nghiệp</option>
                    </select>
                    <button class="btn btn-outline-primary ms-2" type="submit" name="filter">Lọc</button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Họ và Tên</th>
                                <th>Email</th>
                                <th>Công Việc</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    
                                    <td>
                                        <?php 
                                            if ($user['work_type'] === 'lamviec') {
                                                echo 'Freelance';
                                            } elseif ($user['work_type'] === 'doanhnghiep') {
                                                echo 'Doanh nghiệp';
                                            } else {
                                                echo 'Không xác định'; 
                                            }
                                        ?>
                                    </td>
                                    
                                    <td>
                                        <a href="editkhachhang.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                                        <a href="?delete=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?')">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/Admin/footer.php');
?>
