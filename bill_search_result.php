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

$colname_Rec_bill = "-1";
if (isset ($_POST['textsearch'])) {
  $colname_Rec_bill = $_POST['textsearch'];
}
mysql_select_db($database_andypos_connect, $andypos_connect);
$query_Rec_bill = sprintf("SELECT * FROM receipt WHERE rc_id = %s", GetSQLValueString($colname_Rec_bill, "int"));
$Rec_bill = mysql_query($query_Rec_bill, $andypos_connect) or die (mysql_error());
$row_Rec_bill = mysql_fetch_assoc($Rec_bill);
$totalRows_Rec_bill = mysql_num_rows($Rec_bill);
date_default_timezone_set("Asia/Bangkok");

$colname_Rec_billdetail = "-1";
if (isset ($_POST['textsearch'])) {
  $colname_Rec_billdetail = $_POST['textsearch'];
}
mysql_select_db($database_andypos_connect, $andypos_connect);
$query_Rec_billdetail = sprintf("SELECT  receiptdetail.menu_id,  menulist.menu_name,  menulist.type_id,  menutype.type_name,  receiptdetail.rcd_qty,  receiptdetail.rcd_price,  receiptdetail.rcd_cost,  (receiptdetail.rcd_price - receiptdetail.rcd_cost) AS profit, SUM(receiptdetail.rcd_qty) AS sum_rcd_qty,(receiptdetail.rcd_qty*receiptdetail.rcd_price) AS sum_rcd_price,  (receiptdetail.rcd_qty*receiptdetail.rcd_cost) AS sum_rcd_cost,  ((receiptdetail.rcd_qty*receiptdetail.rcd_price) - (receiptdetail.rcd_qty*receiptdetail.rcd_cost)) AS sum_rcd_profit FROM receiptdetail, menulist ,menutype WHERE menulist.type_id = menutype.type_id AND receiptdetail.menu_id = menulist.menu_id AND rc_id = %s GROUP BY menulist.menu_id", GetSQLValueString($colname_Rec_billdetail, "int"));
$Rec_billdetail = mysql_query($query_Rec_billdetail, $andypos_connect) or die (mysql_error());
$row_Rec_billdetail = mysql_fetch_assoc($Rec_billdetail);
$totalRows_Rec_billdetail = mysql_num_rows($Rec_billdetail);
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Andy Bill Detail</title>
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
      font-size: 16px;
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

    /* เพิ่มคลาสเพื่อเปลี่ยนสีของลิงก์ที่มีคลาส "active" */
    .navbar .nav-link.active.custom-color {
      background-color: #0d47a1;
      /* สีพื้นหลังที่เหมือนกับ navbar */
      color: #fff;
      /* เปลี่ยนสีตัวหนังสือเป็นสีขาว */
    }
  </style>

  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <script src="js/bootstrap.bundle.min.js"> </script>
  <link rel="stylesheet" type="text/css" href="css/all.min.css">
  <link rel="stylesheet" type="text/css" href="css/cards.css">

</head>

<body>


  <nav class="navbar fixed-top"
    style="background-image: linear-gradient(to right, #1976d2, #1488CC, #2B32B2); font-size: 20px;">
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
  </head>

  <body>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="80%" border="0" align="center" cellpadding="3" cellspacing="3" class="text-kanit">
      <tr>
        <td align="center">
          <h3><strong>รายละเอียดบิล</strong></h3>
            <div style="display: flex; justify-content: center;">
    <p style="border-bottom: 2px solid #FFD700; width: 50%; "></p>
</div>
        </td>
        
      </tr>
    

      <tr>
    </table>
    <br>
    <table width="80%" border="0" align="center" cellpadding="3" cellspacing="3" class="text-kanit">

      <td width="26%" rowspan="2" align="center"><h4><strong>เลขที่บิล :
          <?php echo $row_Rec_bill['rc_id']; ?>
        </strong></h4></td>
      <td width="74%"><strong>วันที่
          <?php
          $date = date('d/m/Y', strtotime($row_Rec_bill['rc_date']));
          $thai_months = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
          $split_date = explode('/', $date);
          $day = $split_date[0];
          $month = $thai_months[intval($split_date[1]) - 1];
          $year = intval($split_date[2]);
          echo "$day $month $year";
          ?> <br>เวลา
          <?php echo $row_Rec_bill['rc_time']; ?> น.
        </strong></td>

      </tr>
      <tr>
        <td><strong>วิธีการชำระเงิน
            <?php echo $row_Rec_bill['rc_pt']; ?>
          </strong></td>
      </tr>
      <tr>
        <td colspan="2" align="center">&nbsp;
          <table width="100%" border="0" cellpadding="5" cellspacing="5" class="table table-striped table-bordered text-kanit2">
            <tr>
              <td width="5%" align="center"><strong>ลำดับ</strong></td>

              <td width="27%" align="center"><strong>ชื่อเมนู</strong></td>
              <td width="18%" align="center"><strong>ประเภท</strong></td>
              <td width="17%" align="right"><strong>ราคาขายต่อหน่วย(บาท)</strong></td>
              <td width="17%" align="right"><strong>ต้นทุนต่อหน่วย(บาท)</strong></td>
              <td width="16%" align="right"><strong>กำไรต่อหน่วย(บาท)</strong></td>
             
            </tr>
            <?php $i = 1;
            do { ?>
              <tr>
                <td align="center">
                  <?php echo $i; ?>.
                </td>

                <td align="left">
                  <?php echo $row_Rec_billdetail['menu_name']; ?>
                </td>
                <td align="left"><?php echo $row_Rec_billdetail['type_name']; ?>
                </td>
                <td align="right">
                  <?php echo number_format($row_Rec_billdetail['rcd_price'], 2); ?>
                </td>
                <td align="right">
                  <?php echo number_format($row_Rec_billdetail['rcd_cost'], 2); ?>
                </td>
                <td align="right">
                  <?php echo number_format($row_Rec_billdetail['profit'], 2); ?>
                </td>
                
              </tr>

              <?php
              $i++;
              $totalqty = $totalqty + $row_Rec_billdetail['sum_rcd_qty'];
              $totalprice = $totalprice + $row_Rec_billdetail['sum_rcd_price'];
              $totalcost = $totalcost + $row_Rec_billdetail['sum_rcd_cost'];
              $totalprofit = $totalprofit + $row_Rec_billdetail['sum_rcd_profit'];
            } while ($row_Rec_billdetail = mysql_fetch_assoc($Rec_billdetail)); ?>
            
          </table>
        </td>
      </tr>

    </table>

    <div align="center"><i class="fa-regular fa-circle-left" onclick="goBack()"
        style="cursor: pointer; font-size: 30px;"></i></div>

    <script>
      function goBack() {
        window.history.back();
      }
    </script>

  </body>

</html>
<?php
mysql_free_result($Rec_bill);

mysql_free_result($Rec_billdetail);
?>