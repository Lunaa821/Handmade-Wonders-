<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Handmade Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            color: #264653;
        }

        .product-management {
            margin-top: 20px;
        }

        .product-management input, .product-management button {
            padding: 10px;
            margin-bottom: 10px;
            width: 200px;
            font-size: 16px;
        }

        .product-list {
            margin-top: 20px;
        }

        .product-item {
            background-color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
        }

        .product-item button {
            background-color: #e76f51;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1>Admin - Quản lý Sản Phẩm</h1>

    <!-- Product Management Form -->
    <div class="product-management">
        <input type="text" id="productName" placeholder="Tên sản phẩm">
        <input type="number" id="productPrice" placeholder="Giá sản phẩm">
        <button onclick="addProduct()">Thêm sản phẩm</button>
    </div>

    <!-- Product List -->
    <div class="product-list" id="productList">
        <!-- Sản phẩm sẽ được hiển thị ở đây -->
    </div>

    <script>
        let products = [];

        // Function to add product
        function addProduct() {
            const name = document.getElementById('productName').value;
            const price = document.getElementById('productPrice').value;

            if (name && price) {
                const product = { name, price };
                products.push(product);
                displayProducts();
            } else {
                alert('Vui lòng cung cấp tên và giá sản phẩm.');
            }
        }

        // Function to display products
        function displayProducts() {
            const productList = document.getElementById('productList');
            productList.innerHTML = '';

            products.forEach((product, index) => {
                const productItem = document.createElement('div');
                productItem.classList.add('product-item');
                productItem.innerHTML = `
                    <span>${product.name} - $${product.price}</span>
                    <button onclick="removeProduct(${index})">Xóa</button>
                `;
                productList.appendChild(productItem);
            });
        }

        // Function to remove product
        function removeProduct(index) {
            products.splice(index, 1);
            displayProducts();
        }
    </script>

</body>
</html>
