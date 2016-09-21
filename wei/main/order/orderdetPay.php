<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
$order_id = intval($_POST["order_id"]);
$out_trade_no = addslashes($_POST["out_trade_no"]);

$sql = "update sk_orders set order_sn='$out_trade_no' where order_id='$order_id'";
$do->dealsql($sql);

echo "{\"success\":\"1\"}";
?>