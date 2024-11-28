<?php
// ตั้งค่า session cookie ให้มีอายุเฉพาะเมื่อเปิดเว็บไซต์
ini_set('session.cookie_lifetime', 0);
session_start();

// ตรวจสอบว่ามี session login หรือไม่ ถ้าไม่มีให้ redirect ไปที่หน้า login.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}
// ปรับเปลี่ยน header เพื่อไม่ให้ cache หน้าเว็บ
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>
<?php date_default_timezone_set("Asia/Bangkok"); ?>
<?php require_once('Connections/andypos_connect.php'); ?>
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

//หาจำนวนวันในแต่ละเดือน โดยใช้ฟังก์ชัน cal_days_in_month()
$numday = @cal_days_in_month(CAL_GREGORIAN, $_POST['textmonth'], $_POST['textyear']);
//กำหนดวันเริ่มต้น
$start = $_POST['textyear'] . "-" . $_POST['textmonth'] . "-01";
//กำหนดวันสิ้นสุด
$end = $_POST['textyear'] . "-" . $_POST['textmonth'] . "-" . $numday;

if ($_POST['textmonth'] === 'all') {
  // เปลี่ยน query SQL เพื่อให้ค้นหายอดขายของเมนูทุกอันโดยไม่จำกัดเดือนหรือปี
  $query_Rec_report = "SELECT  	receiptdetail.menu_id,     menulist.menu_name,     menutype.type_id,     menutype.type_name,     SUM(receiptdetail.rcd_qty) AS total_sold_quantity,     SUM(receiptdetail.rcd_price * receiptdetail.rcd_qty) AS total_sales_amount,     SUM(receiptdetail.rcd_cost * receiptdetail.rcd_qty) AS total_cost,     SUM((receiptdetail.rcd_price - receiptdetail.rcd_cost) * receiptdetail.rcd_qty) AS total_profit,     MONTHNAME(receipt.rc_date) AS Month FROM receiptdetail, menulist, menutype, receipt WHERE menulist.type_id = menutype.type_id AND receiptdetail.menu_id = menulist.menu_id AND receiptdetail.rc_id =receipt.rc_id GROUP BY receiptdetail.menu_id ORDER BY total_sold_quantity DESC,total_sales_amount DESC, total_profit DESC LIMIT 10";
} else {
  $query_Rec_report = "SELECT  	receiptdetail.menu_id,     menulist.menu_name,     menutype.type_id,     menutype.type_name,     SUM(receiptdetail.rcd_qty) AS total_sold_quantity,     SUM(receiptdetail.rcd_price * receiptdetail.rcd_qty) AS total_sales_amount,     SUM(receiptdetail.rcd_cost * receiptdetail.rcd_qty) AS total_cost,     SUM((receiptdetail.rcd_price - receiptdetail.rcd_cost) * receiptdetail.rcd_qty) AS total_profit,     MONTHNAME(receipt.rc_date) AS Month FROM receiptdetail, menulist, menutype, receipt WHERE menulist.type_id = menutype.type_id AND receiptdetail.menu_id = menulist.menu_id AND receiptdetail.rc_id =receipt.rc_id AND receipt.rc_date BETWEEN '$start' AND '$end' GROUP BY receiptdetail.menu_id ORDER BY  	total_sold_quantity DESC LIMIT 10";
}

mysql_select_db($database_andypos_connect, $andypos_connect);

