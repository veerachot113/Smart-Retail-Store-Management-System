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

$headers = getallheaders();

if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$token = str_replace('Bearer ', '', $headers['Authorization']);

try {
    // ตรวจสอบ Token
    $stmt = $dbcon->prepare("SELECT * FROM users WHERE token = :token");
    $stmt->execute(['token' => $token]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debug: ตรวจสอบ Token
    if (!$currentUser) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Invalid token']);
        exit();
    }

    // Debug: ตรวจสอบสิทธิ์
    if ($currentUser['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์ในการดูข้อมูลผู้ใช้นี้']);
        exit();
    }

    // ดึงข้อมูลผู้ใช้
    $stmt = $dbcon->prepare("SELECT id, username, email, role FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug: ตรวจสอบข้อมูลผู้ใช้
    echo json_encode(['status' => 'success', 'users' => $users]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการประมวลผลคำขอ: ' . $e->getMessage()]);
}
?>
