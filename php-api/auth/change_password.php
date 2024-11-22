<?php
// api/auth/change_password.php

include '../../database/config.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// รับข้อมูล JSON
$data = json_decode(file_get_contents("php://input"));

if (isset($data->user_id) && isset($data->old_password) && isset($data->new_password)) {
    $user_id = $data->user_id;
    $old_password = $data->old_password;
    $new_password = password_hash($data->new_password, PASSWORD_DEFAULT);

    try {
        // ดึงข้อมูลผู้ใช้
        $stmt = $dbcon->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($old_password, $user['password'])) {
                // อัพเดตรหัสผ่านใหม่
                $stmt = $dbcon->prepare("UPDATE users SET password = :password WHERE id = :id");
                $stmt->execute(['password' => $new_password, 'id' => $user_id]);

                echo json_encode(['status' => 'success', 'message' => 'เปลี่ยนรหัสผ่านสำเร็จ']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'รหัสผ่านเก่าไม่ถูกต้อง']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ไม่พบผู้ใช้']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ข้อมูลไม่ครบถ้วน']);
}
?>
