<?php
// Kết nối với cơ sở dữ liệu
include '../connection.php';
// Kiểm tra nếu người dùng đã đăng nhập
$userLoggedIn = isset($_SESSION['username']); // Giả sử bạn lưu tên người dùng trong session với key 'username'
// Số sản phẩm trên mỗi trang
$productsPerPage = 10;

// Lấy số trang hiện tại từ URL, mặc định là trang 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Tính toán vị trí bắt đầu cho truy vấn
$start = ($page - 1) * $productsPerPage;

// Truy vấn để lấy tổng số sản phẩm
$totalQuery = "SELECT COUNT(*) AS total FROM Products";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalProducts = $totalRow['total'];

// Tính tổng số trang
$totalPages = ceil($totalProducts / $productsPerPage);

// Truy vấn để lấy sản phẩm với phân trang
$sql = "SELECT * FROM Products LIMIT $start, $productsPerPage";
$result = $conn->query($sql);


// Xử lý xóa sản phẩm
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Bước 1: Xóa các bản ghi liên quan trong bảng order_items
    $delete_order_items_query = "DELETE FROM order_items WHERE product_id = $delete_id";
    $conn->query($delete_order_items_query);

    // Bước 2: Xóa sản phẩm
    $delete_product_query = "DELETE FROM Products WHERE product_id = $delete_id";

    if ($conn->query($delete_product_query)) {
        echo "Sản phẩm đã bị xóa!";
    } else {
     echo "Lỗi khi xóa sản phẩm: " . $conn->error ;
    }
}


// Xử lý sửa sản phẩm
if (isset($_POST['update_product'])) {
    $update_id = $_POST['product_id'];
    $update_name = $_POST['product_name'];
    $update_price = $_POST['product_price'];
    $update_description = $_POST['product_description'];
    $update_stock = $_POST['product_stock'];

    $update_query = "UPDATE Products SET name = '$update_name', price = $update_price, description = '$update_description', stock_quantity = $update_stock WHERE id = $update_id";

    if ($conn->query($update_query)) {
        echo "Sản phẩm đã được cập nhật!";
    } else {
        echo "Lỗi khi cập nhật sản phẩm: " . $conn->error;
    }
}
// Thống kê
$total_users = $conn->query("SELECT COUNT(*) AS count FROM Users")->fetch_assoc()['count'];
$total_products = $conn->query("SELECT COUNT(*) AS count FROM Products")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) AS count FROM Orders")->fetch_assoc()['count'];
$total_contacts = $conn->query("SELECT COUNT(*) AS count FROM contacts")->fetch_assoc()['count'];

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển Quản Trị</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Bảng Điều Khiển Quản Trị</h1>
        </header>
        <nav>
            <ul>
                <li><a href="#dashboard" onclick="showSection('dashboard')">Tổng Quan</a></li>
                <li><a href="#products" onclick="showSection('products')">Sản Phẩm</a></li>
                <li><a href="#users" onclick="showSection('users')">Người Dùng</a></li>
                <li><a href="#settings" onclick="showSection('settings')">Đơn Hàng</a></li>
            </ul>
        </nav>
        <main>
            <section id="dashboard" class="content active">
                <h2>Tổng Quan</h2>
                <p>Chào mừng bạn đến với bảng điều khiển quản trị. Tại đây, bạn có thể xem tóm tắt hoạt động của website.</p>
                <div class="stats">
            <div class="stat">
                <h3>Người dùng</h3>
                <p><?= $total_users ?></p>
            </div>
            <div class="stat">
                <h3>Sản phẩm</h3>
                <p><?= $total_products ?></p>
            </div>
            <div class="stat">
                <h3>Đơn hàng</h3>
                <p><?= $total_orders ?></p>
            </div>
            <div class="stat">
                <h3>Liên hệ</h3>
                <p><?= $total_contacts ?></p>
            </div>
        </div>
            </section>
            <section id="products" class="content">
                <h2>Sản Phẩm</h2>
                <div id="product-list">
                    <!-- Danh sách sản phẩm sẽ được thêm bằng JavaScript -->
                    <?php
if ($result && $result->num_rows > 0) {
    echo "<table class='product-table'>";
    echo "<tr><th>Tên Sản Phẩm</th><th>Mô Tả</th><th>Giá</th><th>Số Lượng</th><th>Ảnh</th></tr>"; // Tiêu đề bảng
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . number_format($row['price'], 0, ',', '.') . " VND</td>";
        echo "<td>" . htmlspecialchars($row['stock_quantity']) . "</td>";
        if (!empty($row['image_url'])) {
            echo "<td><img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['name']) . "' style='width:100px;'></td>";
        } else {
            echo "<td>Không có ảnh</td>";
        }
        echo "<td>";
        // Nút Sửa
        echo "<a href='edit_product.php?product_id=" . $row['product_id'] . "'>Sửa</a> | ";
        // Nút Xóa
        echo "<a href='?delete_id=" . $row['product_id'] . "' onclick='return confirm(\"Bạn có chắc muốn xóa sản phẩm này?\")'>Xóa</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Không có sản phẩm nào trong cơ sở dữ liệu.</p>";
}
// Hiển thị phân trang
echo "<div class='pagination'>";
for ($i = 1; $i <= $totalPages; $i++) {
    echo "<a href='?page=$i#products' class='page-link' data-page='$i' " . ($i == $page ? "class='active'" : "") . ">$i</a> ";
}
echo "</div>";
?>
                </div>
                <div class="add-product-container">
        <h3>Thêm Sản Phẩm Mới</h3>
        <form method="POST" action="add_product.php" enctype="multipart/form-data">
            <input type="text" name="product_name" placeholder="Tên sản phẩm" required>
            <textarea name="product_description" placeholder="Mô tả sản phẩm" required></textarea>
            <input type="number" name="product_price" placeholder="Giá" required>
            <input type="number" name="product_stock" placeholder="Số lượng tồn kho" required>
            <input type="text" name="product_image_url" placeholder="URL hình ảnh sản phẩm">
            <button type="submit" name="add_product">Thêm Sản Phẩm</button>
        </form>
    </div>
            </section>
            <section id="users" class="content">
            <h2 style="text-align: center;">Danh sách Người Dùng</h2>
    
            <table id="user-list">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ và tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Vai trò</th>
                <th>Ngày tạo</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dữ liệu người dùng sẽ được thêm ở đây -->
             
        </tbody>
    </table>
                <h3>Thêm Người Dùng Mới</h3>
                <form action="add_user.php" method="POST">
       
        <label for="name">Họ và tên:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="phone">Số điện thoại:</label><br>
        <input type="text" id="phone" name="phone" required><br><br>

        <label for="address">Địa chỉ:</label><br>
        <input type="text" id="address" name="address" required><br><br>

        <label for="role">Vai trò:</label><br>
        <input type="text" id="role" name="role" required><br><br>

        <label for="password">Mật khẩu:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Thêm người dùng</button>
    </form>
            </section>
            <section id="settings" class="content">
                <h2>Đơn Hàng</h2>
             </section>
        </main>
        <footer>
            <p>© 2024 Bảng Điều Khiển Quản Trị</p>
        </footer>
    </div>
    <script src="script.js"></script>
</body>
</html>