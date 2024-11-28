<?php
// เริ่ม session
session_start();

// ลบ session ทั้งหมดโดยใช้ session_destroy()
session_destroy();

// ล้างค่า session variables
$_SESSION = array();

// ล้าง session cookie โดยกำหนดเวลาหมดอายุให้เป็นเวลาก่อนหนึ่งวัน
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 86400,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect ไปยังหน้า login.php หรือหน้าอื่นๆ ตามที่คุณต้องการ
header("Location: login.php");
exit;
?>
