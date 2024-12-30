<?php
// Kết nối với cơ sở dữ liệu
include 'connection.php';
// Kiểm tra nếu người dùng đã đăng nhập
$userLoggedIn = isset($_SESSION['username']); // Giả sử bạn lưu tên người dùng trong session với key 'username'

// Số sản phẩm trên mỗi trang
$products_per_page = 12;

// Lấy trang hiện tại từ URL, mặc định là trang 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $products_per_page;

// Truy vấn để lấy sản phẩm với phân trang
$sql = "SELECT * FROM Products LIMIT $products_per_page OFFSET $offset";
$result = $conn->query($sql);

// Truy vấn để đếm tổng số sản phẩm
$sql_count = "SELECT COUNT(*) AS total FROM Products";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_products = $row_count['total'];
$total_pages = ceil($total_products / $products_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Handmade Store</title>
</head>
<body>

    <!-- Header -->
    <header>
        <!-- Biểu tượng mở menu -->
        <button class="menu-icon" id="menu-open">☰</button>
        <div class="logo-container">
            <div class="logo">
                <img src="imag/z6083666138590_adda912e6480b442257a3ed860d7d993.jpg" alt="Handmade Store Logo">
            </div>
            <h1>Handmade Store</h1>
        </div>
        <div class="search-bar">
  <!-- Hiển thị tên người dùng nếu đã đăng nhập -->
  <?php if (isset($_SESSION['full_name'])): ?>
        <div class="user-name">
            Xin chào, <?php echo $_SESSION['full_name']; ?>!
        </div>
    <?php else: ?>
        <div class="guest-message">
            Chào khách, vui lòng <a href="login.php">đăng nhập</a>.
        </div>
    <?php endif; ?>
            <!-- Biểu tượng giỏ hàng -->
            <div class="cart-icon">
                <a href="..\handmade\giohang.php"><i class="fas fa-shopping-cart"></i></a>
            </div>
            <input type="text" id="search" placeholder="Tìm kiếm sản phẩm...">
            <button onclick="searchProduct()">Tìm kiếm</button>
        </div>

        <!-- Thanh Navbar -->
        <div class="navbar" id="navbar">
            <!-- Nút đóng -->
            <button class="close-icon" id="menu-close">&times;</button>
            <!-- Các mục menu -->
            <ul>
            <li><a href="index.php">TRANG CHỦ</a></li>
             <li><a href="gioithieu.php">GIỚI THIỆU</a></li> <!-- Liên kết tới trang Giới thiệu -->
            <li><a href="lienhe.php">LIÊN HỆ </a></li>
                <li><a href="..\handmade (1)\login.php" id="login-link" onclick="loginPage()">ĐĂNG NHẬP</a></li>
                <li><a href="..\handmade\register.php">ĐĂNG KÍ</a></li>
                <li><a href="javascript:void(0);" id="admin-link" style="display: none;" onclick="adminPage()">Quản lý (Admin)</a></li>
            </ul>
            <!-- Footer -->
            <div class="navbar-footer">
                © Bản quyền thuộc về Hutech. <br> Vận hành bởi VTech Web.
            </div>
        </div>
    </header>
    <!-- Main Banner -->
    <section class="banner">
        <img src="imag\do-handmade-50.jpg">
        <h2>Handmade Wonders</h2>
        <p>Unique gifts made with love and care.</p>

    </section>

    <!-- Product List -->
    <section class="products" id="products">
        <?php
        // Kiểm tra nếu có sản phẩm
        if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
            echo "<img src='" . $row['image_url'] . "' alt='" . $row['name'] . "'>";                       
            echo "<p>" . $row['description'] . "</p>" ;
            echo "<span>$" . $row['price'] . "</span>" ;// Gọi hàm addToCart và truyền thông tin sản phẩm
            // Gọi hàm addToCart và truyền thông tin sản phẩm
            echo "<button onclick=\"addToCart(" . $row['product_id'] . ", '" . $row['name'] . "', " . $row['price'] . ")\">Add to Cart</button>";
            echo "</div>";

        }
        } else {
        echo "<p>Không có sản phẩm nào!</p>";
        }
        ?>
    </section>
    <!-- Pagination -->
    <section class="pagination">
    <ul>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
        <?php endfor; ?>
    </ul>
</section>
    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Handmade Store. All rights reserved.</p>
    </footer>

    <script>
        // Lấy các phần tử
        const menuOpen = document.getElementById('menu-open');
        const menuClose = document.getElementById('menu-close');
        const navbar = document.getElementById('navbar');

        // Mở navbar
        menuOpen.addEventListener('click', () => {
            navbar.classList.add('active');
        });

        // Đóng navbar
        menuClose.addEventListener('click', () => {
            navbar.classList.remove('active');
        });

        // Hàm thêm sản phẩm vào giỏ hàng
