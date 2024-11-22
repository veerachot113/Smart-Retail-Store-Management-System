<?php
// api/products/edit_product.php

include '../../database/config.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบสิทธิ์ผู้ใช้
    // ...

    $id = $_POST['id'];
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
        if ($image_path) {
            $stmt = $dbcon->prepare("UPDATE products SET name = :name, category_id = :category_id, price = :price, stock = :stock, image = :image WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'category_id' => $category_id,
                'price' => $price,
                'stock' => $stock,
                'image' => $image_path,
                'id' => $id
            ]);
        } else {
            $stmt = $dbcon->prepare("UPDATE products SET name = :name, category_id = :category_id, price = :price, stock = :stock WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'category_id' => $category_id,
                'price' => $price,
                'stock' => $stock,
                'id' => $id
            ]);
        }

        echo json_encode(['status' => 'success', 'message' => 'แก้ไขสินค้าสำเร็จ']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}
?>
