<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Nếu không phải admin, chuyển về trang login
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Lý Admin</title>
</head>
<body>
    <h1>Trang Quản Lý Admin</h1>
    <ul>
        <li><a href="manage_users.php">Quản Lý Người Dùng</a></li>
        <li><a href="manage_products.php">Quản Lý Sản Phẩm</a></li>
        <li><a href="manage_orders.php">Quản Lý Đơn Hàng</a></li>
        <li><a href="logout.php">Đăng Xuất</a></li>
    </ul>
</body>
</html>
