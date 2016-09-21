<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

header("content-type:text/html;charset=utf8");
$do = new condbwei();
$com = new commonFun();
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$openid=$_SESSION['openid'];
$user=$do->getone("select jointag from sk_member where open_id='$openid'");
if($user['jointag']!=2){
	echo "<script>alert('店铺会员专享!');history.back(-1);</script>";
	exit;
}
$orderid=intval($_GET['orderid']);
$order=$do->getone("select o.order_amount,o.couponid,(SELECT count(*) FROM sk_order_goods og inner join sk_goods g on og.goods_id=g.id where og.order_id=o.order_id and is_coupon=0) is_coupon from sk_orders o where o.order_id=$orderid and o.status=0 and o.buyer_id='$openid'");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>我的现金券</title>
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="http://libs.baidu.com/jquery/1.9.1/jquery.js"></script>
	<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<style>
		.list{width:100%;border:1px solid #ededed;border-top:4px solid #FF0000;border-radius: 10px;margin-top:10px;}
		.list-bot{text-align:center;color:#B4B4B4}
		.money{text-align:center;width:30%;}
		.money h2{color:#FF0000;border-right:1px dashed #ededed;padding:1.5rem 0;}
		.money h2 small{color:#ff0000;}
		.info{width:70%;padding:1rem 0 0 0.4rem;}
		.info ul{-webkit-padding-start:20px;}
		.info li{color:#B4B4B4;}
	</style>
</head>
<body>
<div class="container-fluid">
	<?php
	$time=time();
	$list='';
	$list=$do->selectsql("select * from sk_mcoupon where open_id='$openid' and status=1 order by add_time desc");
	$str='';
	if($order['order_amount']>=500||$order['is_coupon']>0||empty($list)){
		$str="暂无可用的现金券";
	}else {
		$str='';
		foreach ($list as $v) {
			$str.="<div class='list' data-id='".$v['id']."'>";
			$str.="<div class='pull-left money'><h2><small style='font-size:60%'>￥</small>".round($v['money'])."</h2></div>";
			$str.="<div class='pull-left info'><h4 style='margin:0;'>现金券</h4><ul><li>可到商家后台拆分</li><li>单次购买不能超过500元</li><li>".date('Y-m-d',$v['start_time'])." 至 ".date('Y-m-d',$v['end_time'])."</li></ul></div>";
			$str.="<div class='clearfix'></div></div><div class='list-bot'>·每次使用至少支付1分钱</div>";
		}
	}
		echo $str;
	?>
</div>
</body>
<script>
</script>
</html>
