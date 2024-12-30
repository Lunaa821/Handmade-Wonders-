<?php
session_start();
include 'connection.php';

// Kiểm tra giỏ hàng trong session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Nếu giỏ hàng không tồn tại, khởi tạo một mảng rỗng
}
// Kiểm tra yêu cầu xóa sản phẩm
if (isset($_GET['remove'])) {
    $product_id_to_remove = $_GET['remove'];

    // Kiểm tra nếu sản phẩm tồn tại trong giỏ hàng
    if (isset($_SESSION['cart'][$product_id_to_remove])) {
        // Xóa sản phẩm khỏi giỏ hàng
        unset($_SESSION['cart'][$product_id_to_remove]);
    }

    // Chuyển hướng lại để cập nhật giỏ hàng
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng Magic</title>
 
</head>
<body>
    <div class="container">
        <h1 class="cart-title">Giỏ hàng của bạn</h1>
        <!-- Trở về trang chủ -->
<div class="back-to-home">
    <a href="index.php" class="btn-back-home">Trở về trang chủ</a>
</div>
<!-- Giỏ hàng sẽ được hiển thị ở đây -->
<!-- Hiển thị các sản phẩm trong giỏ hàng -->
<div id="cart-items">
            <?php
            if (count($_SESSION['cart']) > 0) {
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    // Truy vấn thông tin sản phẩm
                    $sql = "SELECT * FROM Products WHERE product_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $product_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $product = $result->fetch_assoc();
// Định dạng giá với dấu phẩy cho hàng nghìn và đơn vị VND
$formatted_price = number_format($product['price'], 0, ',', '.') . ' VND';
$formatted_total = number_format($product['price'] * $quantity, 0, ',', '.') . ' VND';
                    // Hiển thị sản phẩm
                    echo "<div class='cart-item'>";
                    echo "<div><img src='" . $product['image_url'] . "' alt='" . $product['name'] . "' class='cart-item-image'></div>";  // Hiển thị ảnh sản phẩm

                    echo "<div>" . $product['name'] . "</div>";
                    echo "<div class='product-price'>" . $formatted_price . "</div>"; // Giá sản phẩm

                    echo "<div class='product-quantity'>";
                    echo "<input type='number' name='quantity[" . $product_id . "]' value='" . $quantity . "' min='1' onchange='updateQuantity(" . $product_id . ", " . $product['price'] . ")' class='quantity-input'>"; // Số lượng với input
                    echo "</div>";

                    echo "<div class='product-total' id='total-" . $product_id . "'>Tổng: " . $formatted_total . "</div>"; // Tổng giá
                    // Nút xóa sản phẩm
                    echo "<div><a href='giohang.php?remove=" . $product_id . "' class='remove-btn'>Xóa</a></div>";
                    echo "</div>";
                }
            } else {
                echo "<p>Giỏ hàng của bạn đang trống.</p>";
            }
// Tính tổng giá và định dạng
$formatted_total_price = number_format($total_price, 0, ',', '.') . ' VND';
            ?>
        </div>
        <!-- Voucher -->
        <div class="voucher-section">
            <input type="text" id="voucher" placeholder="Nhập mã giảm giá">
            <button onclick="applyVoucher()">Áp dụng</button>
        </div>

        <!-- Tổng giá và nút thanh toán -->
        <div class="checkout">
        <div class="total-price" id="total-price">Tổng giá: <?php echo $formatted_total_price; ?></div>
        <button onclick="checkout()">Thanh toán</button>
        </div>
    </div>
</body>
<script>
    function applyVoucher() {
            var voucher = document.getElementById('voucher').value;
            // Kiểm tra mã giảm giá
            if (voucher === 'DISCOUNT10') {
                alert('Mã giảm giá thành công! Bạn sẽ nhận được 10% giảm giá.');
                // Giả sử giảm giá 10%
                var totalPrice = <?php echo $total_price; ?>;
                var discount = totalPrice * 0.10;
                totalPrice -= discount;
                document.querySelector('.total-price').textContent = "Tổng giá: " + totalPrice.toLocaleString() + "₫";
            } else {
                alert('Mã giảm giá không hợp lệ.');
            }
        }

        function checkout() {
            // Dẫn đến trang thanh toán hoặc xử lý thanh toán ở đây
            alert('Bạn đang được chuyển đến trang thanh toán.');
             window.location.href = "thanhtoan.php"; // Nếu có trang checkout
        }
        // Hàm để cập nhật số lượng và tính lại giá trị tổng
    function updateQuantity(productId, price) {
        const quantityInput = document.querySelector('input[name="quantity[' + productId + ']"]');
        const newQuantity = quantityInput.value;
// Cập nhật giá trị tổng
const totalDiv = document.getElementById('total-' + productId);
        const newTotal = newQuantity * price;

        // Cập nhật tổng giá hiển thị
        totalDiv.innerHTML = "Tổng: " + newTotal.toLocaleString() + " VND";
        // Gửi số lượng mới lên server để cập nhật giỏ hàng
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', newQuantity);

        fetch('update_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Cập nhật giỏ hàng sau khi thay đổi số lượng
            console.log(data);  // Có thể dùng để thông báo hoặc hiển thị kết quả
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
   </script>
</html>
<style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #fdfbfb, #ebedee);
            color: #444;
        }
/* Nút Trở về trang chủ */
.btn-back-home {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    margin-top: 20px;
    text-align: center;
}

.btn-back-home:hover {
    background-color: #45a049;
}

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease;
        }

        .cart-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #444;
            text-align: center;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding: 20px 0;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background: #f9f9f9;
            transform: scale(1.02);
        }

        .cart-item-image{
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #ddd;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-info {
            flex: 1;
            margin-left: 20px;
        }

        .cart-info h4 {
            font-size: 1.4rem;
            margin: 0;
            color: #333;
        }

        .cart-info p {
            margin: 5px 0;
            color: #888;
            font-size: 1rem;
        }

        .cart-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
        }

        .quantity {
            display: flex;
            align-items: center;
        }

        .quantity input {
            width: 60px;
            text-align: center;
            font-size: 1.2rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            margin-left: 15px;
            padding: 5px;
            background: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .quantity input:focus {
            outline: none;
            border-color: #007bff;
        }
        .remove-btn {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
        }
        .voucher-section {
            margin-top: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .voucher-section input {
            flex: 1;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            font-size: 1.1rem;
        }

        .voucher-section input:focus {
            border-color: #007bff;
            outline: none;
        }

        .voucher-section button {
            padding: 12px 25px;
            border: none;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .voucher-section button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }

        .checkout {
            margin-top: 30px;
            text-align: right;
        }

        .checkout button {
            padding: 15px 35px;
            border: none;
            background: linear-gradient(135deg, #28a745, #218838);
            color: #fff;
            font-size: 1.3rem;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .checkout button:hover {
            background: linear-gradient(135deg, #218838, #1e7e34);
            transform: scale(1.05);
        }

        .total-price {
            font-size: 1.4rem;
            font-weight: bold;
            margin-top: 15px;
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>