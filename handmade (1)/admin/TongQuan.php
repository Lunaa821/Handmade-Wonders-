<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'kimphung');

// Kiểm tra lỗi kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thống kê
$total_users = $conn->query("SELECT COUNT(*) AS count FROM Users")->fetch_assoc()['count'];
$total_products = $conn->query("SELECT COUNT(*) AS count FROM Products")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) AS count FROM Orders")->fetch_assoc()['count'];
$total_contacts = $conn->query("SELECT COUNT(*) AS count FROM contacts")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
        }
        .stat {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .nav {
            text-align: center;
            margin-bottom: 20px;
        }
        .nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
        }
        .nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Liên kết điều hướng -->
        <div class="nav">
            <a href="index.php">Trang chủ</a>
            <a href="index.php">Trang admin</a>
        </div>
        
        <h1>Admin Dashboard</h1>
        <div class="stats">
            <div class="stat">
                <h3>Người dùng</h3>
                <p><?= $total_users ?></p>
            </div>
            <div class="stat">
                <h3>Sản phẩm</h3>
                <p><?= $total_products ?></p>
            </div>
            <div class="stat">
                <h3>Đơn hàng</h3>
                <p><?= $total_orders ?></p>
            </div>
            <div class="stat">
                <h3>Liên hệ</h3>
                <p><?= $total_contacts ?></p>
            </div>
        </div>
    </div>
</body>
</html>
