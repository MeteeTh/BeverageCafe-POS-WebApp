<?php

session_start();
$itemId = isset($_GET['itemId']) ? $_GET['itemId'] : "";
if ($_POST) {
    for ($i = 0; $i < count($_POST['qty']); $i++) {
        $key = $_POST['arr_key_' . $i];
        // ตรวจสอบว่าค่าจำนวนใหม่ที่รับมาไม่น้อยกว่า 1 ก่อนที่จะอัปเดตค่าจำนวนในตะกร้า
        if ($_POST['qty'][$i] >= 1) {
            $_SESSION['qty'][$key] = $_POST['qty'][$i];
        }
    }
    header('location:pos.php');
} else {
    // ตรวจสอบว่ามีการส่งค่า itemId มาหรือไม่
    if ($itemId != "") {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
            $_SESSION['qty'][] = array();
        }

        if (in_array($itemId, $_SESSION['cart'])) {
            $key = array_search($itemId, $_SESSION['cart']);
            // เพิ่มเงื่อนไขตรวจสอบว่าจำนวนใหม่ที่เพิ่มเข้ามาไม่ทำให้จำนวนสินค้าติดลบ
            if ($_SESSION['qty'][$key] + 1 >= 1) {
                $_SESSION['qty'][$key] = $_SESSION['qty'][$key] + 1;
                header('location:index.php?a=exists');
            } else {
                // ถ้าจำนวนสินค้าลดลงจนเป็นลบ ไม่ต้องทำอะไร และสามารถแจ้งเตือนได้ตามต้องการ
                header('location:index.php?a=error');
            }
        } else {
            array_push($_SESSION['cart'], $itemId);
            $key = array_search($itemId, $_SESSION['cart']);
            $_SESSION['qty'][$key] = 1;
            header('location:index.php?a=add');
        }
    }
}

