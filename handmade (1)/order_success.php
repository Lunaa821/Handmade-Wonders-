<?php
session_start();
include 'connection.php';

// Lấy thông tin đơn hàng từ URL
$order_id = $_GET['order_id'];

// Truy vấn thông tin đơn hàng
$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán thành công</title>
    <style>
        /* Tổng quan */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }

        /* Container chính */
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Tiêu đề */
        h1 {
            font-size: 24px;
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        /* Đoạn văn */
        p {
            font-size: 18px;
            margin: 10px 0;
        }

        /* Mã đơn hàng và tổng tiền */
        strong {
            color: #000;
        }

        /* Nút trở về */
        a.btn-back-home {
            display: inline-block;
            text-decoration: none;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        a.btn-back-home:hover {
            background-color: #45a049;
        }

        /* Đáp ứng trên màn hình nhỏ */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            h1 {
                font-size: 20px;
            }

            p {
                font-size: 16px;
            }

            a.btn-back-home {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thanh toán thành công</h1>
        <p>Cảm ơn bạn đã mua hàng!</p>
        <p>Mã đơn hàng: <strong>#<?php echo $order['order_id']; ?></strong></p>
        <p>Tổng tiền: <strong><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VND</strong></p>
        <a href="index.php" class="btn-back-home">Quay lại trang chủ</a>
    </div>
</body>
</html>
