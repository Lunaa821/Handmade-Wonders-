<?php
require_once 'db.php'; // Kết nối với cơ sở dữ liệu

// Mã hóa mật khẩu
$password = '123456'; // Mật khẩu gốc
$hashed_password = password_hash($password, PASSWORD_DEFAULT); // Mã hóa mật khẩu

// Câu lệnh SQL để thêm tài khoản admin
$query = "INSERT INTO users (username, email, password, role) 
          VALUES ('admin', 'admin@example.com', ?, 'admin')";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $hashed_password);
$stmt->execute();

echo "Tài khoản admin đã được tạo thành công!";
?>
