<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu</title>
    <link rel="stylesheet" href="style.css"> <!-- Liên kết tới file CSS -->
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #4CAF50;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
        }

        .header p {
            font-size: 1.2rem;
            margin-top: 10px;
        }

        .about-section {
            display: flex;
            flex-wrap: wrap;
            margin-top: 30px;
            gap: 20px;
        }

        .about-text {
            flex: 1;
            min-width: 300px;
        }

        .about-text h2 {
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .about-text p {
            margin-bottom: 15px;
            text-align: justify;
        }

        .about-image {
            flex: 1;
            min-width: 300px;
        }

        .about-image img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .cta-section {
            margin-top: 30px;
            text-align: center;
        }

        .cta-section a {
            text-decoration: none;
            color: #fff;
            background: #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
        }

        .cta-section a:hover {
            background: #45a049;
        }

        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
        }

        footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Về Chúng Tôi</h1>
        <p>Khám phá câu chuyện và sứ mệnh của chúng tôi.</p>
    </div>

    <div class="container">
        <section class="about-section">
            <div class="about-text">
                <h2>Câu chuyện của chúng tôi</h2>
                <p>
                    Chào mừng bạn đến với thế giới của sự sáng tạo và thủ công mỹ nghệ! Chúng tôi là một đội ngũ đam mê với việc tạo ra những sản phẩm handmade độc đáo, chất lượng cao và đầy tình yêu thương. 
                </p>
                <p>
                    Từ những ngày đầu tiên, chúng tôi luôn tin rằng mỗi sản phẩm thủ công đều kể một câu chuyện riêng. Câu chuyện về người tạo ra nó, về sự tỉ mỉ trong từng chi tiết, và về hành trình đưa sản phẩm đến tay bạn.
                </p>
                <p>
                    Sứ mệnh của chúng tôi là mang lại giá trị thật sự qua những sản phẩm thủ công, đồng thời khuyến khích sự sáng tạo và bảo vệ môi trường.
                </p>
            </div>
            <div class="about-image">
                <img src="imag\download (23).jpg" alt="Handmade Products"> <!-- Đường dẫn hình ảnh -->
            </div>
        </section>

        <section class="cta-section">
            <a href="index.php">Quay lại Trang Chủ</a>
        </section>
    </div>
    <footer>
        <p>&copy; 2024 Handmade Blog. <a href="contact.php">Liên hệ chúng tôi</a>.</p>
    </footer>
</body>
</html>
