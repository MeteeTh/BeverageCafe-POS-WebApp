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
// สร้าง recordset Rec_orders
mysql_select_db($database_andypos_connect, $andypos_connect);
$query_Rec_orders = "SELECT receipt.* FROM receipt ORDER BY rc_id DESC";
$Rec_orders = mysql_query($query_Rec_orders, $andypos_connect) or die (mysql_error());
$row_Rec_orders = mysql_fetch_assoc($Rec_orders);
$totalRows_Rec_orders = mysql_num_rows($Rec_orders);

//สร้างตัวแปรเพื่อเก็บค่าของ OrderID ล่าสุด

$id = $row_Rec_orders['rc_id'];


//สร้าง recordset Rec_detail
$maxRows_Rec_detail = 100;
$pageNum_Rec_detail = 0;
if (isset ($_GET['pageNum_Rec_detail'])) {
  $pageNum_Rec_detail = $_GET['pageNum_Rec_detail'];
}
$startRow_Rec_detail = $pageNum_Rec_detail * $maxRows_Rec_detail;

mysql_select_db($database_andypos_connect, $andypos_connect);
$query_Rec_detail = "SELECT receiptdetail.*, menulist.menu_name, receiptdetail.rcd_qty*receiptdetail.rcd_price AS Total FROM receiptdetail, menulist WHERE (receiptdetail.menu_id = menulist.menu_id) AND (rc_id = {$id}) ORDER BY menulist.menu_name";
$query_limit_Rec_detail = sprintf("%s LIMIT %d, %d", $query_Rec_detail, $startRow_Rec_detail, $maxRows_Rec_detail);
$Rec_detail = mysql_query($query_limit_Rec_detail, $andypos_connect) or die (mysql_error());
$row_Rec_detail = mysql_fetch_assoc($Rec_detail);
date_default_timezone_set("Asia/Bangkok");
if (isset ($_GET['totalRows_Rec_detail'])) {
  $totalRows_Rec_detail = $_GET['totalRows_Rec_detail'];
} else {
  $all_Rec_detail = mysql_query($query_Rec_detail);
  $totalRows_Rec_detail = mysql_num_rows($all_Rec_detail);
}
$totalPages_Rec_detail = ceil($totalRows_Rec_detail / $maxRows_Rec_detail) - 1;

