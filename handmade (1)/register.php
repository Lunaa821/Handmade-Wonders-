
<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ form đăng ký
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password_hash = $_POST['password_hash'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Mã hóa mật khẩu
    $hashedPassword = password_hash($password_hash, PASSWORD_DEFAULT);

    // Giá trị mặc định cho role
    $role = 'user';

    // Kiểm tra xem tên người dùng hoặc email đã tồn tại chưa
    $checkQuery = "SELECT * FROM users WHERE full_name = '$full_name' OR email = '$email'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Nếu tên người dùng hoặc email đã tồn tại, hiển thị thông báo lỗi
        echo "Tên người dùng hoặc email đã tồn tại!";
    } else {
        // Thêm người dùng mới vào cơ sở dữ liệu
        $insertQuery = "INSERT INTO users (full_name, email, password_hash, phone_number, address, role) 
                        VALUES ('$full_name', '$email', '$hashedPassword', '$phone_number', '$address', '$role')";
        if ($conn->query($insertQuery) === TRUE) {
            // Đăng ký thành công, chuyển hướng đến trang đăng nhập
            echo "Đăng ký thành công!";
            header('Location: login.php');
            exit();
        } else {
            echo "Lỗi: " . $conn->error;
        }
    }
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  
</head>

<body>
<style>
        body {
    font-family: 'Arial', sans-serif;
    background-color:orange; /* Màu nền dịu nhẹ */
    padding: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.register-container {
    width: 500px;
    background-color: #ffffff; /* Nền trắng */
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Hiệu ứng đổ bóng */
    transition: transform 0.3s ease-in-out;
}

.register-container:hover {
    transform: scale(1.02); /* Tăng nhẹ kích thước khi hover */
}

.register-container h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #264653; /* Màu chữ */
    font-size: 1.8rem;
    font-weight: bold;
    text-transform: uppercase;
}

input[type="text"],
input[type="password"],
input[type="email"],
input[type="tel"],
textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ced4da; /* Đường viền màu xám */
    border-radius: 8px;
    font-size: 1rem;
    background-color: #f8f9fa;
    transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

input:focus,
textarea:focus {
    border-color: #264653; /* Đổi màu viền khi focus */
    box-shadow: 0 0 8px rgba(38, 70, 83, 0.2); /* Hiệu ứng ánh sáng */
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    background-color: #264653; /* Màu chính */
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
}

button:hover {
    background-color: #2a9d8f; /* Màu khi hover */
    transform: translateY(-2px); /* Hiệu ứng nâng nút */
}

button:active {
    transform: translateY(0); /* Hiệu ứng khi nhấn */
}

.login-link {
    text-align: center;
    margin-top: 20px;
    font-size: 0.9rem;
    color: #264653;
    text-decoration: none;
    transition: color 0.3s ease-in-out;
}

.login-link:hover {
    color: #2a9d8f; /* Màu khi hover */
    text-decoration: underline;
}
    </style>
  <div class="register-container">
  <a href="http://localhost/project_root/wbingosite.com/WebPhone.php"></a>
    <div class="register-header">ĐĂNG KÝ</div>
    <form method="POST" action="register.php">
    <input type="text" id="full-name" name="full_name" placeholder="Tên đăng nhập" required><br>
    <input type="email" id="email" name="email" placeholder="Email" required><br>
    <input type="password" id="password" name="password_hash" placeholder="Mật khẩu" required><br>
    <input type="tel" id="phone" name="phone_number" placeholder="Số điện thoại" required><br>
    <textarea id="address" name="address" placeholder="Địa chỉ" required></textarea><br>
    <button type="submit">Đăng Ký</button>
</form>

    <div class="links">
    <a href="login.html" class="login-link">Đã có tài khoản? Đăng nhập</a>
    </div>
  </div>
</body>
</html>