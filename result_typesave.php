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
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Andy Saved</title>
  <link rel="icon" type="image" href="images/andylogoCR.png">
  <style>
    body {
      font-family: Kanit, Segoe UI, Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #28a745, #43d375, #98e6b9);
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      width: 400px;
      text-align: center;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      background: linear-gradient(135deg, #ffffff, #e5e5e5);
      position: relative;
      overflow: hidden;
    }

    img {
      width: 100px;
      height: 100px;
      margin-bottom: 20px;
      filter: hue-rotate(180deg);
      animation: spin 3s linear infinite;
    }

    h1 {
      color: #28a745;
      margin-bottom: 30px;
      font-size: 36px;
      font-weight: bold;
      text-transform: uppercase;
      animation: rainbowText 5s infinite;
    }

    @keyframes rainbowText {
      0% {
        color: #28a745;
      }

      25% {
        color: #43d375;
      }

      50% {
        color: #98e6b9;
      }

      75% {
        color: #43d375;
      }

      100% {
        color: #28a745;
      }
    }

    p {
      font-size: 20px;
      margin-bottom: 20px;
      line-height: 1.6;
      color: #333333;
    }

    a {
      text-decoration: none;
      color: #ffffff;
      font-weight: bold;
      font-size: 24px;
      border: 2px solid #ffffff;
      border-radius: 5px;
      padding: 10px 20px;
      transition: all 0.3s ease;
      display: inline-block;
      background-color: #28a745;
      animation: pulse 1.5s infinite alternate;
    }

    @keyframes pulse {
      from {
        transform: scale(1);
      }

      to {
        transform: scale(1.1);
      }
    }

    a:hover {
      background-color: #43d375;
      border-color: #43d375;
      transform: translateY(-2px);
    }

    .emoji {
      font-size: 72px;
      margin-bottom: 20px;
      animation: bounce 2s infinite;
    }

    @keyframes bounce {

      0%,
      20%,
      50%,
      80%,
      100% {
        transform: translateY(0);
      }

      40% {
        transform: translateY(-30px);
      }

      60% {
        transform: translateY(-15px);
      }
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    @keyframes shake {
      0% {
        transform: translateX(0);
      }

      20% {
        transform: translateX(-10px);
      }

      40% {
        transform: translateX(10px);
      }

      60% {
        transform: translateX(-10px);
      }

      80% {
        transform: translateX(10px);
      }

      100% {
        transform: translateX(0);
      }
    }

    h1.bounce {
      animation: bounce 0.5s;
    }

    @keyframes bounce {

      0%,
      20%,
      50%,
      80%,
      100% {
        transform: translateY(0);
      }

      40% {
        transform: translateY(-30px);
      }

      60% {
        transform: translateY(-15px);
      }
    }
  </style>
</head>

<body>

  <audio id="volume-audio" autoplay>
    <source src="audio/success2.mp3" type="audio/mpeg">
  </audio>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var audioElement = document.getElementById('volume-audio');

      // เพิ่มระดับเสียงเป็น 2.0 เพื่อทำให้เสียงดังขึ้น
      audioElement.volume = 2.0;
    });
  </script>

  <div class="container">
    <div class="emoji">💾</div>
    <h1 class="bounce">บันทึกข้อมูลเสร็จสิ้น</h1>
    <p>ข้อมูลของคุณถูกบันทึกเรียบร้อยแล้ว</p>
    <a href="type_edit_menu.php">กลับหน้าเมนู</a>
  </div>
</body>


</html>