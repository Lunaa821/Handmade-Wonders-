<?php
session_start();
include 'connection.php';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Kiểm tra xem sản phẩm có trong giỏ hàng không
    if (isset($_SESSION['cart'][$product_id])) {
        if ($quantity > 0) {
            // Cập nhật số lượng sản phẩm trong giỏ hàng
            $_SESSION['cart'][$product_id] = $quantity;
            echo "Số lượng sản phẩm đã được cập nhật!";
        } else {
            // Xóa sản phẩm nếu số lượng là 0
            unset($_SESSION['cart'][$product_id]);
            echo "Sản phẩm đã được xóa khỏi giỏ hàng!";
        }
    } else {
        echo "Sản phẩm không tồn tại trong giỏ hàng!";
    }
}
?>

