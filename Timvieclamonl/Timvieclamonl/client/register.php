<?php
// Kết nối database
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');

// Khởi tạo biến để hiển thị thông báo
$message = '';

// Hàm kiểm tra email đã tồn tại chưa
function checkEmailExists($email, $conn) {
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Hàm kiểm tra số điện thoại đã tồn tại chưa
function checkPhoneExists($phone, $conn) {
    $sql = "SELECT id FROM users WHERE phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Xử lý form đăng ký
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $work_type = $_POST['work_type'];

    // Kiểm tra mật khẩu tối thiểu 8 ký tự
    if (strlen($password) < 8) {
        $message = 'Mật khẩu phải có ít nhất 8 ký tự.';
    } elseif ($password !== $confirm_password) {
        $message = 'Mật khẩu và mật khẩu nhập lại không khớp.';
    } elseif (checkEmailExists($email, $conn)) {
        $message = 'Email đã tồn tại.';
    } elseif (checkPhoneExists($phone, $conn)) {
        $message = 'Số điện thoại đã tồn tại.';
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        
        // Kiểm tra và lưu ảnh đại diện
        if (isset($_FILES['avatar'])) {
            $avatar = $_FILES['avatar'];
            $allowed_extensions = ['jpg', 'jpeg', 'png'];
            $max_file_size = 3 * 1024 * 1024; // 3MB

            // Kiểm tra kích thước file
            if ($avatar['size'] > $max_file_size) {
                $message = 'Kích thước file không được vượt quá 3MB.';
            } else {
                // Lấy phần mở rộng của file
                $extension = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));

                // Kiểm tra định dạng file
                if (!in_array($extension, $allowed_extensions)) {
                    $message = 'Chỉ cho phép upload các định dạng jpg, jpeg, png.';
                } else {
                    // Tạo tên file ngẫu nhiên
                    $random_name = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 6) . '.' . $extension;
                    $upload_dir = 'uploads/'; // Thư mục lưu trữ ảnh
                    $upload_file = $upload_dir . $random_name;

                    // Di chuyển file tới thư mục
                    if (move_uploaded_file($avatar['tmp_name'], $upload_file)) {
                        // Chèn dữ liệu vào bảng users
                        $sql = "INSERT INTO users (fullname, email, phone, password, work_type, sodu, anhdaidien)
                                VALUES (?, ?, ?, ?, ?, '0', ?)";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssssss", $fullname, $email, $phone, $password_hashed, $work_type, $upload_file);

                        if ($stmt->execute()) {
                            $message = 'Đăng ký thành công!';
                             header("Location: client/login.php");
                                                         exit(); 
                        } else {
                            $message = 'Lỗi: ' . $conn->error;
                        }
                    } else {
                        $message = 'Không thể upload file.';
                    }
                }
            }
        }
    }
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
</head>
<body>
<div class="container">
    <h2>Đăng Ký Tài Khoản</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="fullname" class="form-label">Họ và Tên</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" id="fullname" name="fullname" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Địa chỉ Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" required minlength="8">
            </div>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Nhập lại mật khẩu</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="avatar" class="form-label">Ảnh đại diện</label>
            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/png, image/jpeg" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Bạn là:</label><br>
            <input type="radio" id="lamviec" name="work_type" value="lamviec" required class="radio-label">
            <label for="lamviec" class="radio-label">Người làm việc</label>
            <input type="radio" id="doanhnghiep" name="work_type" value="doanhnghiep" required class="radio-label">
            <label for="doanhnghiep" class="radio-label">Người thuê</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Đăng Ký</button>
        <br>
        <br>
<p>Nếu bạn đã có tài khoản thì hãy <a href="/client/login.php">Đăng nhập ngay nhé</a></p>
    </form>
</div>

<script>
    <?php if ($message): ?>
        Swal.fire({
            icon: '<?php echo $message === 'Đăng ký thành công!' ? 'success' : 'error'; ?>',
            title: '<?php echo $message; ?>',
            showConfirmButton: true,
        });
    <?php endif; ?>
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
 <?php
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/footer.php');
?>
</body>
</html>
