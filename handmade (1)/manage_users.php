<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.html');
    exit();
}

require_once 'db.php';

$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng</title>
</head>
<body>
    <h1>Danh Sách Người Dùng</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Đăng Nhập</th>
                <th>Email</th>
                <th>Vai Trò</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['id']; ?>">Sửa</a> |
                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
