<?php
$host = 'localhost';
$dbname = 'smart_stores';  // ใช้ชื่อฐานข้อมูลที่คุณสร้างใน phpMyAdmin
$username = 'root';  // ชื่อผู้ใช้ MySQL บน XAMPP (ปกติใช้ 'root')
$password = '';  // รหัสผ่าน MySQL บน XAMPP (ปกติไม่ใส่รหัสผ่าน)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRCODE            => PDO::ERRCODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    die("ไม่สามารถเชื่อมต่อกับฐานข้อมูลได้ $dbname :" . $e->getMessage());
}
?>