?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Andy Bill</title>
  <link rel="icon" type="image" href="images/andylogoCR.png">

  <style type="text/css">
    body {
      background-image: url('images/bg5.jpg');
      background-size: cover;
      background-repeat: no-repeat;
    }
    .text-kanit {
      font-family: Kanit;
      font-size: 20px;
    }

    .text-kanit2 {
      font-family: Kanit;
      font-size: 30px;
    }

    .text-bill {
      font-family: Kanit;

    }

    .navbar .nav-link {
      color: #FFF;
      /* ตั้งค่าสีข้อความเป็นสีดำ */
      font-weight: bold;
      /* ตั้งค่าตัวหนังสือเป็นตัวหนา */
      font-family: 'Kanit', sans-serif;
      /* ตั้งค่าแบบอักษรเป็น Kanit */
      font-size: 18px;
      letter-spacing: 1px;
      /* เพิ่มระยะห่างระหว่างตัวอักษร */
    }

    .input-group-text2 {
      display: flex;
      align-items: center;
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      color: var(--bs-body-color);
      text-align: center;
      white-space: nowrap;
      background-color: var(--bs-tertiary-bg);
      border: var(--bs-border-width) solid var(--bs-border-color);
      border-radius: var(--bs-border-radius);
    }


    /* เมื่อโฮเวอร์ไปที่ปุ่มค้นหา */
    .input-group-text2:hover {
      width: 150px;
      /* กำหนดความยาวของปุ่ม */
      background-color: #f0f0f0;
      /* เปลี่ยนสีพื้นหลังของปุ่ม */
      cursor: pointer;
      /* เปลี่ยน cursor เมื่อโฮเวอร์ */
    }

    /* เมื่อโฮเวอร์ออกจากปุ่มค้นหา */
    .input-group-text2:hover::before {
      content: "ค้นหา";
      /* เพิ่มข้อความ "ค้นหา" โผล่ออกมา */
      position: absolute;
      /* ตั้งตำแหน่งให้อยู่หลังปุ่ม */
      margin-left: 50px;
      /* ขยับข้อความไปทางซ้าย */
      font-size: 16px;
      /* กำหนดขนาดตัวอักษร */
      font-family: kanit;
      /* เปลี่ยนฟอนต์ */
      font-weight: bold;
      /* กำหนดให้เป็นตัวหนา */
      color: green;
      /* เปลี่ยนสีข้อความ */
    }

    .hide {
      display: none;
    }

    @keyframes appear {

      0% {
        opacity: 0;
        transform: translateY(-100px);
      }

      100% {
        opacity: 1;
        transform: translateY(0px);
      }
    }

    /* เพิ่มคลาสเพื่อเปลี่ยนสีของลิงก์ที่มีคลาส "active" */
    .navbar .nav-link.active.custom-color {
      background-color: #0d47a1;
      /* สีพื้นหลังที่เหมือนกับ navbar */
      color: #fff;
      /* เปลี่ยนสีตัวหนังสือเป็นสีขาว */
    }

    @media print {

      /* กำหนดขนาดของเอกสารให้เท่ากับข้อมูลใน container */
      body {
        width: auto;
        margin: auto;
      }

      /* ปิดการแสดง Navbar และปุ่มพิมพ์เมื่อพิมพ์เอกสาร */
      .navbar,
      .print-button {
        display: none !important;
      }

      /* ปิดการแสดงข้อความอื่น ๆ เพื่อให้เอกสารมีเฉพาะข้อมูลใน container เท่านั้น */
      * {
        visibility: hidden;
      }


      /* แสดงเฉพาะ container และเนื้อหาภายใน */
      .container,
      .container * {
        visibility: visible;
      }

      /* ปรับขนาดเอกสารให้พอดีกับข้อมูลใน container */
      @page {
        size: auto;
        margin: 0;
      }
    }
  </style>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <script src="js/bootstrap.bundle.min.js"> </script>
  <link rel="stylesheet" type="text/css" href="css/all.min.css">
  <link rel="stylesheet" type="text/css" href="css/cards.css">

</head>

