<?php
// ตั้งค่า session cookie ให้มีอายุเฉพาะเมื่อเปิดเว็บไซต์
ini_set('session.cookie_lifetime', 0);
session_start();

// ตรวจสอบว่ามี session login หรือไม่ ถ้าไม่มีให้ redirect ไปที่หน้า login.php
if (!isset ($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<?php date_default_timezone_set("Asia/Bangkok"); ?>
<?php require_once ('Connections/andypos_connect.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }
}

mysql_select_db($database_andypos_connect, $andypos_connect);
$query_Rec_report = "SELECT  	menulist.menu_price, receiptdetail.menu_id,     menulist.menu_name,     menutype.type_id,     menutype.type_name,     SUM(receiptdetail.rcd_qty) AS total_sold_quantity,     SUM(receiptdetail.rcd_price * receiptdetail.rcd_qty) AS total_sales_amount,     SUM(receiptdetail.rcd_cost * receiptdetail.rcd_qty) AS total_cost,     SUM((receiptdetail.rcd_price - receiptdetail.rcd_cost) * receiptdetail.rcd_qty) AS total_profit,     MONTHNAME(receipt.rc_date) AS Month FROM receiptdetail, menulist, menutype, receipt WHERE menulist.type_id = menutype.type_id AND receiptdetail.menu_id = menulist.menu_id AND receiptdetail.rc_id =receipt.rc_id GROUP BY receiptdetail.menu_id ORDER BY  	total_sold_quantity DESC,total_sales_amount DESC, total_profit DESC LIMIT 3";
$Rec_report = mysql_query($query_Rec_report, $andypos_connect) or die (mysql_error());
$row_Rec_report = mysql_fetch_assoc($Rec_report);
$totalRows_Rec_report = mysql_num_rows($Rec_report);
date_default_timezone_set("Asia/Bangkok"); ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Andy Home</title>
    <link rel="icon" type="image" href="images/andylogoCR.png">
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
    <script src="js/bootstrap.bundle.min.js"> </script>
    <style type="text/css">
        body {
            font-family: Kanit;
            /* background: linear-gradient(to bottom right, #FFF, #9AFEFF, #FFF, #9AFEFF, #FFF); */
            background-image: url('images/bg5.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            padding-top: 120px;
            /* เพิ่ม padding เพื่อให้ navbar ไม่แทรกกับเนื้อหา */
        }




        .navbar .nav-link {
            color: #FFF;
            font-weight: bold;
            font-size: 18px;
            letter-spacing: 1px;
            /* เพิ่มระยะห่างระหว่างตัวอักษร */
        }

        .navbar .nav-link.active.custom-color {
            background-color: #0d47a1;
            color: #fff;
        }

        .navbar .nav-link:hover {
            color: blue;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: -5px;
        }

        .img-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .img-container img {
            width: 30%;
            /* กำหนดให้ภาพเต็มความกว้างของ container */
            height: 70%;
            /* กำหนดให้ภาพเต็มความสูงของ container */
            object-fit: cover;
            /* ปรับขนาดภาพให้เต็ม container โดยไม่เกี่ยวข้องกับสัดส่วนเดิม */
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .img-container img:hover {
            transform: scale(1.3);
        }

        /* เพิ่ม margin-top เพื่อเลื่อนเมนูแนะนำขึ้น */
        .section-title {
            text-align: center;
            margin-bottom: 20px;
            margin-top: -50px;
            /* ปรับตามต้องการ */
        }

        .special-menu {
            padding: 50px 0;
            overflow: hidden;
            /* กำหนดให้ซ่อนเนื้อหาที่เกินขอบของพื้นที่ */
        }

        .menu-row {
            display: flex;
            flex-wrap: wrap;
            margin: -15px;
            /* กำหนดให้มีช่องว่างระหว่างเมนู */
        }

        .menu-col {
            display: flex;
            align-items: center;
            flex: 0 0 calc(33.333% - 30px);
            margin: 15px;
            box-sizing: border-box;
            max-width: 300px;
            /* เพิ่มส่วนนี้เข้าไป */
        }

        .menu-item {
            text-align: center;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
        }

        .menu-item h3 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            /* เปลี่ยนสีข้อความหัวเมนู */
        }

        .menu-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            transition: all 0.3s ease;
            /* เพิ่ม transition เมื่อ hover */
        }

        .menu-description {
            font-size: 20px;
            color: #151B54;
        }

        .menu-item:hover .menu-title {
            color: #0d47a1;
            /* เปลี่ยนสีของหัวเรื่องเมนูเมื่อ hover */
        }
    </style>
</head>

<body>
    <nav class="navbar fixed-top"
        style="background-image: linear-gradient(to right, #1976d2, #1488CC, #2B32B2); font-size: 20px;">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active custom-color" aria-current="page" href="home.php">Andy Coffee & Friends</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Index.php">POS</a>
            </li>
            <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
          aria-expanded="false">Menu</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="menu_edit_menu.php">จัดการรายการเมนู</a></li>
          <li><a class="dropdown-item" href="type_edit_menu.php">จัดการรายการประเภท</a></li>

        </ul>
      </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                    aria-expanded="false">Sale</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="sale_daily.php">รายงานยอดขายประจำวัน</a></li>
                    <li><a class="dropdown-item" href="sale_monthly.php">รายงานยอดขายประจำเดือน</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="sale_report.php">รายงานเมนูขายดี</a></li>
                </ul>
            </li>
        </ul>

        <div class="text-kanit" style="text-align: right; color: #FFFFFF; margin-right: 20px;">
            <span>
                <i class="fas fa-user-circle" style="font-size: 40px; color: #FFFFFF; margin-right: 5px;"></i>
            </span>
            <?php if (isset ($_SESSION['username'])): ?>
                <span style="color: #FFFFFF; margin-right: 110px; ">
                    <strong><?php echo $_SESSION['username']; ?></strong>
                </span>
            <?php endif; ?>

            <a href="logout.php" class="logout-link">
                <i class="fas fa-sign-out-alt" style="font-size: 40px; color: #dc3545;"></i>
                <span class="logout-text"><strong>Logout</strong></span>
            </a>
            <style>
                .navbar .text-kanit {
                    display: flex;
                    align-items: center;
                }

                .logout-link {
                    text-decoration: none;
                    display: inline-block;
                    position: relative;

                }

                .logout-text {
                    opacity: 0;
                    transition: opacity 0.3s, transform 0.3s;
                    position: absolute;
                    bottom: 50%;
                    left: calc(-100% - 10px);
                    transform: translateY(50%) translateX(-60%);
                    color: #dc3545;
                    background-color: white;
                    padding: 2px 8px;
                    font-size: 1.1em;
                    border-radius: 10px;
                    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
                }

                .logout-link:hover .logout-text {
                    opacity: 1;
                    left: -100%;
                }

                .logout-link:hover .fas.fa-sign-out-alt {
                    transform: translateX(5px);
                    transition: transform 0.3s;
                }

                .logout-link:hover+.user-icon-container {
                    transform: translateX(-30px);
                }
            </style>
        </div>

    </nav>
    <div class="wrapper__background-image">
        <div class="container img-container">
            <div class="img-container">
                <img src="images/home2.jpg" alt="Andy Logo">
                <img src="images/igc.jpeg" alt="Home Image" style="cursor: pointer;"
                    onclick="window.open('https://www.instagram.com/andy_coffeeandfriends/', '_blank');">
                <img src="images/home3.jpg" alt="Home Image">
            </div>
        </div>
    </div>
    <h2 class="section-title"> เมนูขายดี </h2>
    <style>
        .col-md-4 {
            width: 30%;
            padding: 10px;
        }

        .menu-container {
            display: flex;
            justify-content: center;
            /* จัดให้อยู่ตรงกลางตามแนวนอน */
            align-items: center;
            /* จัดให้อยู่ตรงกลางตามแนวตั้ง */
            flex-wrap: wrap;
            /* ให้ข้อมูลแสดงไปในส่วนถัดไปเมื่อไม่พอที่จะแสดงในหน้าต่าง */
        }
    </style>

    <div class="menu-container">
        <?php
        $count = 1; // เพิ่มตัวแปรเพื่อนับลำดับเมนู
        do {
            ?>
            <div class="col-md-4">
                <div class="menu-item">
                    <p></p>
                    <h3 class="menu-title">
                        <i class="fa-solid fa-medal" style="color: #FDBD01;"></i>
                        <?php echo $count . ". " . $row_Rec_report['menu_name']; ?> <!-- แสดงลำดับเมนู -->
                    </h3>
                    <p class="menu-description">
                        จำนวนทั้งหมด
                        <?php echo $row_Rec_report['total_sold_quantity']; ?> รายการ
                        <br>
                        ยอดขายทั้งหมด
                        <?php echo number_format($row_Rec_report['total_sales_amount'],2); ?> บาท
                    </p>
                </div>
            </div>
            <?php
            $count++; // เพิ่มค่าตัวแปรนับเมนู
        } while ($row_Rec_report = mysql_fetch_assoc($Rec_report));
        ?>
    </div>



</body>

</html>