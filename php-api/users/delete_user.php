<?php
include '../database/config.php';
header("Access-Control-Allow-Origin: http://localhost:3000"); // อนุญาต Origin React App
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // อนุญาต HTTP Methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // อนุญาต Headers
header("Access-Control-Allow-Credentials: true"); // หากต้องใช้ Cookie หรือ Authentication Header
// ตรวจสอบ Preflight Request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ตรวจสอบสิทธิ์ผู้ใช้ (เช่น ตรวจสอบ Token และตรวจสอบว่าเป็น Admin)
// ...

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    $id = $data->id;

    try {
        // ลบผู้ใช้
        $stmt = $dbcon->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);

        echo json_encode(['status' => 'success', 'message' => 'ลบผู้ใช้สำเร็จ']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
?>
