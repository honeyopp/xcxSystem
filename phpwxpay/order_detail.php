<?php
include_once('connect.php');

$order_no = isset($_GET['order_no']) ? $_GET['order_no'] : "";
if ($order_no) {
    $query = mysql_query("SELECT * FROM `wxb_payorder` WHERE order_no = '" . $order_no . "' AND state = 1 LIMIT 1");
    $row = mysql_fetch_array($query);

	$queryy = mysql_query("SELECT * FROM `wxb_payorder` WHERE order_no = '" . $order_no . "' AND state = 1 LIMIT 1");
	$rowk = mysql_fetch_array($queryy);	
	$ordermoney = $rowk['order_money'];
	$memberid = $rowk['member_id'];
	$updatetime =$rowk['update_time'];
				
	$querys = mysql_query("SELECT * FROM `wxb_member` WHERE member_id = '" . $memberid ."'");
	$rows = mysql_fetch_array($querys);
	$memberids = $rows['member_id'];	
	$lasttime = $rows['last_time'];
	$moneys = $rows['money'] + $row['order_money'];
	if ($updatetime != $lasttime) {
		$querysss = mysql_query("UPDATE `wxb_member` SET `money`='" . $moneys ."'  ,last_time ='" . $updatetime ."' WHERE `member_id` = '" . $memberids ."'");
	}	
}	
?>
当前订单详情
<p>订单号：<?php echo $row['order_no'] ?></p>
<p>流水号：<?php echo $row['trade_no'] ?></p>
<p>支付时间：<?php echo date("Y-m-d H:i:s", $row['update_time']) ?></p>


