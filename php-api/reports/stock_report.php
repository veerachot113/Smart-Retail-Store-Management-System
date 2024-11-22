<?php
// api/reports/stock_report.php

include '../../database/config.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ตรวจสอบสิทธิ์ผู้ใช้
// ...

try {
    $stmt = $dbcon->prepare("SELECT id, name, stock FROM products");
    $stmt->execute();
    $report = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'report' => $report]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>
