<?php
include '../connection.php';
// Kiểm tra nếu người dùng đã đăng nhập
$userLoggedIn = isset($_SESSION['username']); // Giả sử bạn lưu tên người dùng trong session với key 'username'
// Lấy product_id từ URL (hoặc từ tham số khác)
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 1; // Thay giá trị mặc định nếu cần

// Truy vấn thông tin sản phẩm
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Nếu tìm thấy sản phẩm, lấy thông tin
$product = $result->fetch_assoc();

// Xử lý cập nhật sản phẩm khi form được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_product"])) {
    // Lấy thông tin sản phẩm từ form
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_stock = $_POST['product_stock'];
    $product_image_url = $_POST['product_image_url'];

    // Chuẩn bị câu lệnh SQL để cập nhật sản phẩm
    $sql = "UPDATE products SET 
                name = ?, 
                description = ?, 
                price = ?, 
                stock_quantity = ?, 
                image_url = ? 
            WHERE product_id = ?";  // Thêm điều kiện WHERE để cập nhật theo ID sản phẩm

    // Chuẩn bị statement
    if ($stmt = $conn->prepare($sql)) {
       // Liên kết tham số và thực thi câu lệnh SQL
       $stmt->bind_param("ssdisi", $product_name, $product_description, $product_price, $product_stock, $product_image_url, $product_id);

        if ($stmt->execute()) {
            echo "Sản phẩm đã được cập nhật thành công!";
            header("Location: index.php");
  exit(); // Dừng script sau khi chuyển hướng
        } else {
            echo "Có lỗi xảy ra: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi câu lệnh SQL: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Sản Phẩm</title>
</head>
<body>
    <div class="add-product-container">
        <h3>Cập Nhật Sản Phẩm</h3>
        <form method="POST">
        <form method="POST">
            <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>" placeholder="Tên sản phẩm" required>
            <textarea name="product_description" placeholder="Mô tả sản phẩm" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            <input type="number" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>" placeholder="Giá" required>
            <input type="number" name="product_stock" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" placeholder="Số lượng tồn kho" required>
            <input type="text" name="product_image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" placeholder="URL hình ảnh sản phẩm">
             <button type="submit" name="update_product">Cập Nhật Sản Phẩm</button>
        </form>
    </div>
</body>
</html>
<style>/* Container chứa form */
.add-product-container {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Tiêu đề form cập nhật sản phẩm */
.add-product-container h3 {
    text-align: center;
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
}

/* Các trường nhập liệu trong form */
.add-product-container input[type="text"],
.add-product-container input[type="number"],
.add-product-container textarea {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

/* Textarea cho mô tả sản phẩm */
.add-product-container textarea {
    resize: vertical;
    min-height: 100px;
}

/* Nút submit trong form */
.add-product-container button {
    width: 100%;
    padding: 12px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Nút hover khi di chuột qua */
.add-product-container button:hover {
    background-color: #45a049;
}

/* Các placeholder trong các trường nhập liệu */
.add-product-container input::placeholder,
.add-product-container textarea::placeholder {
    color: #aaa;
    font-style: italic;
}

/* Thêm khoảng cách giữa các trường nhập liệu */
.add-product-container input,
.add-product-container textarea {
    margin-bottom: 15px;
}
</style>