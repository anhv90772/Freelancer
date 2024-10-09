<?php
// Bắt đầu phiên làm việc
session_start();

// Hủy tất cả các biến phiên
$_SESSION = array();

// Nếu bạn muốn hủy luôn cookie của phiên, ví dụ: để chắc chắn người dùng không thể đăng nhập lại mà không qua trang đăng nhập.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hủy bỏ phiên
session_destroy();

// Chuyển hướng người dùng về trang đăng nhập (hoặc trang chủ)
header("Location: /index.php");
exit();
