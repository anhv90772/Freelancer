<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/header.php');

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user']['id'])) {
    header('Location: /client/login.php'); // Chuyển hướng nếu chưa đăng nhập
    exit();
}

// Lấy ID người dùng từ session
$user_id = $_SESSION['user']['id'];

// Lấy thông tin của người dùng từ cơ sở dữ liệu
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý cập nhật thông tin cá nhân
    $fullname = $_POST['fullname']; // Cập nhật họ và tên
    $email = $_POST['email']; // Cập nhật email
    $phone = $_POST['phone']; // Cập nhật phone
    $nghenghiep = $_POST['nghenghiep']; // Cập nhật nghề nghiệp
    $mota = $_POST['mota']; // Cập nhật mô tả
    $avatar = $user['anhdaidien']; // Giữ ảnh đại diện hiện tại nếu không có thay đổi

    // Xử lý tải lên tệp ảnh đại diện
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $target_dir = "uploads/";
        $avatar_name = uniqid() . basename($_FILES["avatar"]["name"]);
        $target_file = $target_dir . $avatar_name;
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);
        $avatar = $target_file; // Cập nhật đường dẫn ảnh đại diện mới
    }

    // Cập nhật thông tin người dùng vào cơ sở dữ liệu
    $update_sql = "UPDATE users SET fullname = ?, email = ?, phone = ?, anhdaidien = ?, nghenghiep = ?, mota = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssssi", $fullname, $email, $phone, $avatar, $nghenghiep, $mota, $user_id);
    if ($stmt->execute()) {
        echo "<script>
        alert('Cập nhật thông tin thành công!');
        window.location.href = ''; 
        </script>";
        // Cập nhật lại session với tên đầy đủ mới
        $_SESSION['user']['fullname'] = $fullname;
    } else {
        echo "<script>alert('Có lỗi xảy ra khi cập nhật thông tin.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hồ sơ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        }
        .form-group img {
            max-width: 150px;
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Thông Tin Hồ Sơ</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="profile.php" method="POST" enctype="multipart/form-data" id="profile-form">
        <div class="form-group">
            <label for="fullname">Tên đầy đủ:</label>
            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $user['fullname']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại:</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
        </div>

        <div class="form-group">
            <label for="mota">Mô tả:</label>
            <textarea class="form-control" id="mota" name="mota" required><?php echo $user['mota']; ?></textarea>
        </div>

        <div class="form-group">
            <label for="nghenghiep">Loại công việc:</label>
            <select class="form-control" id="nghenghiep" name="nghenghiep" required>
                <option value="Bán hàng" <?php if ($user['nghenghiep'] === 'Bán hàng') echo 'selected'; ?>>Bán hàng</option>
                <option value="IT" <?php if ($user['nghenghiep'] === 'IT') echo 'selected'; ?>>IT</option>
                <option value="Kiến trúc" <?php if ($user['nghenghiep'] === 'Kiến trúc') echo 'selected'; ?>>Kiến trúc</option>
                <option value="Thiết kế" <?php if ($user['nghenghiep'] === 'Thiết kế') echo 'selected'; ?>>Thiết kế</option>
                <option value="Dịch thuật & viết lách" <?php if ($user['nghenghiep'] === 'Dịch thuật & viết lách') echo 'selected'; ?>>Dịch thuật & viết lách</option>
            </select>
        </div>
        <div class="form-group">
            <label for="avatar">Ảnh đại diện:</label><br>
            <img src="<?php echo $user['anhdaidien']; ?>" alt="Avatar">
            <br>
            <input type="file" class="form-control-file" id="avatar" name="avatar">
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>

 <?php
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/footer.php');
?>
</body>
</html>
