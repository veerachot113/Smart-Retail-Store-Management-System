<?php
$servername = "localhost";
$username = "root";  // ชื่อผู้ใช้ MySQL
$userpass = "";      // รหัสผ่าน MySQL (ถ้าใช้ XAMPP/WAMP และไม่มีรหัสผ่าน ให้ปล่อยว่าง)
$dbname = "smart_store";  // ชื่อฐานข้อมูล

$dsn = "mysql:host=$servername;dbname=$dbname;port=3307";

try {
    $dbcon = new PDO($dsn, $username, $userpass);
    $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
}
catch (PDOException $e) {
    echo "ไม่สามารถเชื่อมต่อกับฐานข้อมูลไม่สำเร็จ: " . $e->getMessage();
}
?>
