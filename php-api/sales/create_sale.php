<?php
// api/sales/create_sale.php

include '../../database/config.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ตรวจสอบสิทธิ์ผู้ใช้
// ...

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['user_id']) && isset($data['total']) && isset($data['payment_amount']) && isset($data['change_amount']) && isset($data['items'])) {
    $user_id = $data['user_id'];
    $total = $data['total'];
    $payment_amount = $data['payment_amount'];
    $change_amount = $data['change_amount'];
    $items = $data['items'];

    try {
        // เริ่มต้นการทำงานแบบ Transaction
        $dbcon->beginTransaction();

        // บันทึกการขาย
        $stmt = $dbcon->prepare("INSERT INTO sales (user_id, total, payment_amount, change_amount) VALUES (:user_id, :total, :payment_amount, :change_amount)");
        $stmt->execute([
            'user_id' => $user_id,
            'total' => $total,
            'payment_amount' => $payment_amount,
            'change_amount' => $change_amount
        ]);

        $sale_id = $dbcon->lastInsertId();

        // บันทึกรายการสินค้าที่ขาย
        foreach ($items as $item) {
            $stmt = $dbcon->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, price) VALUES (:sale_id, :product_id, :quantity, :price)");
            $stmt->execute([
                'sale_id' => $sale_id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);

            // ตัดสต็อกสินค้า
            $stmt = $dbcon->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :product_id");
            $stmt->execute([
                'quantity' => $item['quantity'],
                'product_id' => $item['product_id']
            ]);

            // บันทึกการเคลื่อนไหวของสต็อก
            $stmt = $dbcon->prepare("INSERT INTO stock_movements (product_id, quantity, movement_type) VALUES (:product_id, :quantity, 'out')");
            $stmt->execute([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity']
            ]);
        }

        // ยืนยันการทำงาน
        $dbcon->commit();

        echo json_encode(['status' => 'success', 'message' => 'บันทึกการขายสำเร็จ', 'sale_id' => $sale_id]);
    } catch (PDOException $e) {
        // ยกเลิกการทำงานหากเกิดข้อผิดพลาด
        $dbcon->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
?>
