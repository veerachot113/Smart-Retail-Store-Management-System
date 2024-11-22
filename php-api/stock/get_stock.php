<?php
// api/stock/get_stock.php

include '../../database/config.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ตรวจสอบสิทธิ์ผู้ใช้
// ...

try {
    $stmt = $dbcon->prepare("SELECT p.id, p.name, p.stock FROM products p");
    $stmt->execute();
    $stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'stock' => $stock]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>
