<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/Admin/header.php');

// Lấy ID của người dùng cần chỉnh sửa từ URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Lấy thông tin người dùng từ cơ sở dữ liệu
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Nếu người dùng tồn tại
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Người dùng không tồn tại!";
        exit();
    }
} else {
    echo "Không có ID người dùng để chỉnh sửa!";
    exit();
}

// Xử lý khi người dùng gửi form để cập nhật
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password']; // Mã hoá mật khẩu nếu cần
    $work_type = $_POST['work_type'];
    $sodu = $_POST['sodu'];

    // Mã hoá mật khẩu mới nếu cần thay đổi
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $hashed_password = $user['password']; // Giữ mật khẩu cũ
    }

    // Validate dữ liệu
    if (empty($fullname) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Vui lòng nhập đầy đủ và đúng thông tin!";
    } else {
        // Cập nhật thông tin người dùng, không thay đổi ảnh đại diện
        $sql = "UPDATE users SET fullname = ?, email = ?, phone = ?, password = ?, work_type = ?, sodu = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $fullname, $email, $phone, $hashed_password, $work_type, $sodu, $user_id);

        if ($stmt->execute()) {
            echo "Cập nhật thông tin thành công!";
            header("Location: /user_list.php"); // Chuyển hướng sau khi thành công
            exit();
        } else {
            echo "Có lỗi xảy ra khi cập nhật!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Chỉnh sửa người dùng</h1>
        <div class="row">
            <div class="col-md-9 col-xl-10">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Thông tin người dùng</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Tên đầy đủ</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu.</small>
                            </div>
                            <div class="mb-3">
                                <label for="work_type" class="form-label">Loại công việc</label>
                                <select class="form-control" id="work_type" name="work_type">
                                    <option value="freelance" <?php echo $user['work_type'] == 'freelance' ? 'selected' : ''; ?>>Freelance</option>
                                    <option value="doanhnghiep" <?php echo $user['work_type'] == 'doanhnghiep' ? 'selected' : ''; ?>>Doanh nghiệp</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="sodu" class="form-label">Số dư</label>
                                <input type="number" class="form-control" id="sodu" name="sodu" value="<?php echo htmlspecialchars($user['sodu']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            <a href="/user_list.php" class="btn btn-secondary">Quay lại</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/Admin/footer.php');
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
