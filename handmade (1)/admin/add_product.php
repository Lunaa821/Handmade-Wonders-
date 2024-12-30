<?php
// Kết nối với cơ sở dữ liệu
include '../connection.php';
// Kiểm tra nếu người dùng đã đăng nhập
$userLoggedIn = isset($_SESSION['username']); // Giả sử bạn lưu tên người dùng trong session với key 'username'

// Kiểm tra nếu form được submit
if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_stock = $_POST['product_stock'];
    $product_image_url = $_POST['product_image_url'];

    // Chuẩn bị câu lệnh SQL
$sql = "INSERT INTO Products (name, description, price, stock_quantity, image_url) 
VALUES ('$product_name', '$product_description', '$product_price','product_stock','product_image_url')";
// Thực thi câu lệnh SQL
if ($conn->query($sql) === TRUE) {
  // Chuyển hướng về trang index.php nếu thành công
  header("Location: index.php#products");
  exit(); // Dừng script sau khi chuyển hướng
  } 
  else {
    echo "Lỗi: " . $sql . "<br>" . $conn->error;
}

// Đóng kết nối
$conn->close();
}
?>