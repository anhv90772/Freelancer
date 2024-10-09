<?php
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/ketnoi/database.php');
session_start();

// Biến để chứa thông báo
$message = "";

// Xử lý form đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = $_POST['identifier'];  // Email hoặc số điện thoại
    $password = $_POST['password'];

    // Tìm người dùng theo email hoặc số điện thoại
    $sql = "SELECT * FROM users WHERE email = '$identifier' OR phone = '$identifier'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            // Đăng nhập thành công
            $message = "Đăng nhập thành công! Chào mừng bạn trở lại!";
            echo "<script>setTimeout(function(){ window.location.href = '/home.php'; }, 2000);</script>";
        } else {
            $message = "Sai mật khẩu!";
        }
    } else {
        $message = "Sai tất cả vui lòng kiểm tra lại!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <style>
body {
    background: url('https://png.pngtree.com/thumb_back/fh260/back_our/20190625/ourmid/pngtree-simple-atmospheric-gradient-urban-construction-enterprise-ppt-background-image_260238.jpg') no-repeat center center fixed;
    background-size: cover; /* Để hình ảnh phủ kín toàn bộ nền */
    font-family: 'Poppins', sans-serif;
    height: 100vh; /* Đảm bảo body có chiều cao 100% */
    margin: 0; /* Xóa margin mặc định */
}


        .container {
            max-width: 600px;
            margin-top: 50px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            animation: fadeIn 1s;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            transition: all 0.3s ease;
            font-weight: 600;
            border-radius: 25px;
            padding: 10px 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #20c997, #28a745);
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        }
        .form-control {
            border-radius: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }
        .radio-label {
            margin-right: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
        @media (max-width: 576px) {
            h2 {
                font-size: 24px;
            }
        }
    </style>
<div class="container mt-5">
    <h2>Đăng Nhập</h2>

    <!-- Hiển thị thông báo nếu có -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label for="identifier" class="form-label">Email hoặc Số điện thoại</label>
            <input type="text" class="form-control" id="identifier" name="identifier" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
<div class="d-flex justify-content-between align-items-center">
    <a href="/client/quenmk.php">Quên mật khẩu?</a>
    <button type="submit" class="btn btn-primary">Đăng Nhập</button>
</div>

        <br>
<p>Nếu bạn chưa có tài khoản thì hãy <a href="/client/register.php">Đăng ký ngay nhé</a></p>
    </form>
</div>

<!-- Bootstrap JS (để sử dụng thông báo) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
