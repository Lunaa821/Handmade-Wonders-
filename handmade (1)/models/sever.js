const express = require('express');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');
require('dotenv').config();

// Tạo ứng dụng Express
const app = express();

// Middleware
app.use(bodyParser.json());

// Kết nối tới MongoDB
mongoose.connect(process.env.MONGODB_URI, {
    useNewUrlParser: true,
    useUnifiedTopology: true
})
    .then(() => console.log('Kết nối MongoDB thành công!'))
    .catch(err => console.log(err));

// Các route
const authRoutes = require('./routes/auth');
app.use('/api/auth', authRoutes);

// Lắng nghe server
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
    console.log(`Server đang chạy trên cổng ${PORT}`);
});
