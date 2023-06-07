<?php
// Khởi động phiên làm việc
session_start();

// Xóa tất cả các biến phiên
session_unset();

// Hủy bỏ phiên làm việc
session_destroy();

// Chuyển hướng người dùng về trang đăng nhập hoặc trang chính của bạn
header("Location: signin.php");
exit;
