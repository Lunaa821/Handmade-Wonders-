-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS kimphung;
USE kimphung;

-- Tạo bảng Users (Thông tin người dùng)
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone_number VARCHAR(20),
    address TEXT,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng Products (Thông tin sản phẩm)
CREATE TABLE IF NOT EXISTS Products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng Orders (Thông tin đơn hàng)
CREATE TABLE IF NOT EXISTS Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    order_status VARCHAR(50) DEFAULT 'pending', -- 'pending', 'shipped', 'delivered', 'cancelled'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Tạo bảng Order_Items (Chi tiết sản phẩm trong đơn hàng)
CREATE TABLE IF NOT EXISTS Order_Items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

-- Tạo bảng Payments (Thông tin thanh toán)
CREATE TABLE IF NOT EXISTS Payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    payment_method VARCHAR(50), -- Ví dụ: 'credit_card', 'paypal', 'cash_on_delivery'
    payment_status VARCHAR(50) DEFAULT 'pending', -- 'pending', 'completed', 'failed'
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id)
);

-- Tạo bảng Cart_Items (Thông tin sản phẩm trong giỏ hàng)
CREATE TABLE IF NOT EXISTS Cart_Items (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (product_id) REFERENCES Products(product_id)
);

-- Tạo bảng Discounts (Thông tin mã giảm giá)
CREATE TABLE IF NOT EXISTS Discounts (
    discount_id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    discount_percentage DECIMAL(5, 2), -- Giảm giá theo phần trăm (ví dụ: 10%)
    start_date TIMESTAMP,
    end_date TIMESTAMP
);

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



-- Tạo chỉ mục cho các trường tìm kiếm phổ biến
CREATE INDEX IF NOT EXISTS idx_user_email ON Users (email);
CREATE INDEX IF NOT EXISTS idx_product_name ON Products (name);
CREATE INDEX IF NOT EXISTS idx_order_user ON Orders (user_id);
CREATE INDEX IF NOT EXISTS idx_cart_user ON Cart_Items (user_id);

-- Thêm người dùng mẫu
INSERT INTO Users (full_name, email, phone_number, address, password_hash)
VALUES 
    ('Nguyễn Văn A', 'nguyenvana@example.com', '0912345678', '123 Đường ABC, TP.HCM', 'hashed_password_here');

-- Thêm sản phẩm mẫu
INSERT INTO Products (name, description, price, stock_quantity, image_url)
VALUES
    ('Sản phẩm 1', 'Mô tả sản phẩm 1', 100000, 50, 'https://via.placeholder.com/150');

-- Thêm đơn hàng mẫu
INSERT INTO Orders (user_id, total_amount, order_status)
VALUES
    (1, 100000, 'pending');

-- Thêm chi tiết sản phẩm trong đơn hàng
INSERT INTO Order_Items (order_id, product_id, quantity, unit_price)
VALUES
    (1, 1, 1, 100000);

-- Thêm thông tin thanh toán mẫu
INSERT INTO Payments (order_id, payment_method, payment_status, amount)
VALUES
    (1, 'credit_card', 'completed', 100000);

-- Thêm mã giảm giá mẫu
INSERT INTO Discounts (code, description, discount_percentage, start_date, end_date)
VALUES
    ('SALE10', 'Giảm 10% cho đơn hàng đầu tiên', 10.00, '2024-12-01', '2024-12-31');
