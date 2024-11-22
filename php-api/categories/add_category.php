<?php
// api/categories/add_category.php

include '../../database/config.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ตรวจสอบสิทธิ์ผู้ใช้
// ...

$data = json_decode(file_get_contents("php://input"));

if (isset($data->name)) {
    $name = $data->name;

    try {
        $stmt = $dbcon->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);

        echo json_encode(['status' => 'success', 'message' => 'เพิ่มหมวดหมู่สำเร็จ']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}
?>
