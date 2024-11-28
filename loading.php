<?php
// ตั้งค่า session cookie ให้มีอายุเฉพาะเมื่อเปิดเว็บไซต์
ini_set('session.cookie_lifetime', 0);
session_start();

// ตรวจสอบว่ามี session login หรือไม่ ถ้าไม่มีให้ redirect ไปที่หน้า login.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title> 
    <link rel="icon" type="image" href="images/andylogoCR.png">
 
    <style>
        body {
        background: linear-gradient(to bottom right, #FFF, #9AFEFF, #FFF, #9AFEFF, #FFF);

    }
        /* ส่วนที่เกี่ยวกับการแสดงไอคอนของการโหลด */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loader {
            width: 120px;
            height: 120px;
            margin: auto;
            margin-top: 40vh; /* ตำแหน่งแถบโหลด */
            display: flex;
            justify-content: center;
            align-items: center;
            animation: spin 1300ms linear infinite; /* การหมุน */
        }

        /* ใช้รูปโลโก้ของคุณในการแสดงแทนไอคอน */
        .loader img {
            width: 400%; /* ปรับขนาดให้เต็มขนาดแถบโหลด */
            height: auto; /* ใช้สัดส่วนของรูปภาพ */
            border-radius: 50%; /* ทำให้รูปโลโก้มีขอบเสมอ */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); /* เพิ่มเอฟเฟกต์เงาให้กับโลโก้ */
            animation: pulse 2s infinite alternate; /* เพิ่มเอฟเฟกต์ที่ทำให้โลโก้ขยับเบาๆ */
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.1);
            }
        }
    </style>
</head>

<body>
     <!-- เพิ่มเสียงดนตรี -->
    <audio autoplay loop>
        <source src="audio/mario.m4a" type="audio/mpeg">
    </audio>
    <div class="loader">
        <img src="images/andylogoC.jpg" alt="Loading...">
    </div>

   

    <script>
        // จำลองการ redirect ไปยัง index.php หลังจากโหลดหน้านี้เสร็จแล้ว
        setTimeout(function() {
            window.location.href = 'home.php';
        }, 2350); 
    </script>
  
</body>

</html>
