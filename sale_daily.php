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

$colname_Rec_rc = "-1";
if (isset ($_POST['textday'])) {
  $colname_Rec_rc = $_POST['textday'];
}
mysql_select_db($database_andypos_connect, $andypos_connect);
$query_Rec_rc = sprintf("SELECT rc_id FROM receipt WHERE rc_date = %s ORDER BY rc_id ASC", GetSQLValueString($colname_Rec_rc, "date"));
$Rec_rc = mysql_query($query_Rec_rc, $andypos_connect) or die (mysql_error());
$row_Rec_rc = mysql_fetch_assoc($Rec_rc);
$totalRows_Rec_rc = mysql_num_rows($Rec_rc);

$colname_Rec_rcd = "-1";
if (isset ($_POST['textday'])) {
  $colname_Rec_rcd = $_POST['textday'];
}
mysql_select_db($database_andypos_connect, $andypos_connect);
$query_Rec_rcd = sprintf("SELECT receipt.rc_id,  receipt.rc_date,  receipt.rc_time,  SUM(receiptdetail.rcd_qty) AS sum_rc_qty,  SUM(receiptdetail.rcd_qty*receiptdetail.rcd_price) AS sum_rc_price,  SUM(receiptdetail.rcd_qty*receiptdetail.rcd_cost) AS sum_rc_cost, SUM((receiptdetail.rcd_qty*receiptdetail.rcd_price) - (receiptdetail.rcd_qty*receiptdetail.rcd_cost)) AS sum_rc_profit FROM receipt, receiptdetail WHERE receipt.rc_id=receiptdetail.rc_id AND rc_date = %s GROUP BY receipt.rc_id ORDER BY receipt.rc_id", GetSQLValueString($colname_Rec_rcd, "date"));
$Rec_rcd = mysql_query($query_Rec_rcd, $andypos_connect) or die (mysql_error());
$row_Rec_rcd = mysql_fetch_assoc($Rec_rcd);
$totalRows_Rec_rcd = mysql_num_rows($Rec_rcd);
?>

