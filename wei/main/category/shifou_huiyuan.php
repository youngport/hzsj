<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();

session_start();
$openid = $_SESSION["openid"];

$sql = "select jointag from sk_member where open_id='$openid'";
$sf_hy_Arr = $do->selectsql($sql);
if($sf_hy_Arr[0]['jointag']==1||$sf_hy_Arr[0]['jointag']==2){
	echo 1;
}else{
	echo 0;
}
?>