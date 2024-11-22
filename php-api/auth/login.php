<?php
include '../database/config.php';
header("Access-Control-Allow-Origin: *"); // Allow requests from all origins
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (isset($data->email) && isset($data->password)) {
    $email = trim($data->email);
    $password = $data->password;

    try {
        $stmt = $dbcon->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $token = bin2hex(random_bytes(16)); // Generate a random token
            $stmt = $dbcon->prepare("UPDATE users SET token = :token WHERE id = :id");
            $stmt->execute(['token' => $token, 'id' => $user['id']]);

            unset($user['password']); // Remove password before returning user data
            $user['token'] = $token;

            echo json_encode([
                "status" => "success",
                "message" => "เข้าสู่ระบบสำเร็จ",
                "user" => $user
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "อีเมลหรือรหัสผ่านไม่ถูกต้อง"
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "เกิดข้อผิดพลาดในการประมวลผลคำขอ: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "ข้อมูลไม่ครบถ้วน"
    ]);
}
?>
