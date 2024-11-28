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


session_start();

$action = isset($_GET['a']) ? $_GET['a'] : "";
$itemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$_SESSION['formid'] = sha1('itoffside.com' . microtime());
if (isset($_SESSION['qty'])) {
  $meQty = 0;
  foreach ($_SESSION['qty'] as $meItem) {
    $meQty = $meQty + $meItem;
  }
} else {
  $meQty = 0;
}
if (isset($_SESSION['cart']) and $itemCount > 0) {
  $itemIds = "";
  foreach ($_SESSION['cart'] as $itemId) {
    $itemIds = $itemIds . $itemId . ",";
  }
  $inputItems = rtrim($itemIds, ",");

  //******************แก้ไขชื่อตารางและชื่อฟิลด์ในคำสั่ง sql ให้สอดคล้องกับฐานข้อมูลที่มี *****************

  $meSql = "SELECT * FROM menulist WHERE menu_id in ({$inputItems})";

  $meQuery = mysql_query($meSql);
  $meCount = mysql_num_rows($meQuery);
} else {
  $meCount = 0;
}
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Andy Order</title>
  <link rel="icon" type="image" href="images/andylogoCR.png">

  <style type="text/css">
    .text-kanit {
      font-family: Kanit;
      font-size: 20px;
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

    .modal-dialog.modal-dialog-centered2 {
      right: 1 !important;
      margin-right: 1 !important;
      margin-left: auto !important;
      max-width: calc(100% - 16px) !important;
      position: fixed !important;
      width: 440px !important;
      display: flex;
      align-items: center;
      min-height: calc(100% - var(--bs-modal-margin) * 2);
    }
  </style>

  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <script src="js/bootstrap.bundle.min.js"> </script>
  <link rel="stylesheet" type="text/css" href="css/all.min.css">
  <link rel="stylesheet" type="text/css" href="css/cards.css">

</head>

<body>



  <div class="container">

    <!-- Static navbar -->
    <div class="navbar navbar-default" role="navigation">

    </div>
    <h3 style="font-family: Kanit;">รายการสั่งซื้อ</h3>
    <!-- Main component for a primary marketing message or call to action -->
    <?php


    if ($meCount == 0) {
      echo "<div class=\"alert alert-warning text-kanit\">ไม่มีสินค้าอยู่ในตะกร้า</div>";
    } else {
      ?>

      <form action="updateorder.php" method="post" name="formupdate" role="form" id="formupdate"
        onsubmit="return updateSubmit();">
        <div class="form-group">

          <p>

            <!--*******************แก้ไขให้สอดคล้องกับตารางที่ใช้ **************** -->

          </p>
          <p>
          </p>
          <p>
            <label for="rc_pt" class="text-kanit">วิธีการชำระเงิน <span class="required-text"
                style="color: red;">*</span></label>
            <select name="rc_pt" id="rc_pt" required class="text-kanit" onchange="showCashInput()">
              <option value="" disabled selected>โปรดเลือกวิธีการชำระเงิน</option>
              <option value="Cash" class="text-kanit">Cash</option>
              <option value="QR Payment" class="text-kanit">QR Payment</option>
            </select>
            <!--แสดงเงินทอน realtime -->

          <div id="cashInput" style="display: none;">
            <label for="cash_received" class="text-kanit">จำนวนเงินที่รับ ฿ </label>
            <input type="text" name="cash_received" id="cash_received" class="text-kanit" style=" width: 8vw;"
              placeholder="คำนวณเงินทอน" oninput="calculateChange()">
          </div>
          <p class="text-kanit" id="change_amount"></p>

          <!-- Modal QRCODEeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee-->
          <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="QRModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered2 ">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="QRModalLabel">QR Code</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center" id="qrCodeContainer">
                  <!-- ตำแหน่งที่แสดง QR Code -->
                  <img src="images/qr.jpg" width="400" height="520" alt="QR Code" class="rounded">
                </div>
              </div>
            </div>
          </div>
          <label for="rc_pt"></label>
          </p>
        </div>
        <table class="table table-striped table-bordered text-kanit">
          <thead>
            <tr>
              <!--*******************แก้ไขให้สอดคล้องกับตารางที่ใช้ **************** -->
               <th style="text-align: center;">ลำดับ</th>
              <th style="text-align: center;">ชื่อสินค้า</th>
              <th style="text-align: center;">จำนวน</th>
              <th style="text-align: right;">ราคาต่อหน่วย</th>
              <th style="text-align: right;">จำนวนเงิน</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i=1;
            $total_price = 0;
            $num = 0;
            while ($meResult = mysql_fetch_assoc($meQuery)) {

              //***************************แก้ไขชื่อฟิลด์ใน record set ให้ตรงกับตารางที่ใช้ *********************************
          
              $key = array_search($meResult['menu_id'], $_SESSION['cart']);
              $total_price = $total_price + ($meResult['menu_price'] * $_SESSION['qty'][$key]);
              ?>
              <tr>
                 <td style="text-align: center;">
                  <?php echo $i; ?>.
                </td> 
                <td style="text-align: left;">
                  <?php echo $meResult['menu_name']; ?>
                </td>
                <td style="text-align: center;"> 
                  <?php echo $_SESSION['qty'][$key]; ?>
                  <input type="hidden" name="qty[]" value="<?php echo $_SESSION['qty'][$key]; ?>" />
                  <input type="hidden" name="menu_id[]" value="<?php echo $meResult['menu_id']; ?>" />
                  <input type="hidden" name="menu_price[]" value="<?php echo $meResult['menu_price']; ?>" />
                  <input type="hidden" name="menu_cost[]" value="<?php echo $meResult['menu_cost']; ?>" />
                </td>
                <td style="text-align: right;">
                  <?php echo '฿' . number_format($meResult['menu_price'], 2); ?>
                </td>
                <td style="text-align: right;">
                  <?php echo '฿' . number_format(($meResult['menu_price'] * $_SESSION['qty'][$key]), 2); ?>
                </td>


                <?php //***************************************************************************************  
                    ?>
              </tr>
              <?php
              $num++;
              $i++;
            }
            ?>
            <tr>
              <td colspan="8" style="text-align: right;">
                <h4 style="font-family: Kanit;">จำนวนเงินรวมทั้งหมด
                  <?php echo number_format($total_price, 2); ?> บาท
                </h4>


                <?php // โค้ดการคำนวณเงินทอน
                  $totalPrice = $total_price; // ใช้ราคารวมจากข้อมูลที่ดึงมาแล้ว
                  $receivedAmount = "cash_received"/* รับค่าจากช่อง input หรือตัวแปรที่บอกจำนวนเงินที่รับมา */ ; // ค่าเงินที่ลูกค้าชำระ
                  ?>



              </td>


            </tr>
            <tr>
              <td colspan="8" style="text-align: right; font-family: Kanit;">
                <input type="hidden" name="formid" value="<?php echo $_SESSION['formid']; ?>" />
                <a href="pos.php" type="button" class="btn btn-danger btn-lg"> ย้อนกลับ</a>
                <button type="submit" class="btn btn-primary btn-lg">บันทึกการสั่งซื้อสินค้า</button>
              </td>
            </tr>


          </tbody>
        </table>
      </form>
      <?php
    }
    ?>


  </div> <!-- /container -->
  <script>
    function calculateChange() {
      // รับค่าราคารวมจากตัวแปร PHP
      var totalPrice = <?php echo $total_price; ?>;

      // รับค่าจำนวนเงินที่รับมาจาก input field
      var receivedAmount = document.getElementById("cash_received").value.trim(); // ใช้ trim() เพื่อลบช่องว่างข้างหน้าและข้างหลัง

      // ตรวจสอบว่า receivedAmount เป็นตัวเลขหรือไม่ และมีค่ามากกว่า 0 หรือไม่
      if (!isNaN(receivedAmount) && receivedAmount > 0) {
        // ตรวจสอบว่าจำนวนเงินที่รับมามากกว่าหรือเท่ากับราคารวมหรือไม่
        if (receivedAmount >= totalPrice) {
          // คำนวณเงินทอน
          var change = receivedAmount - totalPrice;
          // แสดงผลเงินทอน
          document.getElementById("change_amount").innerText = "เงินทอน: " + change.toFixed(2) + " บาท";
        } else {
          // แจ้งเตือนว่าจำนวนเงินไม่เพียงพอ
          document.getElementById("change_amount").innerHTML = "<span style='color: red;'>จำนวนเงินไม่เพียงพอ</span>";
        }
      } else if (receivedAmount === "") {
        // หากไม่มีการกรอกเงินที่รับหรือเป็นค่าที่ไม่ถูกต้อง ให้แสดงข้อความว่าไม่มีเงินทอน
        document.getElementById("change_amount").innerText = "";
      } else {
        // หากไม่มีการกรอกเงินที่รับหรือเป็นค่าที่ไม่ถูกต้อง ให้แสดงข้อความว่าไม่มีเงินทอน
        document.getElementById("change_amount").innerHTML = "<span style='color: red;'>โปรดกรอกจำนวนเงินเป็นตัวเลข</span>";

      }
    }

  </script>
  <script>
    function showCashInput() {
      var paymentMethod = document.getElementById("rc_pt").value;
      var cashInput = document.getElementById("cashInput");

      if (paymentMethod === "Cash") {
        cashInput.style.display = "block";
      } else {
        cashInput.style.display = "none";
      }
    }
  </script>

  <!-- Script สร้าง QR Code และแสดงใน Modal เมื่อ Modal ถูกเปิด -->
  <script>
    // เมื่อคลิกเลือกวิธีการชำระเงิน
    $('#rc_pt').change(function () {
      var selectedPaymentMethod = $(this).val();
      var cashInput = document.getElementById("cashInput");

      // ถ้าเลือกวิธีการชำระเงินเป็น "QR Payment" ให้ซ่อนข้อความเงินทอน
      if (selectedPaymentMethod === "QR Payment") {
        document.getElementById("change_amount").style.display = "none";
      } else {
        // ถ้าไม่ใช่ "QR Payment" ให้แสดงข้อความเงินทอนอีกครั้ง
        document.getElementById("change_amount").style.display = "block";

        // ถ้าเป็น "Cash" ให้แสดง input จำนวนเงินที่รับมา
        if (selectedPaymentMethod === "Cash") {
          cashInput.style.display = "block";
        } else {
          // ถ้าไม่ใช่ "Cash" ให้ซ่อน input จำนวนเงินที่รับมา
          cashInput.style.display = "none";
        }
      }

    });

    // เมื่อคลิกเลือกวิธีการชำระเงิน
    $('#rc_pt').change(function () {
      var selectedPaymentMethod = $(this).val();
      if (selectedPaymentMethod === "QR Payment") {
        $('#qrModal').modal('show'); // เปิด Modal เมื่อเลือก QR Payment
      }
    });

   function updateSubmit() {
    var total_price = <?php echo $total_price; ?>; // ราคารวมทั้งหมด
      var receivedAmount = document.getElementById("cash_received").value.trim(); // จำนวนเงินที่รับมาแล้ว
      var paymentMethod = document.getElementById("rc_pt").value; // วิธีการชำระเงินที่เลือก

      // หากไม่ได้กรอกข้อมูล ให้ทำการบันทึกได้โดยไม่มีการแจ้งเตือน
      if (receivedAmount.trim() === "") {
        return true;
      }

      // หากกรอกข้อมูล ให้ตรวจสอบว่าเป็นตัวเลขหรือไม่
      if (isNaN(receivedAmount)) {
        alert("โปรดกรอกจำนวนเงินที่รับเป็นตัวเลข");
        return false; // ไม่อนุญาตให้ทำการ submit ฟอร์ม
      }

      // หากเป็น "Cash" ให้ตรวจสอบว่าเงินที่รับมาเพียงพอหรือไม่
      if (paymentMethod === "Cash" && receivedAmount < total_price) {
        alert("จำนวนเงินที่รับมาไม่เพียงพอ");
        return false; // ไม่อนุญาตให้ทำการ submit ฟอร์ม
      }

      // หากเงินที่รับมาเพียงพอหรือไม่ใช่การชำระเงินด้วยเงินสด ให้ทำการ submit ฟอร์ม
      return true;
    }



  </script>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="bootstrap/js/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
</body>

</html>

<?php

?>
<?php

mysql_close();
