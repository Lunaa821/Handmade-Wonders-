<?php

// Kết nối với cơ sở dữ liệu MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kimphung";

$conn = new mysqli($servername, $username, $password, $dbname);
// Kiểm tra nếu session chưa được bắt đầu
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>