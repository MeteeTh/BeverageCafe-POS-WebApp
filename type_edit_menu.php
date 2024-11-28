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
    $query_Rec_menutype = "SELECT menutype.* FROM menutype WHERE type_name LIKE '%" . $search_term . "%' ORDER BY type_name ASC";
} else {
    $query_Rec_menutype = "SELECT menutype.* FROM menutype ORDER BY type_id ASC";
}
mysql_select_db($database_andypos_connect, $andypos_connect);
$Rec_menutype = mysql_query($query_Rec_menutype, $andypos_connect) or die (mysql_error());
$row_Rec_menutype = mysql_fetch_assoc($Rec_menutype);
$totalRows_Rec_menutype = mysql_num_rows($Rec_menutype);
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>


    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Andy Type</title>
    <link rel="icon" type="image" href="images/andylogoCR.png">
    <!-- เพิ่มไฟล์ css-->
    <link rel="stylesheet" type="text/css" href="editbutton.css">
    <link rel="stylesheet" type="text/css" href="deletebutton.css">
    <link rel="stylesheet" type="text/css" href="css/all.min.css">
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script src="js/bootstrap.bundle.js"></script>

    <style type="text/css">
        body {
            font-family: Kanit;
            background-image: url('images/bg5.jpg');
            background-size: cover;
            background-repeat: no-repeat;

            /* background: linear-gradient(to bottom right, #FFF, #9AFEFF, #FFF, #9AFEFF, #FFF); */
        }

        .text-kanit {
            font-family: Kanit;
            font-size: 20px;
        }

        .text-kanitx {
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

        a.ex1:hover,
        a.ex1:active {
            color: blue;
        }

        a.ex2:hover,
        a.ex2:active {
            background-color: #FFB800;
        }

        a.ex3:hover,
        a.ex3:active {
            background-color: #090;
        }

        a.ex4:hover,
        a.ex4:active {
            background-color: #C00;
        }

        p.rounded {
            border-style: rounded;
        }


        .add-new-button {
            /* Set the button's appearance */
            appearance: none;
            background-image: linear-gradient(to bottom right, #FC0, #FFFFC2);
            border: none;
            border-radius: 4px;
            color: #000000;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            font-family: Kanit;
            font-size: 16px;
            font-weight: bold;
            height: 40px;
            justify-content: center;
            padding: 0 12px;
            text-transform: uppercase;
            width: auto;

            :focus {
                box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.5);
            }
        }

        .add-new-button:hover {
            background-image: linear-gradient(to bottom right, #FFB800, #FFFFC2);
            /* เปลี่ยนสีพื้นหลังเมื่อ hover */
            color: #000000;
            /* เปลี่ยนสีของตัวหนังสือเป็นสีดำเมื่อ hover */
        }

        .add-new-button::before {

            font-size: 24px;
            line-height: 1;
            margin-right: 8px;
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

        .edit-button,
        .delete-button {
            text-decoration: none;
            /* ลบขีดเส้นใต้ */
        }

        .add-new-button {
            text-decoration: none;
            /* ลบขีดเส้นใต้ */
        }

        .form-control2 {

            width: 40%;
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



</head>

<body>

    <!-- Navbar================================================================-->
    <nav class="navbar fixed-top"
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

    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="80%" border="0" align="center" cellpadding="5" cellspacing="5" style="vertical-align: middle;">

        <td colspan="3" align="center" class="text-kanit">
            <h3><strong>จัดการรายการประเภท</strong></h3>


            <tr align="right">

                <td width="61%" class="text-kanit">
                    <style>
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

                    <!-- แท็ก form และปุ่มค้นหา -->
                    <form action="" method="get">
                        <p>
                            <input name="search" type="text" placeholder="ป้อนชื่อประเภท" size="25"
                                style="height:40px; font-size: 20px;" class="form-control2" autofocus />
                            <button type="submit" class="search-button" style="font-size: 20px;">ค้นหา</button>
                        </p>
                    </form>


                </td>
                <td width="2%" align="left" class="text-kanit">&nbsp;</td>
                <td align="left" class="text-kanit">

                    <a href="type_insert.php" class="add-new-button ex2"
                        style="height: 40px; width: 180px; letter-spacing: 2px; font-size: 20px;">เพิ่มประเภท&nbsp;<i
                            class="fa-solid fa-plus"></i></a>

                    </p>
                </td>
            </tr>

            <tr>
                <td colspan="3" align="center" class="text-kanit">
                    <h5>ผลการค้นหาพบ
                        <?php echo $totalRows_Rec_menutype ?> รายการ
                    </h5>
                    <p></p>
                    <div style="height: 65vh; overflow-y: scroll; ">
                        <table border="0" align="center" cellpadding="5" cellspacing="5"
                            class="table table-striped table-bordered text-kanitx" style="vertical-align: middle;">
                            <tr>
                                <td width="13%" height="54" align="center"><strong>รหัสประเภท</strong></td>
                                <td width="26%" align="center"><strong>ชื่อประเภท</strong></td>
                                <td width="26%" align="center"><strong>สีประเภท</strong></td>
                                <td width="7%" align="center"><strong>แก้ไข</strong></td>
                                <td width="8%" align="center"><strong>ลบ</strong></td>
                            </tr>
                            <?php
                            if (mysql_num_rows($Rec_menutype) > 0) {
                                ?>
                                <?php do { ?>
                                    <tr>
                                        <td align="center">
                                            <p>
                                                <?php echo $row_Rec_menutype['type_id']; ?>
                                            </p>
                                        </td>
                                        <td align="left">
                                            <p>
                                                <?php echo $row_Rec_menutype['type_name']; ?>
                                            </p>
                                        </td>
                                        <td align="left">
                                            <div style="display: flex; align-items: center; ">
                                                <div
                                                    style="width: 50px; height: 50px; background-color: <?php echo $row_Rec_menutype['type_color']; ?>; border-radius: 50px;" >
                                                </div>
                                                <span style="margin-left: 5px;">
                                                    <?php echo $row_Rec_menutype['type_color']; ?>
                                                </span>
                                            </div>
                                        </td>

                                        <td align="center">
                                            <p><a href="type_edit.php?tid=<?php echo $row_Rec_menutype['type_id']; ?>"
                                                    class="edit-button ex3" style="letter-spacing: 1px; font-size: 18px;"><i
                                                        class="fa-solid fa-pen"></i></a></p>
                                        </td>

                                        <td align="center">
                                            <p><a href="#" class="delete-button ex4"
                                                    style="letter-spacing: 1px; font-size: 18px;" data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteModal"
                                                    data-name="<?php echo $row_Rec_menutype['type_name']; ?>"
                                                    data-id="<?php echo $row_Rec_menutype['type_id']; ?>"><i
                                                        class="fa-solid fa-trash-can"></i></a></p>
                                        </td>

                                        <!-- Modal -->
                                        <div class="modal fade" id="confirmDeleteModal" tabindex="-1"
                                            aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fs-5" id="confirmDeleteModalLabel">Confirm Delete
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" style="font-size: 20px;">
                                                        คุณต้องการลบประเภท <span id="menuTypeToDelete"></span> ใช่หรือไม่ ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a id="confirmDeleteButton" href="#" class="btn btn-success">OK</a>
                                                        <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

                                        <script>
                                            $(document).ready(function () {
                                                $('.delete-button').click(function () {
                                                    var menuType = $(this).data('name');
                                                    var menuId = $(this).data('id');
                                                    $('#menuTypeToDelete').text(menuType);
                                                    $('#confirmDeleteButton').attr('href', 'type_delete.php?tid=' + menuId);
                                                });
                                            });
                                        </script>


                                    </tr>

                                <?php } while ($row_Rec_menutype = mysql_fetch_assoc($Rec_menutype)); ?>

                            </table>
                        </div>
                        <?php
                            } else {
                                ?>
                        <!-- แสดงข้อความเมื่อไม่พบข้อมูล -->
                    <td colspan="7" align="center" style="color: red; font-weight: bold;">
                        ไม่พบข้อมูล
                    </td>
                    <?php
                            }
                            ?>
        </td>
        </tr>
    </table>
</body>

</html>
<?php
mysql_free_result($Rec_menutype);
?>