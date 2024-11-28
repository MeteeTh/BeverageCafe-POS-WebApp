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

$action = isset($_GET['a']) ? $_GET['a'] : "";
$itemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
if (isset($_SESSION['qty']))
{
    $meQty = 0;
    foreach ($_SESSION['qty'] as $meItem)
    {
        $meQty = $meQty + $meItem;
    }
} else
{
    $meQty = 0;
}
if (isset($_SESSION['cart']) and $itemCount > 0)
{
    $itemIds = "";
    foreach ($_SESSION['cart'] as $itemId)
    {
        $itemIds = $itemIds . $itemId . ",";
    }
    echo $inputItems = rtrim($itemIds, ",");
	
	//******************แก้ไขชื่อตารางและชื่อฟิลด์ในคำสั่ง sql ให้สอดคล้องกับฐานข้อมูลที่มี *****************
	
    $meSql = "SELECT * FROM menulist WHERE menu_id IN ({$inputItems})";
	
	
    $meQuery = mysql_query($meSql);
    $meCount = mysql_num_rows($meQuery) or die (mysql_error()) ;
} else
{
    $meCount = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Andy Cart</title>
<link rel="icon" type="image" href="images/andylogo.png">
<style type="text/css">
.text-kanit {
  font-family: Kanit;
  font-size: 20px;
}
.navbar .nav-link {
    color: #000; /* ตั้งค่าสีข้อความเป็นสีดำ */
    font-weight: bold; /* ตั้งค่าตัวหนังสือเป็นตัวหนา */
    font-family: 'Kanit', sans-serif; /* ตั้งค่าแบบอักษรเป็น Kanit */
	font-size: 18px;
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
        width: 150px; /* กำหนดความยาวของปุ่ม */
        background-color: #f0f0f0; /* เปลี่ยนสีพื้นหลังของปุ่ม */
        cursor: pointer; /* เปลี่ยน cursor เมื่อโฮเวอร์ */
}

/* เมื่อโฮเวอร์ออกจากปุ่มค้นหา */
.input-group-text2:hover::before {
  content: "ค้นหา"; /* เพิ่มข้อความ "ค้นหา" โผล่ออกมา */
  position: absolute; /* ตั้งตำแหน่งให้อยู่หลังปุ่ม */
  margin-left: 50px; /* ขยับข้อความไปทางซ้าย */
  font-size: 16px; /* กำหนดขนาดตัวอักษร */
  font-family: kanit; /* เปลี่ยนฟอนต์ */
  font-weight: bold; /* กำหนดให้เป็นตัวหนา */
  color: green; /* เปลี่ยนสีข้อความ */   
}

.hide{
			display: none;
		}

		@keyframes appear{

			0%{opacity: 0;transform: translateY(-100px);}
			100%{opacity: 1;transform: translateY(0px);}
}


</style>

<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="js/bootstrap.bundle.min.js" > </script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/all.min.css">
<link rel="stylesheet" type="text/css" href="css/cards.css">

    </head>
    <body>
    <nav class="navbar" style="background-color: #e3f2fd;">
<ul class="nav nav-pills">
  <li class="nav-item">
    <a class="nav-link" aria-current="page" href="home.php">Andy Coffee & Friends</a>
  </li>
    <li class="nav-item">
    <a class="nav-link active" href="Index.php">POS</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="menu_edit_menu.php">Menu</a>
  </li>
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Sale</a>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="sale_daily.php">สรุปบิลประจำวัน</a></li>
      <li><a class="dropdown-item" href="sale_monthly.php">สรุปบิลประจำเดือน</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="sale_report.php">สรุปการขายสินค้า</a></li>
    </ul>
  </li>
</ul>
</nav>
            <!-- Main component for a primary marketing message or call to action -->
            <?php
            if ($action == 'removed')
            {
                echo "<div class=\"alert alert-warning\">ลบสินค้าเรียบร้อยแล้ว</div>";
            }

            if ($meCount == 0)
            {
                echo "<div class=\"alert alert-warning\">ไม่มีสินค้าอยู่ในตะกร้า</div>";
            } else
            {
              
                ?>
          <form action="updatecart.php" method="post" name="fromupdate">
            <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                            <!--*******************แก้ไขให้สอดคล้องกับตารางที่ใช้ **************** -->
                                <th>ชื่อสินค้า</th>
                                <th>จำนวน</th>
                                <th>ราคา</th>
                              <!--**************************************************** -->   
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_price = 0;
                            $num = 0;
                            while ($meResult = mysql_fetch_assoc($meQuery))
                            {
        //***************************แก้ไขชื่อฟิลด์ใน record set ให้ตรงกับตารางที่ใช้ *********************************
								
								$key = array_search($meResult['menu_id'], $_SESSION['cart']);	 
                                $total_price = $total_price + ($meResult['menu_price'] * $_SESSION['qty'][$key]);
                                ?>
                                <tr>
                                    <td><?php echo $meResult['menu_name']; ?></td>
                                    <td>
                                        <input type="text" name="qty[<?php echo $num; ?>]" value="<?php echo $_SESSION['qty'][$key]; ?>" class="form-control" style="width: 60px;text-align: center;">
                                        <input type="hidden" name="arr_key_<?php echo $num; ?>" value="<?php echo $key; ?>">
                                    </td>
                                    <td><?php echo number_format(($meResult['menu_price'] * $_SESSION['qty'][$key]),2); ?></td>
                                    <td>
                                        <a class="btn btn-danger btn-lg" href="removecart.php?itemId=<?php echo $meResult['menu_id']; ?>" role="button">
                                        
      <?php //***************************************************************************************  ?>                                  
                                            <span class="glyphicon glyphicon-trash"></span>
                                            ลบทิ้ง</a>
                                    </td>
                                </tr>
                                <?php
                                $num++;
                            }
                            ?>
                            <tr>
                                <td colspan="8" style="text-align: right;">
                                    <h4>จำนวนเงินรวมทั้งหมด <?php echo number_format($total_price,2); ?> บาท</h4>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="text-align: right;">
                                    <button type="submit" class="btn btn-info btn-lg">คำนวณราคาสินค้าใหม่</button>
                                    <a href="order.php" type="button" class="btn btn-primary btn-lg">สั่งซื้อสินค้า</a>
                                </td>
                            </tr>
                        </tbody>
              </table>
        </form>

                <?php
            }
            ?>

    
    </body>
</html>
<?php
mysql_close();
