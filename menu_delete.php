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

//=========rec_detail=======================================================================
$colname_Rec_detail = "-1";
if (isset($_GET['mid'])) {
  $colname_Rec_detail = $_GET['mid'];
}
mysql_select_db($database_andypos_connect, $andypos_connect);
$query_Rec_detail = sprintf("SELECT menu_id FROM receiptdetail WHERE menu_id = %s", GetSQLValueString($colname_Rec_detail, "int"));
$Rec_detail = mysql_query($query_Rec_detail, $andypos_connect) or die(mysql_error());
$row_Rec_detail = mysql_fetch_assoc($Rec_detail);
$totalRows_Rec_detail = mysql_num_rows($Rec_detail);
//=========rec_detail=======================================================================

//========check rcd relation================================================================
if($totalRows_Rec_detail==0){


if ((isset($_GET['mid'])) && ($_GET['mid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM menulist WHERE menu_id=%s",
                       GetSQLValueString($_GET['mid'], "int"));

  mysql_select_db($database_andypos_connect, $andypos_connect);
  $Result1 = mysql_query($deleteSQL, $andypos_connect) or die(mysql_error());

  $deleteGoTo = "result_save.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
}//end if check rcd relation

else {
  header("Location: result_delete.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Andy Menu Delete</title>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($Rec_detail);
?>
