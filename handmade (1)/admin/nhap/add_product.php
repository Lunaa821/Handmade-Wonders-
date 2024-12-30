<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db.php';
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image']; // Bạn có thể sử dụng upload file ở đây

    $query = "INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $price, $description, $image);
    $stmt->execute();

    echo "Sản phẩm đã được thêm thành công!";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
</head>
<body>
    <h1>Thêm Sản Phẩm Mới</h1>
    <form action="add_product.php" method="POST">
        <label for="name">Tên Sản Phẩm:</label><br>
        <input type="text" name="name" required><br>
        
        <label for="price">Giá:</label><br>
        <input type="text" name="price" required><br>
        
        <label for="description">Mô Tả:</label><br>
        <textarea name="description" required></textarea><br>
        
        <label for="image">Ảnh:</label><br>
        <input type="text" name="image"><br>
        
        <button type="submit">Thêm Sản Phẩm</button>
    </form>
</body>
</html>