function addToCart(productId, productName, productPrice) {
    const quantity = 1;  // Giả sử mỗi lần thêm là 1 sản phẩm

    // Gửi dữ liệu đến server để thêm vào giỏ hàng
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);

    fetch('add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);  // Hiển thị thông báo từ server (sản phẩm đã được thêm vào giỏ hàng)
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

    </script>

</body>
</html>
<style>
    /* Reset */
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    /* Header */
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #f4a261;
        color: white;
    }

        header .logo-container {
            display: flex;
            align-items: center; /* Căn giữa logo và tên cửa hàng theo chiều dọc */
            gap: 10px; /* Khoảng cách giữa logo và tên cửa hàng */
            margin-left: 50px;
        }
/* Thêm style cho phần hiển thị tên tài khoản */
.account-info {
    color: #264653;
    font-weight: bold;
    margin-right: 20px;
}
        header .logo {
            width: 50px; /* Kích thước logo */
            height: 50px;
            background-color: #ffffff;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

            header .logo img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

        header h1 {
            font-size: 1.5em;
            color: #264653;
            margin: 0;
            font-weight: bold;
            letter-spacing: 2px;
        }

        header .search-bar {
            display: flex;
            gap: 10px;
        }

            header .search-bar input {
                padding: 5px;
                width: 200px;
            }

    /* Navigation */
    nav {
        background-color: #264653;
    }

        nav ul {
            display: flex;
            margin: 0;
            padding: 0;
            list-style: none;
            justify-content: center;
        }

            nav ul li {
                padding: 15px 20px;
            }

                nav ul li a {
                    color: white;
                    text-decoration: none;
                }

    /* Banner */
    .banner {
        text-align: center;
        padding: 0;
        height: 50vh;
        background-image: url('C:/xampp/htdocs/handmade/imag/do-handmade-50.jpg'); /* Đường dẫn tới hình ảnh */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: white;
        position: relative;
    }

        .banner h2 {
            font-size: 3em;
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .banner p {
            font-size: 1.5em;
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translateX(-50%);
        }

        .banner img {
            width: 100%; /* Đặt chiều rộng ảnh bằng với chiều rộng của phần chứa */
            height: inherit; /* Giữ tỉ lệ ảnh */
        }
    /* Products */
    .products {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        padding: 20px;
    }

    .product {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        max-width: 200px;
        text-align: center;
        padding: 10px;
    }

        .product img {
            max-width: 100%;
            border-radius: 8px;
        }

        .product h3 {
            margin: 10px 0;
        }

        .product p {
            font-size: 0.9em;
            color: #555;
        }

        .product span {
            font-weight: bold;
            color: #e76f51;
        }

    /* Footer */
    footer {
        background-color: #264653;
        color: white;
        text-align: center;
        padding: 10px;
    }

    /* Cart Section */
    .cart {
        padding: 20px;
        background-color: #f4f4f4;
        margin-top: 20px;
    }

        .cart h2 {
            margin-bottom: 20px;
        }

    .cart-items {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

        .cart-item span {
            font-weight: bold;
        }

    .cart-total {
        font-size: 1.2em;
        margin-top: 20px;
        text-align: right;
    }

    .cart button {
        padding: 10px 20px;
        background-color: #264653;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

        .cart button:hover {
            background-color: #e76f51;
        }

    /* Biểu tượng menu */
    .menu-icon {
        font-size: 1.8rem;
        cursor: pointer;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1000;
        color: #333;
        border: none;
        background: none;
    }

    /* Thanh navbar */
    .navbar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 75%;
        max-width: 300px;
        height: 100%;
        background: #fff;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        transition: left 0.3s ease;
        z-index: 999;
        display: flex;
        flex-direction: column;
        padding: 20px;
    }

    /* Nút đóng navbar */
    .close-icon {
        font-size: 1.5rem;
        cursor: pointer;
        align-self: flex-end;
        margin-bottom: 20px;
        color: #333;
        border: none;
        background: none;
    }

    /* Danh sách menu */
    .navbar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .navbar li {
        margin: 15px 0;
    }

    .navbar a {
        text-decoration: none;
        color: #333;
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }

        .navbar a:hover {
            color: #007bff;
        }

    /* Footer trong navbar */
    .navbar-footer {
        margin-top: auto;
        font-size: 0.9rem;
        color: #888;
        text-align: center;
    }

    /* Hiển thị navbar khi kích hoạt */
    .navbar.active {
        left: 0;
    }

    /* Nội dung chính */
    .content {
        padding: 20px;
    }

    /* Pagination */
    .pagination {
        text-align: center;
        margin-top: 20px;
    }

        .pagination ul {
            list-style: none;
            padding: 0;
            display: inline-flex;
            gap: 10px;
        }

        .pagination li {
            display: inline;
        }

        .pagination a {
            text-decoration: none;
            padding: 10px 15px;
            background-color: #264653;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }

            .pagination a:hover {
                background-color: #e76f51;
            }

        .pagination .disabled {
            color: #ddd;
            cursor: not-allowed;
        }

            .pagination .disabled a {
                background-color: #f4f4f4;
            }
</style>