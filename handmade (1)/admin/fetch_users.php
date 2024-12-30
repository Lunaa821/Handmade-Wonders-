<?php
// Kết nối với cơ sở dữ liệu
include '../connection.php';
// Kiểm tra nếu người dùng đã đăng nhập
$userLoggedIn = isset($_SESSION['username']); // Giả sử bạn lưu tên người dùng trong session với key 'username'

// Truy vấn lấy danh sách người dùng
$sql = "SELECT user_id, full_name, email, phone_number, address, role, created_at FROM users";
$result = $conn->query($sql);

$users = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row; // Thêm mỗi dòng dữ liệu vào mảng
    }
}
// Xử lý yêu cầu xóa người dùng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $userId = intval($_POST['user_id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Xóa thành công"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Xóa thất bại"]);
    }
    exit;
}

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($users);

$conn->close();
?>