$Rec_report = mysql_query($query_Rec_report, $andypos_connect) or die(mysql_error());
$row_Rec_report = mysql_fetch_assoc($Rec_report);
$totalRows_Rec_report = mysql_num_rows($Rec_report);
date_default_timezone_set("Asia/Bangkok"); ?>

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
</head>

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
  <table width="90%" border="0" align="center" cellpadding="3" cellspacing="3" class="text-kanit">
    <tr>
      <td height="48" align="center">
        <table width="90%" border="0" align="center" cellpadding="3" cellspacing="3" class="text-kanit">
          <tr>
            <td align="center">
              <h3><strong>รายงานเมนูขายดี</strong></h3>
              
            </td>
          </tr>

          <tr>
            <td align="center" style="border-bottom: 2px solid #FFD700;">
              <form id="form1" name="form1" method="post" action="">

                <p><strong>เดือน
                    <select name="textmonth" id="textmonth">
                      <option value="all" style="color: red;" <?php if ($_POST['textmonth'] === 'all')
                        echo 'selected style="color: red;"'; ?>>ทั้งหมด</option>
                      <option value="01" <?php if (date('m') == '01')
                        echo 'selected'; ?>>มกราคม</option>
                      <option value="02" <?php if (date('m') == '02')
                        echo 'selected'; ?>>กุมภาพันธ์</option>
                      <option value="03" <?php if (date('m') == '03')
                        echo 'selected'; ?>>มีนาคม</option>
                      <option value="04" <?php if (date('m') == '04')
                        echo 'selected'; ?>>เมษายน</option>
                      <option value="05" <?php if (date('m') == '05')
                        echo 'selected'; ?>>พฤษภาคม</option>
                      <option value="06" <?php if (date('m') == '06')
                        echo 'selected'; ?>>มิถุนายน</option>
                      <option value="07" <?php if (date('m') == '07')
                        echo 'selected'; ?>>กรกฎาคม</option>
                      <option value="08" <?php if (date('m') == '08')
                        echo 'selected'; ?>>สิงหาคม</option>
                      <option value="09" <?php if (date('m') == '09')
                        echo 'selected'; ?>>กันยายน</option>
                      <option value="10" <?php if (date('m') == '10')
                        echo 'selected'; ?>>ตุลาคม</option>
                      <option value="11" <?php if (date('m') == '11')
                        echo 'selected'; ?>>พฤศจิกายน</option>
                      <option value="12" <?php if (date('m') == '12')
                        echo 'selected'; ?>>ธันวาคม</option>
                    </select>&nbsp;
                    ปี ค.ศ.
                    <select name="textyear" id="textyear">
                      <?php
                      $start_year = 2023; // เริ่มต้นจากปี 2023
                      $current_year = date("Y");
                      $end_year = $current_year + 0; // สิ้นสุดที่ปีปัจจุบัน
                      for ($year = $start_year; $year <= $end_year; $year++) {
                        echo "<option value=\"$year\"";
                        if ($year == $current_year) {
                          echo " selected";
                        }
                        echo ">$year</option>";
                      }
                      ?>
                    </select>&nbsp;
                    <input type="submit" name="button" id="button" class="search-button" value="  ค้นหา  " />
                  </strong></p>
              </form>
            </td>
          </tr>
          <?php
          $mm = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
          $i = $_POST['textmonth'] - 1;

          ?>
        </table>
        <p></p>
        <?php if ($totalRows_Rec_report > 0) { // ตรวจสอบว่ามีข้อมูลหรือไม่         ?>
          <?php
          if ($_POST['textmonth'] === 'all') {
            echo "<p><span style='color: red;'>ยอดรวมทั้งหมด</span></p>";
          } else {
            echo "<p>เดือน " . $mm[$i] . " ปี ค.ศ. " . $_POST['textyear'] . "</p>";
          }
          ?>

          <div>
            <table border="0" cellpadding="5" cellspacing="5" class="table table-striped table-bordered text-kanit">
              <tr>
                <td align="center"><strong>อันดับ</strong></td>
               
                <td align="center"><strong>ชื่อเมนู</strong></td>
                <td align="center"><strong>ประเภท</strong></td>
                <td align="center"><strong>จำนวนรวม</strong></td>
                <td align="right"><strong>ยอดขายรวม(บาท)</strong></td>
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
                  
                  <td align="left">
                    <?php echo $row_Rec_report['menu_name']; ?>
                  </td>
                  <td align="left"><?php echo $row_Rec_report['type_name']; ?>
                  </td>
                  <td align="center">
                    <?php echo $row_Rec_report['total_sold_quantity']; ?>
                  </td>
                  <td align="right">
                    <?php echo number_format($row_Rec_report['total_sales_amount'], 2); ?>
                  </td>
                  <td align="right">
                    <?php echo number_format($row_Rec_report['total_cost'], 2); ?>
                  </td>
                  <td align="right">
                    <?php echo number_format($row_Rec_report['total_profit'], 2); ?>
                  </td>
                  <td align="center">

                    <form id="form2" name="form2" method="post" action="sale_report_menu_details.php">
                      <!-- เพิ่มฟิลด์ input hidden เพื่อเก็บค่ารหัสเมนูและเดือน/ปี โดยใช้ค่าจาก row ปัจจุบัน -->
                      <input type="hidden" name="menu_id" id="menu_id_hidden"
                        value="<?php echo $row_Rec_report['menu_id']; ?>">
                      <input type="hidden" name="textmonth" id="textmonth_hidden"
                        value="<?php echo $_POST['textmonth']; ?>">
                      <input type="hidden" name="textyear" id="textyear_hidden" value="<?php echo $_POST['textyear']; ?>">
                      <!-- เพิ่ม button และ script เพื่อทำการ submit ฟอร์มโดยอัตโนมัติเมื่อคลิก -->
                      <button type="submit" onclick="submitForm('<?php echo $row_Rec_report['menu_id']; ?>')"><i
                          class="fas fa-info-circle"></i></button>
                    </form>

                  </td>

                  <script>
                    function submitForm(menuId) {
                      var selectedMenuId = menuId; // รับค่า menu_id จากพารามิเตอร์
                      var selectedMonth = "<?php echo $_POST['textmonth']; ?>"; // ดึงค่าเดือนจาก $_POST['textmonth']
                      var selectedYear = document.getElementById("textyear").value; // ดึงค่าปีจาก dropdown
                      document.getElementById("menu_id_hidden").value = selectedMenuId; // กำหนดค่า menu_id ลงใน input hidden
                      document.getElementById("textmonth_hidden").value = selectedMonth; // กำหนดค่าเดือนลงใน input hidden
                      document.getElementById("textyear_hidden").value = selectedYear; // กำหนดค่าปีลงใน input hidden
                      document.getElementById("form1").submit(); // ทำการ submit ฟอร์ม
                    }
                  </script>



                </tr>
                <?php $i++;
              } while ($row_Rec_report = mysql_fetch_assoc($Rec_report)); ?>
            </table>
        </td>
      </tr>

    <?php } elseif ($_POST['textmonth'] === null || $_POST['textyear'] === null) { // กรุณากรอกข้อมูลในฟอร์ม                        ?>

      <tr>
        <td align="center">โปรดเลือกเดือน/ปีในฟอร์มเพื่อค้นหา 10 อันดับ เมนูขายดีประจำเดือน</td>
      </tr>
    <?php } else { // ไม่มีข้อมูล                        ?>

      <tr>
        <td align="center">ไม่พบข้อมูลของ เดือน
          <?php echo $mm[$i] . " ปี ค.ศ. " . $_POST['textyear']; ?>
        </td>
      </tr>
    <?php } ?>
  </table>

  </td>
  </tr>

  </table>


</body>

</html>
<?php
mysql_free_result($Rec_report);
?>