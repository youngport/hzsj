<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
$do = new condbwei();
$com = new commonFun();
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$openid = $_SESSION["openid"];
$goodsid = intval($_POST['goodsid']);
$gsid = intval($_POST['gsid']);
$guige = addslashes($_POST['guige']);
$h="select id,gsid,cartid,status from sk_gs_jl where openid='$openid' and gsid='$gsid'";
$jl=$do->getone($h);
if(isset($jl['status'])&&$jl['status']==1){
	echo 2;
}elseif(isset($jl['status'])&&$jl['status']==0){
	$h="select count(*) count from sk_gs where gid='$goodsid' and start_time<".time()." and end_time>".time()." and status=1";
	$harr = $do->getone($h);
	if($harr['count']<=0){
		echo 3;exit;
	}
	$sql="select count(*) count from wei_cart where cart_id=".$jl['cartid'];
	$cart=$do->getone($sql);
	if($cart['count']>0)
		echo 4;
	else{
		$kaituan = "insert into wei_cart (openid,goodsid,guige,buynum,danjia) values ('$openid','$goodsid','$guige',1,2)";//加入购物车
		$do->query($kaituan);
		$do->query("update sk_gs_jl set cartid='".mysql_insert_id()."' where id=".$jl['id']);
		echo 1;
	}
}else{
	$kaituan = "insert into wei_cart (openid,goodsid,guige,buynum,danjia) values ('$openid','$goodsid','$guige',1,2)";//加入购物车
	$do->query($kaituan);
	$do->dealsql("insert into sk_gs_jl (gsid,openid,cartid) values ('$gsid','$openid','".mysql_insert_id()."')");
	echo 1;
}
?>