<body>

  <nav class="navbar" style="background-image: linear-gradient(to right, #1976d2, #1488CC, #2B32B2); font-size: 20px;">
    <ul class="nav nav-pills">
      <li class="nav-item">
        <a class="nav-link" aria-current="page" href="home.php">Andy Coffee & Friends</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active custom-color" href="Index.php">POS</a>
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
          <strong>
            <?php echo $_SESSION['username']; ?>
          </strong>
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

        .logout-link:hover+.user-icon-container {
          transform: translateX(-30px);
        }

        .logout-link {
          text-decoration: none;
          display: inline-block;
          position: relative;
        }

        .logout-text {
          opacity: 0;
          transition: opacity 0.3s, transform 0.3s;
          /* เพิ่มการ transition ใน transform */
          position: absolute;
          bottom: 50%;
          left: calc(-100% - 10px);
          transform: translateY(50%) translateX(-60%);
          /* เลื่อนข้อความออกจากด้านขวา */
          color: #dc3545;
          background-color: white;
          padding: 2px 8px;
          font-size: 1.1em;
          border-radius: 10px;
          text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .logout-link:hover .logout-text {
          opacity: 1;
          left: -100%
            /* เลื่อนข้อความกลับมาแสดง */
        }

        .logout-link:hover .fas.fa-sign-out-alt {
          transform: translateX(5px);
          transition: transform 0.3s;
        }

        .container {
          margin-top: -30px;
          background-color: #ffffff;
          border-radius: 10px;
          padding: 30px;
          box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
          max-width: 600px;
          margin-left: auto;
          margin-right: auto;
          transition: transform 0.3s ease;
          overflow: auto;
          /* เพิ่ม overflow เพื่อให้เกิดการเลื่อนข้อมูล */
            word-wrap: break-word;
        }

  

        /*.container:hover {
          transform: scale(1.04);
          /* ปรับขนาดของ container เมื่อ hover เข้าไป 120% */
        /* } */
        
        h1 {
          text-align: center;
          margin-bottom: 20px;
          color: #007bff;
        }

        h2 {
          text-align: center;
          margin-bottom: 10px;
          color: #007bff;
        }

        h3 {
          text-align: center;
          margin-bottom: 10px;
          color: #007bff;
        }

        h4 {
          text-align: center;
          margin-bottom: 10px;
          color: #007bff;
        }

        h5 {
          text-align: center;
          margin-bottom: 10px;
          color: #000;
        }

        h6 {
          text-align: center;
          margin-bottom: 10px;
          color: #000;
        }


        table {
          width: 100%;
          border-collapse: collapse;
          margin-top: 20px;
        }

        th,
        td {
          border: none;
          padding: 8px;
          text-align: center;
        }

        th {
          background-color: #F5F5F5;
          color: #333333;
        }

        p {
          font-size: 16px;
        }

        .total-row td {
          /*background-color: #F5F5F5;*/
          font-weight: bold;
          text-align: right;
        }

        .print-button {
          margin-top: 30px;
          background-color: #007bff;
          color: #ffffff;
          border: none;
          border-radius: 5px;
          padding: 10px 20px;
          cursor: pointer;
          display: block;
          margin-left: auto;
          margin-right: auto;
        }

        .print-button:hover {
          background-color: #0056b3;
        }


        th:nth-child(4),
        td:nth-child(4),
        th:nth-child(5),
        td:nth-child(5) {
          text-align: right;
        }
      </style>
    </div>
  </nav>

  <p>&nbsp;</p>
  <style>
    .logo-container {
      position: fixed;
      /* ตำแหน่งจะถูกติดบนหน้าจอ */
      top: 50px;
      /* ระยะห่างด้านบน */
      left: 10px;
      /* ระยะห่างด้านซ้าย */
      z-index: 9999;
      /* ตั้งค่า Z-index เพื่อให้โลโก้อยู่ข้างหน้าของเนื้อหา */
    }

    .logo {
      width: 120px;
      /* ขนาดความกว้างของโลโก้ */
      height: auto;
      /* สูงจะปรับอัตโนมัติตามอัตราส่วน */
      border-radius: 5px;
      /* ทำให้มีมุมโค้งบางน้อย */
    }

    .open {
      width: 133px;
      /* ขนาดความกว้างของโลโก้ */
      height: auto;
      /* สูงจะปรับอัตโนมัติตามอัตราส่วน */
      border-radius: 5px;
      /* ทำให้มีมุมโค้งบางน้อย */
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
  </style>
  <div class="container text-bill">





    <h4><strong>ใบเสร็จรับเงิน</strong></h4>
    <h6>เลขที่ใบสั่งซื้อ :
      <?php echo $row_Rec_orders['rc_id']; ?>
    </h6>

    <h6>วันที่
      <?php
      // แปลงวันที่ให้เป็นรูปแบบ "วันที่ ... เดือน (ชื่อเดือน) ปีพ.ศ."
      $dateThai = date('d', strtotime($row_Rec_orders['rc_date'])) . " " .
        str_replace(
          array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
          array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'),
          date('F', strtotime($row_Rec_orders['rc_date']))
        ) . " " .
        (date('Y', strtotime($row_Rec_orders['rc_date'])) + 543);
      echo $dateThai;
      ?> เวลา
      <?php echo $row_Rec_orders['rc_time']; ?>น.
    </h6>
    <h6>วิธีการชำระเงิน :
      <?php echo $row_Rec_orders['rc_pt']; ?>
    </h6>


    <table>
      <tr>
        <th>ลำดับ</th>
        <th style="text-align: left">รายการ</th>
        <th>จำนวน</th>
        <th>ราคา</th>
        <th>รวม</th>
      </tr>
      <?php

      do { ?>
        <tr>
          <td>
            <?php echo ($startRow_Rec_detail + 1) ?>.
          </td>
          <td style="text-align: left; overflow: hidden; ">
            <?php echo $row_Rec_detail['menu_name']; ?>
          </td>

          <td>
            <?php echo $row_Rec_detail['rcd_qty']; ?>
          </td>
          <td style="white-space: nowrap;" >
            ฿<?php echo number_format($row_Rec_detail['rcd_price'], 2); ?>
          </td>
          <td>
            ฿<?php echo number_format($row_Rec_detail['Total'], 2); ?>
          </td>
        </tr>
        <?php
        $startRow_Rec_detail++;
        $total += $row_Rec_detail['Total'];
        $num += $row_Rec_detail['rcd_qty'];
      } while ($row_Rec_detail = mysql_fetch_assoc($Rec_detail)); ?>

      <tr class="total-row">
        <td colspan="4">ราคารวมทั้งหมด</td>
        <td>
          <?php echo number_format($total, 2); ?> บาท
        </td>
      </tr>
      <tr class="total-row">
        <td colspan="4">จำนวนสินค้าทั้งหมด</td>
        <td>
          <?php echo $num; ?> รายการ
        </td>
      </tr>
    </table>
    <div style="position: relative; ">
      <img src="images/andylogoC.jpg" alt="Andy Logo" class="logo"
        style="position: absolute; top: 0; right: 0; transform: translate(-50%, 40%);">

    </div>
    <p>------------------------------------------------------------------------</p>
    <strong>ที่อยู่ร้าน Andy Coffee & Friends</strong>
    <p>263/1 ตำบลศิลา, อำเภอเมือง , จังหวัดขอนแก่น</p>

    <strong>วัน-เวลาเปิดปิด</strong>
    <p>วันจันทร์ - วันศุกร์ | เวลา 11:00น. - 19:00น.<br>
      วันเสาร์ - วันอาทิตย์ | เวลา 11:00น. - 18:00.น.</p>


    <button class="print-button" onclick="printReceipt(), adjustPrintSize()">พิมพ์ใบเสร็จ</button>
  </div>
  <style>
    @media print {
      @page {
        margin: 0;
      }

      body {
        margin: 0;
      }
    }
  </style>
  <script>
    function printReceipt() {
      // เรียกใช้งานฟังก์ชันพิมพ์
      window.print();

      // ตรวจสอบว่าการพิมพ์ถูกยกเลิกหรือไม่
      // หากยกเลิกการพิมพ์ ให้แสดง Navbar และปุ่มพิมพ์อีกครั้ง
      window.onafterprint = function () {
        location.reload();
      };
    }

    function adjustPrintSize() {
      // หา element container ที่เป็นข้อมูล
      var container = document.querySelector('.container');

      // หาความกว้างและความสูงของ container
      var containerWidth = container.offsetWidth;
      var containerHeight = container.offsetHeight;

      // หาความสูงและความกว้างของหน้าเอกสาร
      var pageWidth = containerWidth;
      var pageHeight = containerHeight; // เอาออก เพราะมันจะปรับตาม containerHeight แล้ว

      // สร้าง CSS สำหรับปรับขนาดหน้ากระดาษเอกสาร
      var style = document.createElement('style');
      style.textContent = '@media print { @page { size: ' + pageWidth + 'px ' + pageHeight + 'px; margin: 0; } }';

      // เพิ่ม CSS ลงในหัวเอกสาร
      document.head.appendChild(style);
    }

    // เรียกใช้งานฟังก์ชันเมื่อโหลดเพจเสร็จ
    window.onload = adjustPrintSize;

  </script>


</body>

</html>
<?php
mysql_free_result($Rec_orders);

mysql_free_result($Rec_detail);

?>