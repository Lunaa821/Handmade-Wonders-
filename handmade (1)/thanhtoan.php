<?php
session_start();
include 'connection.php';

// Kiểm tra giỏ hàng có sản phẩm không
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: giohang.php");
    exit();
}

// Tính tổng giá giỏ hàng
$total_price = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // Truy vấn để lấy thông tin sản phẩm
    $sql = "SELECT * FROM Products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Tính tổng giá
    $total_price += $product['price'] * $quantity;
}

// Kiểm tra mã giảm giá
$discount = 0;
if (isset($_SESSION['voucher']) && $_SESSION['voucher'] == 'DISCOUNT10') {
    $discount = $total_price * 0.10; // Giảm giá 10%
    $total_price -= $discount;  // Trừ đi giá trị giảm giá
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
</head>
<body>
    <div class="container">
        <h1>Thông tin thanh toán</h1>

        <!-- Hiển thị sản phẩm trong giỏ hàng -->
        <div id="cart-items">
            <?php
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                // Truy vấn để lấy thông tin sản phẩm
                $sql = "SELECT * FROM Products WHERE product_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();

                // Định dạng giá với dấu phẩy cho hàng nghìn và đơn vị VND
                $formatted_price = number_format($product['price'], 0, ',', '.') . ' VND';
                $formatted_total = number_format($product['price'] * $quantity, 0, ',', '.') . ' VND';
                
                echo "<div class='cart-item'>";
                echo "<div><img src='" . $product['image_url'] . "' alt='" . $product['name'] . "' class='cart-item-image'></div>";
                echo "<div>" . $product['name'] . "</div>";
                echo "<div class='product-price'>" . $formatted_price . "</div>";
                echo "<div class='product-quantity'>" . $quantity . "</div>";
                echo "<div class='product-total'>" . $formatted_total . "</div>";
                echo "</div>";
            }
            ?>
        </div>

        <!-- Thông tin thanh toán -->
        <div class="payment-info">
            <form action="process_checkout.php" method="POST">
                <label for="name">Họ và tên:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="address">Địa chỉ giao hàng:</label>
                <input type="text" id="address" name="address" required><br><br>

                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" required><br><br>

                <label for="payment-method">Phương thức thanh toán:</label>
                <select id="payment-method" name="payment-method">
                    <option value="cash">Thanh toán khi nhận hàng</option>
                    <option value="online">Thanh toán trực tuyến</option>
                </select><br><br>

                <h3>Giá trị đơn hàng:</h3>
                <div>Tổng tiền: <?php echo number_format($total_price, 0, ',', '.') . ' VND'; ?></div>
                <?php if ($discount > 0) { ?>
                    <div>Giảm giá (10%): -<?php echo number_format($discount, 0, ',', '.') . ' VND'; ?></div>
                <?php } ?>
                
                <button type="submit">Xác nhận thanh toán</button>
            </form>
        </div>
    </div>
</body>
</html>
<style>/* General styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    width: 80%;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    font-size: 28px;
    margin-bottom: 20px;
}

h3 {
    font-size: 22px;
    color: #333;
}

/* Cart item */
.cart-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.cart-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}

.cart-item div {
    flex: 1;
    margin: 0 10px;
}

.product-price, .product-total {
    font-size: 16px;
    color: #333;
}

.product-quantity {
    font-size: 16px;
    color: #666;
}

.remove-btn {
    color: #e74c3c;
    text-decoration: none;
    font-weight: bold;
}

/* Form styles */
.payment-info form {
    display: flex;
    flex-direction: column;
    margin-top: 20px;
}

.payment-info label {
    margin-bottom: 5px;
    font-weight: bold;
}

.payment-info input, .payment-info select {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

.payment-info button {
    background-color: #3498db;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
}

.payment-info button:hover {
    background-color: #2980b9;
}

.checkout {
    text-align: right;
    margin-top: 30px;
    font-size: 18px;
}

.checkout .total-price {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 15px;
}

.checkout button {
    padding: 12px 20px;
    background-color: #2ecc71;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.checkout button:hover {
    background-color: #27ae60;
}

/* Back to home button */
.back-to-home {
    text-align: center;
    margin-bottom: 20px;
}

.btn-back-home {
    display: inline-block;
    background-color: #e67e22;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 18px;
}

.btn-back-home:hover {
    background-color: #d35400;
}
</style>
