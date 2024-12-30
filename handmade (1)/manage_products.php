<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

require_once 'db.php';

$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm</title>
</head>
<body>
    <h1>Danh Sách Sản Phẩm</h1>
    <a href="add_product.php">Thêm Sản Phẩm Mới</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Sản Phẩm</th>
                <th>Giá</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo $product['name']; ?></td>
                <td><?php echo $product['price']; ?></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $product['id']; ?>">Sửa</a> |
                    <a href="delete_product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
