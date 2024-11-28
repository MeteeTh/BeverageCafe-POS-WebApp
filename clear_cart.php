<?php
// เริ่ม session
session_start();

// ลบข้อมูลในตะกร้าทั้งหมดโดยล้าง session
unset($_SESSION['cart']);
unset($_SESSION['qty']);

// Redirect กลับไปยังหน้าเดิมหลังจากลบรายการทั้งหมด
header("Location: ".$_SERVER['HTTP_REFERER']);
?>
