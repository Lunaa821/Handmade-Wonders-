<?php
session_start();
require_once 'db.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra nếu có yêu cầu đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Tìm người dùng trong cơ sở dữ liệu
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Kiểm tra mật khẩu và vai trò
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Lưu vai trò vào session

        // Kiểm tra nếu là admin
        if ($user['role'] === 'admin') {
            header('Location: admin_dashboard.php'); // Chuyển đến trang quản lý admin
        } else {
            header('Location: user_dashboard.php'); // Trang cho người dùng bình thường
        }
    } else {
        echo "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
</head>
<body>
    <h1>Đăng Nhập</h1>
    <form action="login.php" method="POST">
        <label for="username">Tên Đăng Nhập:</label><br>
        <input type="text" name="username" required><br>

        <label for="password">Mật Khẩu:</label><br>
        <input type="password" name="password" required><br>

        <button type="submit">Đăng Nhập</button>
    </form>
</body>
</html>
