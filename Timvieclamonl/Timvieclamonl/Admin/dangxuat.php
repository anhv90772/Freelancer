<?php
session_start();
session_destroy(); // Xóa session
header("Location: /Admin/dangnhap.php"); // Chuyển hướng về trang đăng nhập
exit();
?>
