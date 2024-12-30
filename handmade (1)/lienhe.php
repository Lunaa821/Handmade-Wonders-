<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Kết nối cơ sở dữ liệu
    $conn = new mysqli('localhost', 'root', '', 'kimphung'); // Thay đổi thông tin kết nối nếu cần

    // Kiểm tra lỗi kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    if (!empty($name) && !empty($email) && !empty($message)) {
        // Chuẩn bị truy vấn chèn dữ liệu
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Lỗi chuẩn bị truy vấn: " . $conn->error);
        }
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $success_message = "Đã gửi tin nhắn thành công!";
        } else {
            $error_message = "Đã xảy ra lỗi, vui lòng thử lại.";
        }

        $stmt->close();
    } else {
        $error_message = "Vui lòng điền đầy đủ thông tin.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
        }
        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form input[type="submit"] {
            background: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background: #218838;
        }
        .message {
            text-align: center;
            font-size: 1.2em;
            margin-top: 20px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Liên Hệ với Chúng Tôi</h1>

        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="name">Tên của bạn:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email của bạn:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Tin nhắn của bạn:</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <input type="submit" value="Gửi Tin Nhắn">
        </form>
    </div>
</body>
</html>
