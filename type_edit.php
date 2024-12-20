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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset ($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset ($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf(
        "UPDATE menutype SET type_name=%s, type_color=%s WHERE type_id=%s",
        GetSQLValueString($_POST['type_name'], "text"),
        GetSQLValueString($_POST['type_color'], "text"),
        GetSQLValueString($_POST['type_id'], "int")
    );

    mysql_select_db($database_andypos_connect, $andypos_connect);
    $Result1 = mysql_query($updateSQL, $andypos_connect) or die (mysql_error());

    $updateGoTo = "result_typesave.php";
    if (isset ($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

$colname_Rec_type = "-1";
if (isset ($_GET['tid'])) {
    $colname_Rec_type = $_GET['tid'];
}
mysql_select_db($database_andypos_connect, $andypos_connect);
$query_Rec_type = sprintf("SELECT * FROM menutype WHERE type_id = %s", GetSQLValueString($colname_Rec_type, "int"));
$Rec_type = mysql_query($query_Rec_type, $andypos_connect) or die (mysql_error());
$row_Rec_type = mysql_fetch_assoc($Rec_type);

$totalRows_Rec_type = "-1";
if (isset ($_GET['tid'])) {
    $totalRows_Rec_type = $_GET['tid'];
}
$colname_Rec_type = "-1";
if (isset ($_GET['tid'])) {
    $colname_Rec_type = $_GET['tid'];
}



?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Andy type Edit</title>
    <link rel="icon" type="image" href="images/andylogoCR.png">
    <style type="text/css">
        body {
            background-image: url('images/bg5.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }

        .text-warning {
            color: #FF0000;
            font-family: Kanit;
            font-size: large;
        }

        .text-kanit {
            font-family: Kanit;
            color: #000;
            font-size: 20px;
        }

        .text-kanitx {
            font-family: Kanit;
            color: #000;
            font-size: x-large;
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

        /* เปลี่ยนสีและตัวหนังสือของปุ่ม Cancel */
        #Cancel {
            background-color: #38B6FF;
            /* สีฟ้า */
            color: #ffffff;
            /* สีขาว */
            width: 90px;
            height: 50px;
            font-family: Kanit;
            font-size: larger;
            border: none;
            /* ลบขอบ */
            border-radius: 10px;
            /* เหลามุม */
        }

        /* เปลี่ยนสีและตัวหนังสือของปุ่ม Save */
        #Save {
            background-color: #FF3B3B;
            /* สีแดง */
            color: #ffffff;
            /* สีขาว */
            width: 90px;
            height: 50px;
            font-family: Kanit;
            font-size: larger;
            border: none;
            /* ลบขอบ */
            border-radius: 10px;
            /* เหลามุม */
        }

        /* เพิ่มคลาสเพื่อเปลี่ยนสีของลิงก์ที่มีคลาส "active" */
        .navbar .nav-link.active.custom-color {
            background-color: #0d47a1;
            /* สีพื้นหลังที่เหมือนกับ navbar */
            color: #fff;
            /* เปลี่ยนสีตัวหนังสือเป็นสีขาว */
        }

        /* เปลี่ยนสีและตัวหนังสือของปุ่ม Save */
        #Save {
            background-color: #38B6FF;
            /* สีแดง */
            color: #ffffff;
            /* สีขาว */
            width: 90px;
            height: 50px;
            font-family: Kanit;
            font-size: larger;
            border: none;
            /* ลบขอบ */
            border-radius: 10px;
            /* เหลามุม */
            transition: background-color 0.3s ease;
            /* เพิ่ม transition เพื่อสร้างเอฟเฟกต์เมื่อ hover */
        }

        /* เมื่อ hover กับปุ่ม Save */
        #Save:hover {
            background-color: #1c87e4;
            /* เปลี่ยนสีเข้มขึ้นเมื่อ hover */
        }

        /* เปลี่ยนสีและตัวหนังสือของปุ่ม Cancel */
        #Cancel {
            background-color: #FF3B3B;
            /* สีฟ้า */
            color: #ffffff;
            /* สีขาว */
            width: 90px;
            height: 50px;
            font-family: Kanit;
            font-size: larger;
            border: none;
            /* ลบขอบ */
            border-radius: 10px;
            /* เหลามุม */
            transition: background-color 0.3s ease;
            /* เพิ่ม transition เพื่อสร้างเอฟเฟกต์เมื่อ hover */
        }

        /* เมื่อ hover กับปุ่ม Cancel */
        #Cancel:hover {
            background-color: #d32626;
            /* เปลี่ยนสีเข้มขึ้นเมื่อ hover */
        }

        .form-control2 {

            width: auto;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: var(--bs-body-color);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-color: var(--bs-body-bg);
            background-clip: padding-box;
            border: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: var(--bs-border-radius);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
    </style>

    <!-- ตรวจการกรอกข้อมูลครบหรือไม่ -->
    <script type="text/javascript">
        function validateForm() {
            var menuName = document.forms["form1"]["menu_name"].value;
            var type = document.forms["form1"]["type_id"].value;
            var cost = document.forms["form1"]["menu_cost"].value;
            var price = document.forms["form1"]["menu_price"].value;

            if (menuName == "" || type == "" || cost == "" || parseFloat(cost) <= 0 || price === "" || parseFloat(price) <= 0) {
                $('#alertModal').modal('show'); // เรียกใช้ modal เมื่อข้อมูลไม่ถูกต้อง
                return false;
            }
        }
    </script>

    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script src="js/bootstrap.bundle.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
</head>

<body>

    <!-- Navbar================================================================-->
    <nav class="navbar"
        style="background-image: linear-gradient(to right, #1976d2, #1488CC, #2B32B2); font-size: 20px;">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link" href="home.php">Andy Coffee & Friends</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Index.php">POS</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle active custom-color" data-bs-toggle="dropdown" href="#" role="button"
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
    <!-- Navbar================================================================-->


    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1"
        onsubmit="return validateForm()">
        <p>&nbsp;</p>
        <table align="center">
            <tr valign="baseline">
                <td width="388" colspan="3" nowrap="nowrap" class="text-kanitx">
                    <p
                        style="text-align: center; font-size: 36px; font-weight: bold; color: #FFD700; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); border-bottom: 2px solid #FFD700;">
                        แก้ไขประเภท</p>

                    <p><span class="text-kanitx">รหัสประเภท</span><font color="red">*</font>
                    </p>
                    <p>
                        <input type="text" class="text-kanit form-control" disabled
                            value="<?php echo $row_Rec_type['type_id']; ?>">
                    </p>
                </td>
            </tr>
            <tr valign="baseline">
                <td colspan="2" nowrap="nowrap" class="text-kanitx">
                    <p><span class="text-kanitx">ชื่อประเภท</span><font color="red">*</font>
                    </p>
                    <p><span class="text-kanit">
                            <input name="type_name" type="text" class="text-kanit form-control"
                                placeholder="ป้อนชื่อประเภท"
                                value="<?php echo htmlentities($row_Rec_type['type_name'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" style="width: 100%;" />
                        </span></p>
                </td>
            </tr>
            <style>
                .color-container {
                    display: flex;
                    align-items: center;
                    .
                }

                .color-picker {
                    width: 50px;
                    height: 50px;
                    padding: 0;
                    margin: 0;
                }

                .color-code {
                    font-family: Arial, sans-serif;
                    font-size: 14px;
                    margin-left: 10px;

                }
            </style>


            <tr valign="baseline">
                <td colspan="3" nowrap="nowrap" class="text-kanitx">
                    <p>เลือกสีของประเภท<font color="red">*</font>
                    </p>
                    <div class="color-container">
                        <input type="color" name="type_color" class="text-kanit form-control color-picker"
                            value="<?php echo $row_Rec_type['type_color']; ?>">
                        <span class="color-code"></span>
                    </div>
                </td>
            </tr>



            <script>
                const colorPicker = document.querySelector('input[type="color"]');
                const colorCode = document.querySelector('.color-code');
                let selectedColor = colorPicker.value;
                const typeColorInput = document.querySelector('input[name="type_color"]');

                colorPicker.addEventListener('input', function () {
                    selectedColor = this.value;
                    colorCode.innerHTML = '<span class="text-kanit">รหัสสี: </span><span class="text-kanit">' + selectedColor + '</span>';
                    typeColorInput.value = selectedColor;
                });

                document.addEventListener("DOMContentLoaded", function () {
                    selectedColor = '<?php echo $row_Rec_type['type_color']; ?>';
                    colorCode.innerHTML = '<span class="text-kanit">รหัสสี: </span><span class="text-kanit">' + selectedColor + '</span>';
                    typeColorInput.value = selectedColor;
                });
            </script>


            <tr valign="baseline">
                <td colspan="3" nowrap="nowrap" class="text-kanit"> &nbsp;</td>
            </tr>
            <tr valign="baseline">
                <td align="center" nowrap="nowrap"><span class="text-kanit">
                        <input name="Save" type="submit" class="text-kanit" id="Save" value="Save" />
                    </span></td>
                <td align="center" nowrap="nowrap"><span class="text-kanit">
                        <input name="Cancel" type="reset" class="text-kanit" id="Cancel" value="Cancel" />
                    </span></td>

                <script>
                    // หากมีการคลิกที่ปุ่ม Cancel
                    document.getElementById('Cancel').addEventListener('click', function () {
                        // คืนค่ารหัสสีเดิมจากตัวแปรเก็บรหัสสีเดิม
                        var originalColor = "<?php echo $row_Rec_type['type_color']; ?>";
                        // กำหนดค่ารหัสสีกลับไปยังช่องข้อมูลแสดงรหัสสีและช่องข้อมูลซ่อน
                        colorCode.innerHTML = '<span class="text-kanit">รหัสสี: </span><span class="text-kanit">' + originalColor + '</span>';
                        typeColorInput.value = originalColor;
                    });
                </script>

            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1" />
        <input type="hidden" name="type_id" value="<?php echo $row_Rec_type['type_id']; ?>" />
    </form>
    <p>&nbsp;</p>
    </td>
    </tr>
    </table>
    <style>
        .modal-danger .modal-content {
            border: 1px solid #dc3545;
        }

        .modal-danger .modal-header {
            background-color: #dc3545;
            border-bottom: none;
        }

        .modal-danger .modal-header h5 {
            color: #fff;
        }

        .modal-danger .btn-close {
            color: #fff;
        }

        .modal-danger .modal-footer .btn {
            color: #fff;
            background-color: #dc3545;
            border: 1px solid #dc3545;
        }

        .modal-danger .modal-footer .btn:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
    <!-- Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-danger">
            <div class="modal-content text-kanit">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="alertModalLabel">แจ้งเตือน</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    กรุณากรอกข้อมูลให้ครบทุกช่อง<br>
                    Price และ Cost ต้องไม่น้อยกว่าหรือเท่ากับ 0
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php
mysql_free_result($Rec_type);

?>