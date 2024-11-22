<?php
// ค่าแฮชที่ได้จากฐานข้อมูล
$hashedPassword = '$2y$10$5LimhmmHIdKJEGAL7H4gcuFeV/qgxmLtJpRvFJnKomAE0z20.WHAS';

// รหัสผ่านที่คุณต้องการทดสอบ
$inputPassword = 'admin'; 

// แสดงข้อมูล Debug
echo "<strong>แฮชในฐานข้อมูล:</strong> " . $hashedPassword . "<br>";
echo "<strong>รหัสผ่านที่ทดสอบ:</strong> " . $inputPassword . "<br><br>";

// ตรวจสอบรหัสผ่าน
if (password_verify($inputPassword, $hashedPassword)) {
    echo "<strong>ผลลัพธ์:</strong> รหัสผ่านถูกต้อง";
} else {
    echo "<strong>ผลลัพธ์:</strong> รหัสผ่านไม่ถูกต้อง";
}
?>
