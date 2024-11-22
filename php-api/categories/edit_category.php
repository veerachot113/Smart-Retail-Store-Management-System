<?php
// api/categories/edit_category.php

include '../../database/config.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ตรวจสอบสิทธิ์ผู้ใช้
// ...

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->name)) {
    $id = $data->id;
    $name = $data->name;

    try {
        $stmt = $dbcon->prepare("UPDATE categories SET name = :name WHERE id = :id");
        $stmt->execute(['name' => $name, 'id' => $id]);

        echo json_encode(['status' => 'success', 'message' => 'แก้ไขหมวดหมู่สำเร็จ']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}
?>
