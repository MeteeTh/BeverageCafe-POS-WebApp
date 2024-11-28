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

if (isset ($_GET['search'])) {
  $search_term = $_GET['search'];
  $query_Rec_menulist = "SELECT menulist.*, menutype.type_color FROM menulist, menutype WHERE menutype.type_id = menulist.type_id AND menu_name LIKE '%" . $search_term . "%' ORDER BY type_name ASC";
} else {
  $query_Rec_menulist = "SELECT menulist.*, menutype.type_color FROM menulist, menutype WHERE menutype.type_id = menulist.type_id ORDER BY type_name ASC";
}


mysql_select_db($database_andypos_connect, $andypos_connect);
$Rec_menulist = mysql_query($query_Rec_menulist, $andypos_connect) or die (mysql_error());

$row_Rec_menulist = mysql_fetch_assoc($Rec_menulist);
$totalRows_Rec_menulist = mysql_num_rows($Rec_menulist);



$action = isset ($_GET['a']) ? $_GET['a'] : "";
$itemCount = isset ($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$_SESSION['formid'] = sha1('itoffside.com' . microtime());
if (isset ($_SESSION['qty'])) {
  $meQty = 0;
  foreach ($_SESSION['qty'] as $meItem) {
    $meQty = $meQty + $meItem;
  }
} else {
  $meQty = 0;
}
if (isset ($_SESSION['cart']) and $itemCount > 0) {
  $itemIds = "";
  foreach ($_SESSION['cart'] as $itemId) {
    $itemIds = $itemIds . $itemId . ",";
  }
  $inputItems = rtrim($itemIds, ",");

  //******************แก้ไขชื่อตารางและชื่อฟิลด์ในคำสั่ง sql ให้สอดคล้องกับฐานข้อมูลที่มี *****************

  $meSql = "SELECT * FROM menulist WHERE menu_id IN ({$inputItems})";


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
  <title>Andy POS</title>
  <link rel="icon" type="image" href="images/andylogoCR.png">

  <style type="text/css">
    body {
      font-family: Kanit;
      background: linear-gradient(to bottom right, #FFF, #9AFEFF, #FFF, #9AFEFF, #FFF);
      padding-top: 58px;
    }

    .text-kanit {
      font-family: Kanit;
      font-size: 20px;

    }

    .navbar .nav-link {
      color: #fff;
      /* ตัวหนังสือสีขาว */
      font-weight: bold;
      /* ตั้งค่าตัวหนังสือเป็นตัวหนา */
      font-family: Kanit;
      /* ตั้งค่าแบบอักษรเป็น Kanit */
      font-size: 18px;
      letter-spacing: 1px;
      /* เพิ่มระยะห่างระหว่างตัวอักษร */
    }

    /* เปลี่ยนสีของตัวหนังสือเมื่อ hover ที่แถบเมนู */
    .navbar .nav-link:hover {
      color: blue;
      /* เปลี่ยนสีตัวหนังสือเป็นสีเขียวเด่น */
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
      overflow: hidden;
      /* ซ่อนข้อความ "ค้นหา" เมื่อไม่โชว์ */
      position: relative;
      /* ตำแหน่งสำหรับการโชว์ข้อความ "ค้นหา" */
      width: 40px;
      /* กำหนดความกว้างเริ่มต้น */
      transition: width 0.5s ease;
      /* เพิ่มเอฟเฟกต์ transition */

    }



    .input-group-text2::before {
      content: "Search";

      position: absolute;
      margin-left: 45px;

      font-size: 16px;
      font-family: kanit;
      font-weight: bold;
      color: white;
      opacity: 0;
      /* เริ่มต้นซ่อนข้อความ "ค้นหา" */
      transition: opacity 0.5s ease;
      /* เพิ่มเอฟเฟกต์ transition */

    }

    .input-group-text2:hover {
      width: 150px;
      /* ปรับความกว้างเมื่อโฮเวอร์ */
      background-color: #1e90ff;
      color: white;

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

    .input-group-text2:hover::before {
      opacity: 1;
      /* โชว์ข้อความ "ค้นหา" เมื่อโฮเวอร์ */

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

    .cart-bg {
      background-image: url('images/bg3.jpg');
      background-size: cover;
      /* ปรับขนาดภาพให้เต็มหน้าจอ */
      background-position: center;
      /* จัดตำแหน่งภาพกลางหน้าจอ */
      width: auto;
      /* ความกว้างของพื้นที่ */
      height: auto;
      /* ความสูงเท่ากับความสูงของหน้าจอ */
    }

    @media screen and (max-width: 2560px) {
      .pos-vh {
        flex-wrap: wrap;
        height: 79vh;
        overflow-y: scroll;
        align-content: flex-start;
      }

      .cart-vh {
        height: 58vh;
        overflow-y: scroll;
      }
    }

    @media screen and (min-width: 1920px) {
      .pos-vh {
        flex-wrap: wrap;
        height: 82vh;
        overflow-y: scroll;
      }

      .cart-vh {
        height: 64vh;
        overflow-y: scroll;
      }
    }

    .cards {
      transition: all 0.2s ease;
      cursor: pointer;

    }

    .cards:hover {
      box-shadow: 5px 6px 6px 2px gray;
      transform: scale(1.1);
    }
  </style>

  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <script src="js/bootstrap.bundle.min.js"> </script>
  <link rel="stylesheet" type="text/css" href="css/all.min.css">




</head>

<body>
  <nav class="navbar fixed-top"
    style="background-image: linear-gradient(to right, #1976d2, #1488CC, #2B32B2); font-size: 20px;">
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
      </style>
    </div>


  </nav>

  <!-- -------------หน้าการขาย------------------ -->

  <div class="d-flex" style="background-image: url('images/bg5.jpg');">
    <div class="shadow-sm col-7 p-4">

      <div class="input-group mb-3">
        <h3 style="font-family: kanit"><i class="fas fa-coffee " style="font-size: 24px;"></i> Items </h3>
        <input id="search" type="text" class="ms-4 form-control text-kanit"
          style="border-radius: var(--bs-border-radius);" placeholder="ป้อนชื่อเมนู" aria-label="Search Menu Name"
          aria-describedby="basic-addon1" autofocus>
        <span class="input-group-text2 " style="border-radius: 0px 5px 5px 0px;" id="basic-addon1"
          onclick="searchMenu()">
          <i class="fa-solid fa-magnifying-glass"></i>
        </span>

      </div>
      <div id="searchResults"></div>

      <script>
        // เพิ่มการตรวจสอบการกดปุ่ม Enter
        document.getElementById('search').addEventListener('keypress', function (event) {
          if (event.key === 'Enter') {
            searchMenu();
          }
        });

        function searchMenu() {
          var searchValue = document.getElementById('search').value;
          window.location.href = 'pos.php?search=' + searchValue;
        }
      </script>

      <!-- แสดงข้อมูลเป็น Card ใช้ตารางจัดเรียง -->
      <!-- card ================================================================================================ -->
      <div class="pos-vh js-products d-flex ">

        <table class="table">
          <tbody>

            <?php

            if ($totalRows_Rec_menulist > 0) {
              do {
                ?>
                <tr>
                  <div onclick="window.location.href = 'updatecart.php?itemId=<?php echo $row_Rec_menulist['menu_id']; ?>';"
                    class="card cards m-3 text-kanit"
                    style="min-width: 200px; max-width: 200px; min-height: 80px; max-height: 100px; background-color: <?php echo $row_Rec_menulist['type_color']; ?>; border: 1px solid #000; padding: 10px;">
                    <div class="p-2"
                      style="font-size: 15px; color: #FFF; text-shadow: 0px 0px 5px #000, 0px 0px 5px #000, 0px 0px 5px #000, 0px 0px 5px #000, 0px 0px 5px #000;">
                      <div class="text-kanit" style="font-size: 15px; color: #FFF">
                        <?php echo $row_Rec_menulist['menu_name']; ?>
                      </div>
                      <div class=""><b>฿
                          <?php echo $row_Rec_menulist['menu_price']; ?>
                        </b></div>
                    </div>
                  </div>


                </tr>
              <?php } while ($row_Rec_menulist = mysql_fetch_assoc($Rec_menulist));
            } else {
              // ถ้าไม่มีข้อมูลที่ตรงกับคำค้นหา ให้แสดงข้อความแจ้งเตือน
              echo "<tr><div class=\"alert alert-warning text-kanit\">ไม่พบสินค้าที่ตรงกับคำค้นหา</div></tr>";
            }
            ?>



          </tbody>
        </table>
        <!-- end card ==================================== -->


      </div>
    </div>

    <!-- ===============================CartCartCartCartCartCartCartCartCartCartCartCartCartCartCart=================================== -->

    <div class="cart-bg col-5 p-4 pt-3">

      <!--แจ้งเตือนการสั่งซื้อ---------------------------->
      <?php
      if ($action == 'exists') {
        echo "";//"<div class=\"alert alert-warning alert-sm\">เพิ่มจำนวนสินค้าแล้ว</div>";
      }
      if ($action == 'add') {
        echo "";//"<div class=\"alert alert-success alert-sm\">เพิ่มสินค้าลงในตะกร้าเรียบร้อยแล้ว</div>";
      }
      if ($action == 'order') {
        echo "";//"<div class=\"alert alert-success alert-sm\">สั่งซื้อสินค้าเรียบร้อยแล้ว</div>";
      }
      if ($action == 'orderfail') {
        echo "";//"<div class=\"alert alert-warning alert-sm\">สั่งซื้อสินค้าไม่สำเร็จ มีข้อผิดพลาดเกิดขึ้นกรุณาลองใหม่อีกครั้ง</div>";
      }
      ?>

      <div>
        <center>
          <h3 style="font-family: kanit"> <i class="fas fa-shopping-cart"
              style="font-size: 24px; margin-top: 10px; "></i> Cart <div
              class="badge bg-primary rounded-circle text-kanit"><span>
                <?php echo $meQty; ?>
              </span></a></div>
            <h3>
        </center>
      </div>

      <div class="table-responsive cart-vh">
        <table class="table table-striped table-hover text-kanit ">



          <tbody class="js-items">
            <!-- Main component for a primary marketing message or call to action -->
            <?php
            if ($action == 'removed') {
              echo "";//"<div class=\"alert alert-warning\">ลบสินค้าเรียบร้อยแล้ว</div>";
            }

            if ($meCount == 0) {
              echo "";//"<div class=\"alert alert-warning\">ไม่มีสินค้าอยู่ในตะกร้า</div>";
            } else {

              ?>
              <form action="updatecart.php" method="post" name="fromupdate" id="cartForm">
                <table class="table table-striped table-bordered text-kanit">
                  <thead>

                    <tr style="text-align: center; ">
                      <th center style="background-color: #00BFFF;">รายการ</th>
                      <th style="background-color: #00BFFF;">จำนวน</th>
                      <th colspan="2" style="background-color: #00BFFF;">ราคา</th>

                      <!--*******************แก้ไขให้สอดคล้องกับตารางที่ใช้ **************** -->
                      <!--**************************************************** -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $total_price = 0;
                    $num = 0;
                    while ($meResult = mysql_fetch_assoc($meQuery)) {
                      //***************************แก้ไขชื่อฟิลด์ใน record set ให้ตรงกับตารางที่ใช้ *********************************
                  
                      $key = array_search($meResult['menu_id'], $_SESSION['cart']);
                      $total_price = $total_price + ($meResult['menu_price'] * $_SESSION['qty'][$key]);
                      ?>
                      <tr>

                        <td>
                          <?php echo $meResult['menu_name']; ?>
                        </td>
                        <td style=" vertical-align: middle;">

                          <div class="input-group md-3" style="max-width: 150px; margin: 0 auto;">
                            <span onclick="change_qty('down', this); submitForm();" class="input-group-text"
                              style="cursor: pointer;"><i class="fa fa-minus text-danger"></i></span>
                            <input type="text" name="qty[<?php echo $num; ?>]" value="<?php echo $_SESSION['qty'][$key]; ?>"
                              class="form-control" style="width: 60px;text-align: center;">
                            <span onclick="change_qty('up', this); submitForm();" class="input-group-text"
                              style="cursor: pointer;"><i class="fa fa-plus text-success"></i></span>
                          </div>


                          <input type="hidden" name="arr_key_<?php echo $num; ?>" value="<?php echo $key; ?>">
                        </td>
                        <td style="text-align: right;">
                          <?php echo '฿' . number_format(($meResult['menu_price'] * $_SESSION['qty'][$key]), 2); ?>
                        </td>
                        <td style="text-align: center;">
                          <a class="btn btn-danger btn-lg" href="removecart.php?itemId=<?php echo $meResult['menu_id']; ?>"
                            role="button">

                            <?php //***************************************************************************************                                                    ?>
                            <span class="glyphicon glyphicon-trash"></span><i class="fa-solid fa-trash-can"></i>
                          </a>
                        </td>
                      </tr>
                      <?php
                      $num++;
                    }
                    ?>
                  </tbody>
                </table>
              </form>

              <?php
            }
            ?>

          </tbody>

        </table>
      </div>
      <div class="alert alert-danger my-2 text-kanit" style=" font-size: 20px;">
        Total: ฿
        <?php echo number_format($total_price, 2); ?>
      </div>
      <div class="">
        <div class="row">
          <div class="col text-center">

            <!-- Button trigger modal -->
            <button type="button" class="col text-center btn btn-success my-2" data-bs-toggle="modal"
              data-bs-target="#orderModal" style="width: 200px; height: 80px; font-family: kanit" onclick="checkout()">
              Checkout
            </button>
          </div>

          <!-- Modal -->
          <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="orderModalLabel">Checkout</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderModalBody">
                  <!-- เนื้อหาจาก order.php จะแสดงที่นี่ -->
                </div>
              </div>
            </div>
          </div>


          <div class="col text-center">
            <!-- Button trigger modal -->
            <button type="button" class="col text-center btn btn-danger my-2" data-bs-toggle="modal"
              data-bs-target="#staticBackdrop" style="width: 200px; height: 80px; font-family: kanit">
              Clear All
            </button>
          </div>

          <!-- Modal -->
          <div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog modal-dialog-centered">
              <div class="modal-content text-kanit">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="staticBackdropLabel">ล้างรายการสั่งซื้อ</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  คุณต้องการล้างรายการทั้งหมดในตะกร้า ใช่หรือไม่?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" onclick="clearCart()">OK</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </div>


        </div>


      </div>
    </div>
  </div>


  </script>

  <!-- function ต่างๆ -->
  <script>
    // ฟังก์ชั่น clearCart() ที่รับคำยืนยันจากผู้ใช้ก่อนทำการลบรายการทั้งหมด
    function clearCart() {
      window.location.href = 'clear_cart.php';
    }
  </script>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function () {
      $('.addToCart').click(function () {
        var itemId = $(this).data('item-id');
        $.ajax({
          type: 'POST',
          url: 'updatecart.php',
          data: { itemId: itemId },
          success: function (response) {
            // อัปเดตส่วนของหน้าเว็บที่ต้องการ (เช่น จำนวนรายการในตะกร้า)
            // ตัวอย่างเช่น $('#cartCount').text(response);
          },
          error: function (xhr, status, error) {
            // การจัดการเมื่อเกิดข้อผิดพลาด
            console.error(xhr.responseText);
          }
        });
      });
    });
  </script>
  <script>
    function checkout() {
      // โหลดเนื้อหาของ order.php ด้วย AJAX
      $.ajax({
        url: 'order.php',
        type: 'GET',
        success: function (response) {
          // นำเนื้อหาที่โหลดได้มาแสดงใน Modal
          $('#orderModalBody').html(response);
          // เปิด Modal ของ Order
          $('#orderModal').modal('show');
        },
        error: function (xhr, status, error) {
          // การจัดการเมื่อเกิดข้อผิดพลาด
          console.error(xhr.responseText);
        }
      });
    }


  </script>
  <script>
    // ฟังก์ชันสำหรับอัปเดต cart และ total บนหน้าเว็บไซต์
    function updateCart(itemId, qty) {
      // ส่ง request ไปยังไฟล์ updatecart.php โดยใช้ AJAX
      $.ajax({
        type: 'POST',
        url: 'updatecart.php',
        data: { itemId: itemId, qty: qty },
        success: function (response) {
          // อัปเดตการแสดงผลของ cart และ total โดยใช้ JavaScript
          // เช่น การอัปเดตข้อมูลในตารางหรือส่วนที่แสดง cart และ total บนหน้าเว็บ
          // เช่น $('#cartCount').text(response.cartCount);
          // $('#totalPrice').text(response.totalPrice);
        },
        error: function (xhr, status, error) {
          // การจัดการเมื่อเกิดข้อผิดพลาด
          console.error(xhr.responseText);
        }
      });
    }
  </script>

  <script>
    function change_qty(direction, element) {
      var inputElement = element.parentElement.querySelector('input');
      var qty = parseInt(inputElement.value);
      if (direction == "up") {
        qty += 1;
      } else {
        if (qty > 1) {
          qty -= 1;
        } else {
          qty = 1; // ถ้าน้อยกว่า 1 ให้เป็น 1
        }
      }
      inputElement.value = qty;
    }
  </script>
  <script>
    function submitForm() {
      // ใช้ JavaScript เพื่อส่ง form
      document.getElementById("cartForm").submit();
    }
  </script>



</body>

</html>