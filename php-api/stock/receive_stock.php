<?php
// api/stock/receive_stock.php

include '../../database/config.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ตรวจสอบสิทธิ์ผู้ใช้
// ...

$data = json_decode(file_get_contents("php://input"));

if (isset($data->product_id) && isset($data->quantity)) {
    $product_id = $data->product_id;
    $quantity = $data->quantity;

    try {
        // เพิ่มสต็อกสินค้า
        $stmt = $dbcon->prepare("UPDATE products SET stock = stock + :quantity WHERE id = :product_id");
        $stmt->execute(['quantity' => $quantity, 'product_id' => $product_id]);

        // บันทึกการเคลื่อนไหวของสต็อก
        $stmt = $dbcon->prepare("INSERT INTO stock_movements (product_id, quantity, movement_type) VALUES (:product_id, :quantity, 'in')");
        $stmt->execute(['product_id' => $product_id, 'quantity' => $quantity]);

        echo json_encode(['status' => 'success', 'message' => 'รับสินค้าเข้าสำเร็จ']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}
?>