<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<script>
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
</script>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Andy Sale Daily</title>
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

    /* สไตล์ปุ่มค้นหา */
    .search-button {
      height: 40px;
      width: 80px;
      background-color: #1e90ff;
      /* สีฟ้าน้ำเงิน */
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
      /* เพิ่มเอฟเฟ็กต์การเปลี่ยนสีเมื่อโฮเวอร์ */
    }

    /* สไตล์การเปลี่ยนสีเมื่อโฮเวอร์ */
    .search-button:hover {
      background-color: #306EFF;
      /* สีน้ำเงินอ่อนเมื่อโฮเวอร์ */
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
  <p>&nbsp;</p>
  <p>&nbsp;</p>

  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="3" class="text-kanit">
    <tr>
      <td align="center">
        <h3><strong>รายงานยอดขายประจำวัน</strong></h3>
      </td>
    </tr>
    <tr>
      <td align="center" style="border-bottom: 2px solid #FFD700;">
        <form id="form1" name="form1" method="post" action="">
          <strong>
            วันที่
            <input type="date" name="textday" id="textday" value="<?php echo date('Y-m-d'); ?>" />
            <input type="submit" name="button" id="button" class="search-button"  value="  ค้นหา  " />
          </strong>
          <p></p>
        </form>
      </td>
    </tr>

  </table>
  <p></p>
  <table width="80%" border="0" align="center" cellpadding="3" cellspacing="3" class="text-kanit">

    <?php if ($Rec_rcd && mysql_num_rows($Rec_rcd) > 0) { ?>
      <tr>
        <td align="center">วันที่
          <?php
          $date = date('d/m/Y', strtotime($row_Rec_rcd['rc_date'])); // วันที่ในรูปแบบ dd/mm/YYYY
          $thai_months = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
          $split_date = explode('/', $date);
          $day = $split_date[0];
          $month = $thai_months[intval($split_date[1]) - 1]; // แปลงเป็นชื่อเดือนไทย
          $year = $split_date[2]; // ปี ค.ศ.
          echo "$day เดือน $month ปี ค.ศ. $year";
          ?>
        </td>
      </tr>
      <tr>
        <td align="center">
          <form id="form2" name="form2" method="post" action="bill_search_result.php">

            <?php
            do {
              ?>

              <?php
            } while ($row_Rec_rc = mysql_fetch_assoc($Rec_rc));
            $rows = mysql_num_rows($Rec_rc);
            if ($rows > 0) {
              mysql_data_seek($Rec_rc, 0);
              $row_Rec_rc = mysql_fetch_assoc($Rec_rc);
            }
            ?>

          </form>
          <div style="height: 59vh; overflow-y: scroll;">
            <table width="80%" border="0" cellpadding="5" cellspacing="5"
              class="table table-striped table-bordered text-kanit">
              <tr>
                <td align="center"><strong>ลำดับ</strong></td>
                <td align="center"><strong>รหัสบิล</strong></td>
                <td align="center"><strong>เวลา</strong></td>
                <td align="center"><strong>จำนวนรวม</strong></td>
                <td align="right"><strong>ราคารวม(บาท)</strong></td>
                <td align="right"><strong>ต้นทุนรวม(บาท)</strong></td>
                <td align="right"><strong>กำไรรวม(บาท)</strong></td>
                <td align="center"><strong>รายละเอียด</strong></td>
              </tr>





              <?php $i = 1;
              do { ?>
                <tr>
                  <td align="center">
                    <?php echo $i; ?>.
                  </td>
                  <td align="center">
                    <?php echo $row_Rec_rcd['rc_id']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row_Rec_rcd['rc_time']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row_Rec_rcd['sum_rc_qty']; ?>
                  </td>
                  <td align="right">
                    <?php echo number_format($row_Rec_rcd['sum_rc_price'], 2); ?>
                  </td>
                  <td align="right">
                    <?php echo number_format($row_Rec_rcd['sum_rc_cost'], 2); ?>
                  </td>
                  <td align="right">
                    <?php echo number_format($row_Rec_rcd['sum_rc_profit'], 2); ?>
                  </td>
                  <td align="center">
                    <form id="form2" name="form2" method="post" action="bill_search_result.php">
                      <!-- เพิ่มฟิลด์ input hidden เพื่อเก็บค่า rc_id ของแต่ละ row -->
                      <input type="hidden" name="textsearch" value="<?php echo $row_Rec_rcd['rc_id']; ?>">
                      <!-- ปุ่ม submit สำหรับส่งค่าไปยัง bill_search_result.php -->
                      <button type="submit"><i class="fas fa-info-circle"></i></button>
                    </form>
                  </td>

                </tr>



                <?php
                $i++;
                $totalqty = $totalqty + $row_Rec_rcd['sum_rc_qty'];
                $totalprice = $totalprice + $row_Rec_rcd['sum_rc_price'];
                $totalcost = $totalcost + $row_Rec_rcd['sum_rc_cost'];
                $totalprofit = $totalprofit + $row_Rec_rcd['sum_rc_profit'];

              } while ($row_Rec_rcd = mysql_fetch_assoc($Rec_rcd)); ?>
              <tr>
                <td colspan="3" align="right"><strong>รวมสุทธิ</strong></td>
                <td align="center"><strong>
                    <?php echo $totalqty; ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php echo number_format($totalprice, 2); ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php echo number_format($totalcost, 2); ?>
                  </strong></td>
                <td align="right"><strong>
                    <?php echo number_format($totalprofit, 2); ?>
                  </strong></td>
                <td align="right">&nbsp;</td>
              </tr>
            </table>



        </td>
      </tr>
    <?php } elseif ($_POST['textday'] === null) { // กรุณากรอกข้อมูลในฟอร์ม               ?>

      <tr>
        <td align="center">โปรดเลือกวันที่ในฟอร์ม</td>
      </tr>
    <?php } else { // ไม่มีข้อมูล       ?>

      <tr>
        <td align="center">ไม่พบข้อมูลของ วันที่
          <?php echo date('d/m/Y', strtotime($_POST['textday'])); ?>
        </td>
      </tr>

    <?php } ?>
    <tr>
      <td align="center">&nbsp;</td>
    </tr>
  </table>
  </div>
</body>

</html>
<?php
mysql_free_result($Rec_rc);

mysql_free_result($Rec_rcd);
?>