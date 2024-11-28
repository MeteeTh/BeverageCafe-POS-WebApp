<?php
session_start();

// ตรวจสอบว่าผู้ใช้กด submit แล้วหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่าชื่อผู้ใช้และรหัสผ่านถูกต้องหรือไม่ 
    if ($_POST['username'] === 'AndyAdmin' && $_POST['password'] === 'password') {
        // เก็บข้อมูลการ login ใน session
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $_POST['username'];

        // ส่งผู้ใช้ไปยังหน้า index.php
        header("Location: loading.php");
        exit;
    } else {
        // ถ้าชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง ให้แสดงข้อความผิดพลาด
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
// ปรับเปลี่ยน header เพื่อไม่ให้ cache หน้าเว็บ
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>

<!DOCTYPE html>
<html lang="en">

<script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Andy Login</title>
    <link rel="icon" type="image" href="images/andylogoCR.png">
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
    <script src="js/bootstrap.bundle.min.js"> </script>
    <style type="text/css">
        /* เพิ่มสีพื้นหลังไล่สีด้วย linear gradient */
        body {
            /* background: linear-gradient(to bottom right, #FFF, #9AFEFF, #FFF, #9AFEFF, #FFF); /* */
            background-image: url('images/4.png');
            background-size: 100% 100%;
            /* ปรับขนาดรูปภาพให้เต็มพื้นที่ */
            background-repeat: no-repeat;
            /* ไม่ให้ซ้ำซ้อนรูปภาพ */
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;

        }

        .text-kanit {
            font-family: Kanit;
            font-size: 16px;
        }

        .text-kanitx {
            font-family: Kanit;
            color: #000;
            font-size: x-large;
        }

        /* เปลี่ยนสีและตัวหนังสือของปุ่ม Login */
        #Login {
            background-color: #38B6FF;
            /* สีแดง */
            color: #ffffff;
            /* สีขาว */
            width: 90px;
            height: 50px;
            font-family: Kanit;
            font-size: larger;
            border: none;
            /* ลบขอบ */
            border-radius: 10px;
            /* เหลามุม */
            transition: background-color 0.3s ease;
            /* เพิ่ม transition เพื่อสร้างเอฟเฟกต์เมื่อ hover */
        }

        /* เมื่อ hover กับปุ่ม Login */
        #Login:hover {
            background-color: #1c87e4;
            /* เปลี่ยนสีเข้มขึ้นเมื่อ hover */
        }

        /* เปลี่ยนสีและตัวหนังสือของปุ่ม Cancel */
        #Cancel {
            background-color: #FF3B3B;
            /* สีฟ้า */
            color: #ffffff;
            /* สีขาว */
            width: 90px;
            height: 50px;
            font-family: Kanit;
            font-size: larger;
            border: none;
            /* ลบขอบ */
            border-radius: 10px;
            /* เหลามุม */
            transition: background-color 0.3s ease;
            /* เพิ่ม transition เพื่อสร้างเอฟเฟกต์เมื่อ hover */
        }

        /* เมื่อ hover กับปุ่ม Cancel */
        #Cancel:hover {
            background-color: #d32626;
            /* เปลี่ยนสีเข้มขึ้นเมื่อ hover */
        }

        /* เพิ่ม animation เมื่อ hover หรือ focus ให้กับปุ่ม Login และ Cancel */
        #Login:hover,
        #Login:focus,
        #Cancel:hover,
        #Cancel:focus {
            transform: scale(1.05);
            /* เพิ่มขนาดเมื่อ hover หรือ focus */
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;

            transform: translateY(-50%);
            position: absolute;
            top: 45%;
        }

        .rounded-border {
            border-radius: 5px;
            /* ปรับขนาดของขอบเป็นรูปโค้ง */
            border: 1px solid #ccc;
            /* เพิ่มขอบ */
            padding: 8px;
            /* เพิ่มระยะห่างระหว่างข้อความและขอบ */
        }


        .input-container {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            margin: 0 auto;
            /* เพิ่มคำสั่ง margin: 0 auto; เพื่อจัดให้ตรงกลาง */
            margin-bottom: 10px;
            max-width: 200px;
            /* ปรับความกว้างของ input-container ตามต้องการ */
        }

        .input-container i {
            margin-right: 10px;
        }

        .input-container input[type="text"],
        .input-container input[type="password"] {
            flex: 1;
            border: none;
            outline: none;
            font-family: Kanit;
            font-size: 16px;
            width: calc(100% - 30px);
            /* ปรับความกว้างของ input ให้เต็มความกว้างของ container */
        }

        .logo img {
            width: auto;
            height: auto;
            max-width: 400%;
            max-height: 100%;
            border-radius: 40%;
            /* ทำให้รูปโลโก้มีขอบ */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            /* เพิ่มเอฟเฟกต์เงาให้กับโลโก้ */
        }

        .logo {
            max-width: 40px;
            max-height: 40px;

            margin-top: 10vh;
            /* ตำแหน่งโลโก้จากบน */

            display: flex;
            justify-content: center;
            align-items: center;
        }

    
    </style>
</head>

<body>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <tr>
                <div class="logo">
                    <img src="images/andylogoC.jpg" alt="Logo">
                </div>
                <td colspan="3" align="center">
                    <h2 class="text-kanitx">Andy User Login</h2>
                </td>
            </tr>
            <tr>
                <td width="100%" align="left">
                    <div class="input-container">
                        &nbsp;&nbsp;&nbsp;<i class="fas fa-user"></i>
                        <input name="username" type="text" required class="text-kanit rounded-border" id="username4"
                            placeholder="ป้อนชื่อผู้ใช้" autofocus>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="100%" align="left">
                    <div class="input-container">
                        &nbsp;&nbsp;&nbsp;<i class="fas fa-lock"></i>
                        <input name="password" type="password" required class="text-kanit rounded-border" id="password"
                            placeholder="ป้อนรหัสผ่าน" autofocus>
                    </div>
                </td>
            </tr>


            <tr>
                <td colspan="3" align="center">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" align="center"><input name="Login" type="submit" id="Login" value="Login">
                    <input name="Cancel" type="reset" id="Cancel" value="Cancel">
                </td>
            </tr>
        </form>
        <tr>
            <td colspan="3" align="left">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <?php
                // แสดงข้อความผิดพลาด (ถ้ามี)
                if (isset($error)) {
                    echo "<p class='text-kanit' style='color: red;'>$error</p>";
                }
                ?>
            </td>
        </tr>
    </table>

</body>

</html>