<?php
// api/products/add_product.php

include '../../database/config.php';
header("Access-Control-Allow-Origin: *");

// ตั้งค่าเพื่อรองรับการอัพโหลดไฟล์
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบสิทธิ์ผู้ใช้
    // ...

    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // การอัพโหลดรูปภาพ
    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        $target_dir = "../../uploads/";
        $target_file = $target_dir . basename($image["name"]);
        move_uploaded_file($image["tmp_name"], $target_file);
        $image_path = "/uploads/" . basename($image["name"]);
    } else {
        $image_path = null;
    }

    try {
        $stmt = $dbcon->prepare("INSERT INTO products (name, category_id, price, stock, image) VALUES (:name, :category_id, :price, :stock, :image)");
        $stmt->execute([
            'name' => $name,
            'category_id' => $category_id,
            'price' => $price,
            'stock' => $stock,
            'image' => $image_path
        ]);

        echo json_encode(['status' => 'success', 'message' => 'เพิ่มสินค้าสำเร็จ']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}
?>
