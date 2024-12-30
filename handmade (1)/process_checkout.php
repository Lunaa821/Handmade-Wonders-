<?php
session_start();
include 'connection.php';

// Kiểm tra nếu giỏ hàng rỗng
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo "<p>Giỏ hàng của bạn đang trống.</p>";
    echo "<a href='index.php'>Quay lại mua sắm</a>";
    exit();
}

// Giả định user_id là 1 (nếu đã có hệ thống đăng nhập, hãy lấy user_id từ session)
$user_id = 1;

// Tính tổng giá trị giỏ hàng
$total_amount = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // Truy vấn thông tin sản phẩm
    $sql = "SELECT * FROM Products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Tính tổng giá trị sản phẩm
    $total_amount += $product['price'] * $quantity;
}

// Nếu người dùng nhấn nút "Xác nhận thanh toán"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tạo đơn hàng trong bảng `orders`
    $order_status = "Pending";
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_status) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $user_id, $total_amount, $order_status);
    $stmt->execute();

    // Lấy ID của đơn hàng vừa tạo
    $order_id = $conn->insert_id;

    // Thêm các sản phẩm vào bảng `order_items`
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Truy vấn thông tin sản phẩm
        $sql = "SELECT price FROM Products WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        $unit_price = $product['price'];

        // Thêm sản phẩm vào `order_items`
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $unit_price);
        $stmt->execute();
    }

    // Xóa giỏ hàng sau khi thanh toán thành công
    unset($_SESSION['cart']);

    // Chuyển hướng đến trang xác nhận
    header("Location: order_success.php?order_id=" . $order_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Thanh toán</h1>
        <div class="order-summary">
            <h3>Thông tin đơn hàng</h3>
            <ul>
                <?php
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    // Truy vấn thông tin sản phẩm
                    $sql = "SELECT * FROM Products WHERE product_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $product_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $product = $result->fetch_assoc();

                    echo "<li>" . $product['name'] . " x " . $quantity . " - " . number_format($product['price'], 0, ',', '.') . " VND</li>";
                }
                ?>
            </ul>
            <p><strong>Tổng cộng:</strong> <?php echo number_format($total_amount, 0, ',', '.'); ?> VND</p>
        </div>
        <form method="POST">
            <h3>Thông tin thanh toán</h3>
            <label for="name">Họ và tên:</label>
            <input type="text" id="name" name="name" required>

            <label for="address">Địa chỉ:</label>
            <input type="text" id="address" name="address" required>

            <label for="phone">Số điện thoại:</label>
            <input type="text" id="phone" name="phone" required>

            <button type="submit">Xác nhận thanh toán</button>
        </form>
    </div>
</body>
</html>
