<?php
include '../database/config.php';
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


// ตรวจสอบสิทธิ์ผู้ใช้ (เช่น ตรวจสอบ Token และตรวจสอบว่าเป็น Admin)
// ...

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->username) && isset($data->email) && isset($data->role)) {
    $id = $data->id;
    $username = $data->username;
    $email = $data->email;
    $role = $data->role;

    try {
        // อัพเดตข้อมูลผู้ใช้
        $stmt = $dbcon->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        $stmt->execute(['username' => $username, 'email' => $email, 'role' => $role, 'id' => $id]);

        echo json_encode(['status' => 'success', 'message' => 'แก้ไขผู้ใช้สำเร็จ']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
?>
