<?php
// ตั้งค่าอายุของ session เป็นเวลา () วินาที
session_set_cookie_params(0);
// ตั้งค่า session cookie ให้มีอายุเฉพาะเมื่อเปิดเว็บไซต์
ini_set('session.cookie_lifetime', 0);
session_start();

// ตรวจสอบว่ามี session login หรือไม่ ถ้าไม่มีให้ redirect ไปที่หน้า login.php
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>



 <!-- item cart -->
 <tr>
              
              <td style="width: 250px;" class="text-muted">Hot Espresso</td>
              <td>
                  <div class="input-group md-3" style="max-width: 150px;">
                  <span onclick="change_qty('up',event)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-minus text-danger"></i></span>
                  <input type="text" class="form-control text-primary" placeholder="1" value="1">
                  <span onclick="change_qty('down',event)" class="input-group-text" style="cursor: pointer;"><i class="fa fa-plus text-success"></i></span>
                  </div>
              </td>
              <td style="font-size: 16px"><b>฿50.00</b></td>
          </tr>

  <!-- end item cart  -->

<script>

        function change_qty(direction.e)
        {
            alert(direction);

        }


</script>

SELECT receipt.rc_id,  receipt.rc_date,  receipt.rc_time,  SUM(receiptdetail.rcd_qty) AS sum_rc_qty,  SUM(receiptdetail.rcd_qty*receiptdetail.rcd_price) AS sum_rc_price,  SUM(receiptdetail.rcd_qty*receiptdetail.rcd_cost) AS sum_rc_cost, SUM((receiptdetail.rcd_qty*receiptdetail.rcd_price) - (receiptdetail.rcd_qty*receiptdetail.rcd_cost)) AS sum_rc_profit FROM receipt, receiptdetail WHERE receipt.rc_id=receiptdetail.rc_id AND rc_date = %s GROUP BY receipt.rc_id ORDER BY receipt.rc_id"

