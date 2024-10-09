<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');

// Lấy thông tin người dùng từ phiên làm việc
if (isset($_SESSION['user'])) {
    // Giả sử bạn đã lưu thông tin người dùng trong $_SESSION['user']
    $userId = $_SESSION['user']['id'];
    $query = "SELECT anhdaidien FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $avatar = $user['anhdaidien']; // Lưu đường dẫn ảnh đại diện
} else {
    $avatar = "https://static.vecteezy.com/system/resources/previews/014/212/681/non_2x/female-user-profile-avatar-is-a-woman-a-character-for-a-screen-saver-with-emotions-for-website-and-mobile-app-design-illustration-on-a-white-isolated-background-vector.jpg"; // Đường dẫn đến ảnh đại diện mặc định nếu chưa đăng nhập
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Roboto', sans-serif;
    }
    .navbar {
        background-color: #004080; /* Màu xanh đậm cho navbar */
        min-height: 100px; /* Đặt chiều cao tối thiểu cho navbar */
    }
    .navbar-nav .nav-link {
        color: #ffffff; /* Màu chữ trắng cho liên kết */
    }
    .navbar-nav .nav-link:hover {
        color: #f0f0f0; /* Màu chữ khi hover */
    }
    .dropdown-menu {
        background-color: #004080; /* Màu nền cho menu dropdown */
    }
    .dropdown-item {
        color: #ffffff; /* Màu chữ trắng cho item dropdown */
    }
    .dropdown-item:hover {
        background-color: #0059b3; /* Màu nền khi hover trên item dropdown */
    }
    .btn-custom {
        margin-left: 10px; /* Khoảng cách giữa các nút */
    }
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    @media (max-width: 768px) {
        .avatar {
            width: 30px; /* Giảm kích thước avatar trên thiết bị nhỏ */
            height: 30px;
        }
        .dropdown-menu {
            position: static !important; /* Xóa bỏ absolute để dropdown nằm trong flow của document */
            float: none; /* Đảm bảo menu không trôi sang phải */
            width: auto; /* Giữ kích thước phù hợp với nội dung */
            text-align: left; /* Giữ căn chỉnh bên trái cho các mục */
        }
    }
</style>
<title><?php echo $thongtinweb['title']; ?></title>
<meta name="description" content="<?php echo $thongtinweb['description']; ?>">
<meta property="og:image" content="<?php echo $thongtinweb['image_link']; ?>">

<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">Freelancer</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Chuyển đổi điều hướng">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/home.php">Trang chủ</a>
                </li>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['work_type'] == 'doanhnghiep'): ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/client/quanlyduan.php">Dự án của tôi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/client/lienhe.php">Liên hệ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/client/naptien.php">Nạp tiền</a>
                </li>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['work_type'] == 'lamviec'): ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/client/quanlyfreelancer.php">Chào giá được duyệt</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/client/lienhe.php">Liên hệ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/client/naptien.php">Nạp tiền</a>
                </li>
                <?php endif; ?>
            </ul>
           <div class="d-flex align-items-center">
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['work_type'] == 'doanhnghiep'): ?>
        <a href="/client/dangduan.php" class="btn btn-outline-light btn-custom">Đăng dự án </a>
    <?php endif; ?>
 <div class="dropdown ms-3">
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user'])): ?>
                <!-- Avatar dropdown -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownAvatar" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="/client/<?php echo $avatar; ?>" alt="Avatar" class="avatar">
                        <span class="ms-2 text-light"><?php echo $_SESSION['user']['fullname']; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownAvatar">
                        <li><a class="dropdown-item" href="/client/viewprofile.php?id=<?php echo $_SESSION['user']['id']; ?> ">Quản lý tài khoản</a></li>
                        <li><a class="dropdown-item" href="/client/logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a href="/client/login.php" class="btn btn-outline-light btn-custom">Đăng nhập</a>
                <a href="/client/register.php" class="btn btn-light btn-custom">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
