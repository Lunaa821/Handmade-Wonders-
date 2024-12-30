<?php
// Kết nối với cơ sở dữ liệu
include '../connection.php';
// Kiểm tra nếu người dùng đã đăng nhập
$userLoggedIn = isset($_SESSION['username']); // Giả sử bạn lưu tên người dùng trong session với key 'username'

// Lấy dữ liệu từ form
$full_name = $_POST['name']; // Họ và tên
$email = $_POST['email']; // Email
$phone_number = $_POST['phone']; // Số điện thoại
$address = $_POST['address']; // Địa chỉ
$role = $_POST['role']; // Vai trò
$password_raw = $_POST['password']; // Mật khẩu người dùng nhập

// Mã hóa mật khẩu
$password_hashed = password_hash($password_raw, PASSWORD_BCRYPT);

// Câu lệnh SQL để thêm người dùng vào cơ sở dữ liệu
$sql = "INSERT INTO users (full_name, email, phone_number, address, role, password_hash)
VALUES ('$full_name', '$email', '$phone_number', '$address', '$role', '$password_hashed')";

// Kiểm tra và thực thi câu lệnh SQL
if ($conn->query($sql) === TRUE) {
    echo "Người dùng đã được thêm thành công!";
    sleep(5);

    // Quay lại trang trước sau khi thêm
    header("Location: index.php"); // Chuyển hướng về trang danh sách
    exit;
} else {
    echo "Lỗi: " . $sql . "<br>" . $conn->error;
}
?>