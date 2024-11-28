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

$colname_Rec_menu = "-1";
if (isset($_POST['textsearch'])) {
  $colname_Rec_menu = $_POST['textsearch'];
}
mysql_select_db($database_andypos_connect, $andypos_connect);
$query_Rec_menu = sprintf("SELECT menulist.*, menutype.type_name FROM menulist INNER JOIN menutype ON menulist.type_id = menutype.type_id WHERE menulist.menu_name LIKE %s ORDER BY menulist.menu_name ASC", GetSQLValueString("%" . $colname_Rec_menu . "%", "text"));
$Rec_menu = mysql_query($query_Rec_menu, $andypos_connect) or die(mysql_error());
$row_Rec_menu = mysql_fetch_assoc($Rec_menu);
$totalRows_Rec_menu = mysql_num_rows($Rec_menu);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Andy Menu Search Result</title>
<link rel="icon" type="image" href="images/andylogo.png">
</head>

<body>
<table width="70%" border="0" align="center" cellpadding="5" cellspacing="5">
<tr></tr>
<tr>
  <td align="center"><strong>Menu Search Results = <?php echo $totalRows_Rec_menu ?> Items</strong></td>
</tr>
<tr>
  <td align="center"><form id="form1" name="form1" method="post" action="">
    <label for="textsearch"></label>
    <input type="text" name="textsearch" id="textsearch" />
    <input type="submit" name="button" id="button" value="Submit" />
  </form>
    &nbsp;
    <table border="0" cellpadding="5" cellspacing="5">
      <tr>
        <td align="center"><strong>No.</strong></td>
        <td align="left"><strong>Name</strong></td>
        <td align="left"><strong>Type</strong></td>
        <td align="right"><strong>Price</strong></td>
        <td align="right"><strong>Cost</strong></td>
        </tr>
      <?php $i=1; do { //แสดงลำดับ ?> 
        <tr>
          <td align="center"><?php echo $i;?>.</td>
          <td align="left"><?php echo $row_Rec_menu['menu_name']; ?></td>
          <td align="left"><?php echo $row_Rec_menu['type_id']; ?> - <?php echo $row_Rec_menu['type_name']; ?></td>
          <td align="right">฿<?php echo $row_Rec_menu['menu_price']; ?></td>
          <td align="right">฿<?php echo $row_Rec_menu['menu_cost']; ?></td>
        </tr>
        <?php $i++;} while ($row_Rec_menu = mysql_fetch_assoc($Rec_menu)); ?>
    </table></td>
</tr>
</table>
</body>
</html>
<?php
mysql_free_result($Rec_menu);
?>
