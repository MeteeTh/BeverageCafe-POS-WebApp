<?php
session_start();
$formid = isset($_SESSION['formid']) ? $_SESSION['formid'] : "";
if ($formid != $_POST['formid']) {
	echo "E00001!! SESSION ERROR RETRY AGAINT.";
} else {
	unset($_SESSION['formid']);
	if ($_POST) {
		require 'Connections/andypos_connect.php';

		$order_time = mysql_real_escape_string($_POST['rc_time']);
		$order_pay = mysql_real_escape_string($_POST['rc_pt']);

		$meSql = "INSERT INTO receipt (rc_id, rc_date, rc_time, rc_pt) VALUES (NULL, CURRENT_DATE,CURRENT_TIME,'{$order_pay}') ";
		
		$meQeury = mysql_query($meSql);
		if ($meQeury) {
			$order_id = mysql_insert_id();
			for ($i = 0; $i < count($_POST['qty']); $i++) {
				$order_detail_quantity = mysql_real_escape_string($_POST['qty'][$i]);
				$order_detail_price = mysql_real_escape_string($_POST['menu_price'][$i]);
				$product_id = mysql_real_escape_string($_POST['menu_id'][$i]);
				$order_detail_cost = mysql_real_escape_string($_POST['menu_cost'][$i]);
				
				$lineSql = "INSERT INTO receiptdetail (rc_id, menu_id, rcd_qty, rcd_price,rcd_cost) VALUES ('{$order_id}','{$product_id}','{$order_detail_quantity}','{$order_detail_price}','{$order_detail_cost}')";
				
				mysql_query($lineSql);
			}
			mysql_close();
			unset($_SESSION['cart']);
			unset($_SESSION['qty']);
			header('location:bill.php'); // change file name
		}else{
			mysql_close();
			header('location:index.php?a=orderfail');
		} 
	}
}