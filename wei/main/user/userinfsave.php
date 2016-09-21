<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$wei_username = addslashes($_POST["wei_username"]);
$wei_phone = addslashes($_POST["wei_phone"]);
$pay_code = addslashes($_POST["pay_code"]);
$yin_username = addslashes($_POST["yin_username"]);
$yin_code = addslashes($_POST["yin_code"]);
$brank = addslashes($_POST["brank"]);
$brank_adda = addslashes($_POST["brank_adda"]);
$brank_addb = addslashes($_POST["brank_addb"]);
$brank_zhi = addslashes($_POST["brank_zhi"]);

$sql = "update sk_member set wei_username='$wei_username',wei_phone='$wei_phone',pay_code='$pay_code',yin_username='$yin_username',
		yin_code='$yin_code',brank='$brank',brank_adda='$brank_adda',brank_addb='$brank_addb',brank_zhi='$brank_zhi' 
		where open_id='$openid'";
$do->dealsql($sql);
echo "{\"success\":\"1\"}";
?>