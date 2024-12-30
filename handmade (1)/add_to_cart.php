<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Kiểm tra nếu giỏ hàng đã có session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity; // Nếu có, tăng số lượng
    } else {
        $_SESSION['cart'][$product_id] = $quantity; // Nếu chưa, thêm mới
    }

    echo "Sản phẩm đã được thêm vào giỏ hàng!";
}
?>
