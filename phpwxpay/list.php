<?php
	include_once('connect.php');
	$memberid = isset($_GET['memberid']) ? $_GET['memberid'] : "";
    $query = mysqli_query($GLOBALS['link'],"SELECT * FROM `wxb_payorder` WHERE member_id = '" . $memberid . "' AND state = 1  LIMIT 100");

?>


<title>订单列表</title>
<style>
     body{ background:#fff;}
    .wx_img{ margin-top:10px;}
    .wx_img img{ border:1px solid #ddd;}
</style>
</head>
<body>


			<table>

                            <thead>
								<tr>
								   <th width="5%" align="center">ID</th>
								   <th width="25%" align="center">订单号</th>
								   <th width="25%" align="center">流水号</th>
								   <th width="15%" align="center">支付时间</th>
							   </tr>
                            </thead>
                            <tbody>
							<?php while ($row = mysqli_fetch_array($query)) {?>
                                <tr style="font-size: 12px;">
                                    <td align="center"><?php echo $row['id'] ?></td>
                                    <td align="center"><?php echo $row['order_no'] ?></td>
                                    <td align="center"><?php echo $row['trade_no'] ?></td>
                                    <td align="center"><?php echo date("Y-m-d H:i:s", $row['update_time']) ?></td>
                                </tr>
							<?}?>


                            </tbody>
			</table>


</body>
</html>
