<?php
// api/sales/get_sales.php

include '../../database/config.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ตรวจสอบสิทธิ์ผู้ใช้
// ...

try {
    $stmt = $dbcon->prepare("SELECT s.*, u.username FROM sales s LEFT JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC");
    $stmt->execute();
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงรายการสินค้าที่ขาย
    foreach ($sales as &$sale) {
        $stmt = $dbcon->prepare("SELECT si.*, p.name FROM sale_items si LEFT JOIN products p ON si.product_id = p.id WHERE si.sale_id = :sale_id");
        $stmt->execute(['sale_id' => $sale['id']]);
        $sale['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode(['status' => 'success', 'sales' => $sales]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>
