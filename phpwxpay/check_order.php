<?php
include_once('connect.php');
//检测订单是否支付成功
$order_no = isset($_POST['order_no']) ? $_POST['order_no'] : "";

if ($order_no) {
    $query = mysql_query("SELECT id FROM `wxb_payorder` WHERE order_no = '" . $order_no . "' AND state = 1 LIMIT 1");
    $row = mysql_fetch_array($query);
    if ($row) {
        echo $row['id'];//若是返回值，大于0则说明订单支付成功
    }
}


