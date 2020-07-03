<?php
header("Content-type: text/html; charset=utf-8");
session_start();

$host="localhost";
$db_user="root";//数据库帐号
$db_pass="Brucewoo1979#";//数据库密码
$db_name="xiaochengxu";//数据库名

$timezone="Asia/Shanghai";
$link=mysqli_connect($host,$db_user,$db_pass,$db_name,'3306');
header("Content-Type: text/html; charset=utf-8");
date_default_timezone_set($timezone); //北京时间
?>
