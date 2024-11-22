<?php
include '../database/config.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Extract and clean Token
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}
$token = str_replace('Bearer ', '', $headers['Authorization']);

// Authenticate User
function authenticateUser($token, $dbcon) {
    $stmt = $dbcon->prepare("SELECT * FROM users WHERE token = :token");
    $stmt->execute(['token' => $token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$currentUser = authenticateUser($token, $dbcon);
if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Invalid token']);
    exit();
}

if ($currentUser['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์ในการดำเนินการนี้']);
    exit();
}

// Handle User Data
$data = json_decode(file_get_contents("php://input"));
if (isset($data->username, $data->email, $data->password, $data->role)) {
    $username = trim($data->username);
    $email = trim($data->email);
    $password = password_hash($data->password, PASSWORD_DEFAULT);
    $role = strtolower(trim($data->role));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'รูปแบบอีเมลไม่ถูกต้อง']);
        exit();
    }

    try {
        $stmt = $dbcon->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
        $stmt->execute(['email' => $email, 'username' => $username]);
        if ($stmt->fetch()) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้งานแล้ว']);
            exit();
        }

        $stmt = $dbcon->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password, 'role' => $role]);

        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'เพิ่มผู้ใช้สำเร็จ']);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการประมวลผลคำขอ']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
?>
