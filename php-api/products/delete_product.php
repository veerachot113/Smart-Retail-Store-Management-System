<?php
// api/products/delete_product.php

include '../../database/config.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ตรวจสอบสิทธิ์ผู้ใช้
// ...

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    $id = $data->id;

    try {
        $stmt = $dbcon->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);

        echo json_encode(['status' => 'success', 'message' => 'ลบสินค้าสำเร็จ']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}
?>