<?php
require_once('Connections/andypos_connect.php');

$maxRowsPerPage = 10; // จำนวนรายการต่อหน้า
$pageNum = isset($_GET['pageNum']) ? intval($_GET['pageNum']) : 0; // เลขหน้า

// คำนวณ offset
$startRow = $pageNum * $maxRowsPerPage;

$query_Rec_report = "SELECT receiptdetail.menu_id, menulist.menu_name, menutype.type_id, menutype.type_name, 
SUM(receiptdetail.rcd_qty) AS total_sold_quantity, SUM(receiptdetail.rcd_price * receiptdetail.rcd_qty) AS total_sales_amount, 
SUM(receiptdetail.rcd_cost * receiptdetail.rcd_qty) AS total_cost, SUM((receiptdetail.rcd_price - receiptdetail.rcd_cost) * receiptdetail.rcd_qty) AS total_profit, 
MONTHNAME(receipt.rc_date) AS Month 
FROM receiptdetail, menulist, menutype, receipt 
WHERE menulist.type_id = menutype.type_id AND receiptdetail.menu_id = menulist.menu_id AND receiptdetail.rc_id = receipt.rc_id 
GROUP BY receiptdetail.menu_id ORDER BY total_sold_quantity DESC LIMIT $startRow, $maxRowsPerPage";

$Rec_report = mysql_query($query_Rec_report, $andypos_connect) or die(mysql_error());

echo "<table border='1'>";
echo "<tr>";
echo "<th>รหัสเมนู</th>";
echo "<th>ชื่อเมนู</th>";
echo "<th>ประเภท</th>";
echo "<th>จำนวนรวม</th>";
echo "<th>ยอดขายรวม</th>";
echo "<th>ต้นทุนรวม</th>";
echo "<th>กำไรรวม</th>";
echo "<th>รายละเอียด</th>";
echo "</tr>";

if (mysql_num_rows($Rec_report) > 0) {
    while ($row_Rec_report = mysql_fetch_assoc($Rec_report)) {
        echo "<tr>";
        echo "<td>{$row_Rec_report['menu_id']}</td>";
        echo "<td>{$row_Rec_report['menu_name']}</td>";
        echo "<td>{$row_Rec_report['type_id']}-{$row_Rec_report['type_name']}</td>";
        echo "<td>{$row_Rec_report['total_sold_quantity']}</td>";
        echo "<td>{$row_Rec_report['total_sales_amount']}</td>";
        echo "<td>{$row_Rec_report['total_cost']}</td>";
        echo "<td>{$row_Rec_report['total_profit']}</td>";
        echo "<td>";
        echo "<form id='form2' name='form2' method='post' action='sale_report_menu_details.php'>";
        echo "<input type='hidden' name='menu_id' id='menu_id_hidden' value='{$row_Rec_report['menu_id']}'>";
        echo "<input type='hidden' name='textmonth' id='textmonth_hidden' value='{$_POST['textmonth']}'>";
        echo "<input type='hidden' name='textyear' id='textyear_hidden' value='{$_POST['textyear']}'>";
        echo "<button type='submit' onclick=\"submitForm('{$row_Rec_report['menu_id']}')\"><i class='fas fa-info-circle'></i></button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>ไม่พบข้อมูล</td></tr>";
}

echo "</table>";

// หาจำนวนรายการทั้งหมด
$totalRows = mysql_num_rows(mysql_query("SELECT * FROM receiptdetail"));
// คำนวณจำนวนหน้าทั้งหมด
$totalPages = ceil($totalRows / $maxRowsPerPage);

// แสดงปุ่ม Next Page ถ้าไม่ได้อยู่ที่หน้าสุดท้าย
if ($pageNum < $totalPages - 1) {
    echo "<a href='home.php?pageNum=" . ($pageNum + 1) . "'>Next Page</a>";
}

mysql_free_result($Rec_report);
?>