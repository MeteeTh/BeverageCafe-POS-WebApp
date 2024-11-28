<?php
// ตั้งค่า session cookie ให้มีอายุเฉพาะเมื่อเปิดเว็บไซต์
ini_set('session.cookie_lifetime', 0);
session_start();

// ตรวจสอบว่ามี session login หรือไม่ ถ้าไม่มีให้ redirect ไปที่หน้า login.php
if (!isset ($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}
// ปรับเปลี่ยน header เพื่อไม่ให้ cache หน้าเว็บ
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>
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

$colname_Rec_reportdetails = "-1";
if (isset ($_POST['menu_id'])) {
  $colname_Rec_reportdetails = $_POST['menu_id'];
}

$textmonth = isset ($_POST['textmonth']) ? $_POST['textmonth'] : 'all'; // กำหนดค่าเริ่มต้นเป็น 'all' หากไม่มีการส่งค่าเดือนมา

$query_Rec_reportdetails = "";
// ตรวจสอบค่าของเดือน
if ($textmonth === 'all') {
  // ถ้าค่าเป็น 'all' ให้ดึงข้อมูลทั้งหมด
  $query_Rec_reportdetails = sprintf("SELECT menulist.menu_name, menutype.type_name, receiptdetail.rc_id, receipt.rc_date, receipt.rc_time, receiptdetail.rcd_qty, (receiptdetail.rcd_price*receiptdetail.rcd_qty) AS TotalPrice, (receiptdetail.rcd_cost*receiptdetail.rcd_qty) AS TotalCost, SUM((receiptdetail.rcd_price-receiptdetail.rcd_cost)*receiptdetail.rcd_qty) AS TotalProfit FROM menulist, menutype, receipt, receiptdetail WHERE menulist.type_id = menutype.type_id AND receipt.rc_id = receiptdetail.rc_id AND receiptdetail.menu_id = menulist.menu_id AND receiptdetail.menu_id = %s GROUP BY receiptdetail.rc_id", GetSQLValueString($_POST['menu_id'], "int"));
} else {
  // ถ้าไม่ใช่ 'all' ให้ดึงข้อมูลตามเดือนที่เลือก
  $query_Rec_reportdetails = sprintf("SELECT menulist.menu_name, menutype.type_name, receiptdetail.rc_id, receipt.rc_date, receipt.rc_time, receiptdetail.rcd_qty, (receiptdetail.rcd_price*receiptdetail.rcd_qty) AS TotalPrice, (receiptdetail.rcd_cost*receiptdetail.rcd_qty) AS TotalCost, SUM((receiptdetail.rcd_price-receiptdetail.rcd_cost)*receiptdetail.rcd_qty) AS TotalProfit FROM menulist, menutype, receipt, receiptdetail WHERE menulist.type_id = menutype.type_id AND receipt.rc_id = receiptdetail.rc_id AND receiptdetail.menu_id = menulist.menu_id AND MONTH(receipt.rc_date) = %s AND receiptdetail.menu_id = %s GROUP BY receiptdetail.rc_id", GetSQLValueString($_POST['textmonth'], "int"), GetSQLValueString($_POST['menu_id'], "int"));
}
mysql_select_db($database_andypos_connect, $andypos_connect);
//$query_Rec_reportdetails = sprintf("SELECT menulist.menu_name, menutype.type_name, receiptdetail.rc_id, receipt.rc_date, receipt.rc_time, receiptdetail.rcd_qty, (receiptdetail.rcd_price*receiptdetail.rcd_qty) AS TotalPrice, (receiptdetail.rcd_cost*receiptdetail.rcd_qty) AS TotalCost, SUM((receiptdetail.rcd_price-receiptdetail.rcd_cost)*receiptdetail.rcd_qty) AS TotalProfit FROM menulist, menutype, receipt, receiptdetail WHERE menulist.type_id = menutype.type_id AND receipt.rc_id = receiptdetail.rc_id AND receiptdetail.menu_id = menulist.menu_id AND MONTH(receipt.rc_date) = %s AND receiptdetail.menu_id = %s GROUP BY receiptdetail.rc_id", GetSQLValueString($_POST['textmonth'], "int"), GetSQLValueString($_POST['menu_id'], "int"));
$Rec_reportdetails = mysql_query($query_Rec_reportdetails, $andypos_connect) or die (mysql_error());
$row_Rec_reportdetails = mysql_fetch_assoc($Rec_reportdetails);
$totalRows_Rec_reportdetails = mysql_num_rows($Rec_reportdetails);
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">



<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Andy Report</title>
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

    /* ซ่อนกรอบและพื้นหลังของปุ่ม */
    button[type="submit"] {
      background: none;
      border: none;
      padding: 0;
      margin: 0;
      cursor: pointer;
    }

    /* ปรับรูปแบบของไอคอน */
    button[type="submit"] i {
      font-size: 30px;
      /* ปรับขนาดไอคอนตามต้องการ */
      color: blueviolet;
      /* เปลี่ยนสีไอคอนตามต้องการ */
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

    /* CSS สำหรับปรับขนาดเมนูและจัดการตาราง */
    .menu-table {
      table-layout: fixed;
    }

    .menu-table th,
    .menu-table td {
      text-align: center;
      /* จัดให้ข้อมูลตรงกลาง */
      vertical-align: middle;
      /* จัดให้ข้อมูลตรงกลางแนวตั้ง */
    }

    .navbar .nav-link.active.custom-color {
      background-color: #0d47a1;
      /* สีพื้นหลังที่เหมือนกับ navbar */
      color: #fff;
      /* เปลี่ยนสีตัวหนังสือเป็นสีขาว */
    }

    /* เปลี่ยนสีของตัวหนังสือเมื่อ hover ที่แถบเมนู */
    .navbar .nav-link:hover {
      color: blue;
      /* เปลี่ยนสีตัวหนังสือเป็นสีเขียวเด่น */
    }

    .link-button {
      background: none;
      border: none;
      padding: 0;
      font: inherit;
      cursor: pointer;
      text-decoration: none;
      color: blue;
      /* สีตัวหนังสือ */
      position: relative;
      /* เพิ่ม position เพื่อให้สามารถใช้ :after ได้ */
      transition: color 0.3s ease;
      /* เพิ่ม transition สำหรับการเปลี่ยนสี */
    }

    .link-button:hover {
      color: darkblue;
      /* เปลี่ยนสีของตัวหนังสือเมื่อ hover */
    }

    .link-button:after {
      content: '';
      position: absolute;
      width: 100%;
      height: 1px;
      bottom: 0;
      left: 0;
      background-color: blue;
      /* สีของขีดเส้นใต้ */
      visibility: hidden;
      transform: scaleX(0);
      transition: all 0.3s ease;
      /* เพิ่ม transition สำหรับการเปลี่ยนขนาดและสีของขีดเส้นใต้ */
    }

    .link-button:hover:after {
      visibility: visible;
      transform: scaleX(1);
    }
  </style>
</head>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="js/bootstrap.bundle.min.js"> </script>
<link rel="stylesheet" type="text/css" href="css/all.min.css">

<body>
  <nav class="navbar " style="background-image: linear-gradient(to right, #1976d2, #1488CC, #2B32B2); font-size: 20px;">
    <ul class="nav nav-pills">
      <li class="nav-item">
        <a class="nav-link" aria-current="page" href="home.php">Andy Coffee & Friends</a>
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
        <a class="nav-link dropdown-toggle active custom-color" data-bs-toggle="dropdown" href="#" role="button"
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
      </style>
    </div>
  </nav>
  <p></p>
  <div style="height: 90vh; overflow-y: scroll;">
    <table width="70%" border="0" align="center" cellpadding="3" cellspacing="3" class="text-kanit">
      <tr>
        <td align="center">
          <h3><strong>
              <?php echo $row_Rec_reportdetails['menu_name']; ?>
            </strong></h3>
          <div style="display: flex; justify-content: center;
            border-bottom: 2px solid #FFD700; width: 50%; ">
          </div>
        </td>
      </tr>

      <tr>

        <td align="center">
          <p><strong>จำนวนบิลทั้งหมด
              <?php echo $totalRows_Rec_reportdetails ?> รายการ
            </strong></p>
          <table border="0" cellpadding="5" cellspacing="5" class="table table-striped table-bordered text-kanit">
            <tr>
              <td align="center"><strong>ลำดับ</strong></td>
              <td align="center"><strong>รหัสบิล</strong></td>
              <td align="center"><strong>วันที่</strong></td>
              <td align="center"><strong>เวลา</strong></td>
              <td align="center"><strong>จำนวนรวม</strong></td>
              <td align="right"><strong>ราคารวม(บาท)</strong></td>
              <td align="right"><strong>ต้นทุนรวม(บาท)</strong></td>
              <td align="right"><strong>กำไรรวม(บาท)</strong></td>
            </tr>
            <?php $i = 1;
            do { ?>
              <tr>
                <td align="center">
                  <?php echo $i; ?>.
                </td>
                <td align="center">
                  <form id="form2" name="form2" method="post" action="bill_search_result.php">
                    <!-- เพิ่มฟิลด์ input hidden เพื่อเก็บค่า rc_id ของแต่ละ row -->
                    <input type="hidden" name="textsearch" value="<?php echo $row_Rec_reportdetails['rc_id']; ?>">
                    <!-- ปุ่ม submit สำหรับส่งค่าไปยัง bill_search_result.php -->
                    <button type="submit" class="link-button">
                      <?php echo $row_Rec_reportdetails['rc_id']; ?>
                    </button>
                  </form>
                </td>
                <td align="center">
                  <?php echo $row_Rec_reportdetails['rc_date']; ?>
                </td>
                <td align="center">
                  <?php echo $row_Rec_reportdetails['rc_time']; ?>
                </td>
                <td align="center">
                  <?php echo $row_Rec_reportdetails['rcd_qty']; ?>
                </td>
                <td align="right">
                  <?php echo $row_Rec_reportdetails['TotalPrice']; ?>
                </td>
                <td align="right">
                  <?php echo $row_Rec_reportdetails['TotalCost']; ?>
                </td>
                <td align="right">
                  <?php echo $row_Rec_reportdetails['TotalProfit']; ?>
                </td>
              </tr>
              <?php $i++;
            } while ($row_Rec_reportdetails = mysql_fetch_assoc($Rec_reportdetails)); ?>
          </table>
        </td>
      </tr>

      <tr>
        <td align="center"><i class="fa-regular fa-circle-left" onclick="goBack()"
            style="cursor: pointer; font-size: 30px;"></i></td>

      </tr>

      <script>
        function goBack() {
          window.history.back();
        }
      </script>
    </table>
</body>

</html>
<?php
mysql_free_result($Rec_reportdetails);
?>