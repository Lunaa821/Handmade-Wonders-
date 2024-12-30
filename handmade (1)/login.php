<?php
// Gọi session_start() một lần ở phần đầu tệp (tốt nhất nên gọi trong tệp connection.php)
include 'connection.php';

$error = ''; // Biến để lưu thông báo lỗi

// Kiểm tra nếu người dùng gửi form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = trim($_POST['login_input']); // Email hoặc tên đăng nhập
    $password = $_POST['password'];

    // Kiểm tra nếu các trường không trống
    if (empty($login_input) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ thông tin!';
    } else {
        // Truy vấn kiểm tra tài khoản
        $sql = "SELECT * FROM users WHERE (full_name = ? OR email = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $login_input, $login_input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Kiểm tra mật khẩu
            if (password_verify($password, $user['password_hash'])) {
                // Đăng nhập thành công
                session_start();  // Nếu session chưa được bắt đầu, gọi ở đây
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_id'] = $user['user_id'];

                // Điều hướng dựa trên vai trò
                if ($user['role'] === 'admin') {
                    header("Location: http://localhost/handmade (1)/admin/index.php"); // Điều hướng đến trang quản trị (admin)
                } else {
                    header("Location: index.php"); // Điều hướng đến trang chính (user)
                }
                exit(); // Đảm bảo không có lỗi xảy ra sau khi điều hướng
            } else {
                $error = 'Mật khẩu không đúng!';
            }
        } else {
            $error = 'Tên đăng nhập hoặc email không tồn tại!';
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="css/dangnhapdangki.css">
</head>
<body>
<h1>Đăng Nhập</h1>
<?php if (!empty($error)): ?>
        <div style="color: red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <label for="login_input">Email hoặc Tên đăng nhập:</label>
        <input type="text" id="login_input" name="login_input" required><br><br>

        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Đăng Nhập</button>
    </form>
    <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p></body>
</html>
