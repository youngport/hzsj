<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$openid=$_SESSION['openid'];
$orderid=intval($_GET['orderid']);
$order=$do->getone("select o.goods_amount,o.couponid,(SELECT count(*) FROM sk_order_goods og inner join sk_goods g on og.goods_id=g.id where og.order_id=o.order_id and is_coupon=0) is_coupon from sk_orders o where o.order_id=$orderid and o.status=0 and o.buyer_id='$openid'");
if(empty($order)){
	header("location:http://m.hz41319.com/wei/index.php");
	exit;
}
$gslist=$do->selectsql("select g.id,g.cate_id  from sk_order_goods og inner join sk_goods g on g.id=og.goods_id where order_id=$orderid");
foreach($gslist as $v){
	$cates=$v['cate_id'].',';
	$goods=$v['id'].",";
}
$cates=trim($cates,",");
$goods=trim($goods,",");
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
	<title>我的优惠券</title>
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="http://libs.baidu.com/jquery/1.9.1/jquery.js"></script>
	<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<style>
		.list{width:100%;}
		.td1{width:85%;}
		.td2{text-align:center;width:15%;padding:0;}
		.check{width:85%}
		.conpon-list{background:#EC6F7F;width:100%;margin-top:15px;border-radius:10px;border:1px solid #ededed;overflow:hidden;}
		.conpon-list h1{padding:0.7rem 0;color:#fff}
		.conpon-list small{color:#fff}
		.conpon-info h6{padding:0.5rem 0;margin:0;}
		.conpon-info{padding:0 10px;background:#fff}
	</style>
</head>
<body>
<div>
	<div class="container-fluid" id="list" style="overflow-y:scroll;">
		<table class="list">
			<?php
			$time=time();
			$list='';
			$list=$do->selectsql("select * from sk_coupon where rec='$openid' and status in(1,3) and (type=0 or (type=1 and tid in ($cates)) or (type=2 and tid in ($goods))) and start_time<=$time and end_time>=$time and xz<=".$order['goods_amount']." order by end_time desc");
			$str='';
			if($order['is_coupon']>0||empty($list)){
				$str="<tr><td colspan='2'>暂无可用的优惠券</td></tr>";
			}else {
				foreach ($list as $v) {
					$str .= "<tr>";
					$str .= "<td class='td1'><div class='conpon-list text-center'>";
					if($v['sendid']==303&&$v['type']==2&&$v['tid']==336){
						$str .= "<h1>天美会员专享券</h1 >";
					}else {
						$str .= "<h1>" . $v['js'] . "<small >元  &nbsp;&nbsp;代金券</small ></h1 >";
					}
					$str .= "<div class='conpon-info' >";
					$str .= "<h6 class='pull-left'>购满" . $v['xz'] . "元后可用</h6>";
					$str .= "<h6 class='pull-right'>有效期至" . date("Y-m-d", $v['end_time']) . "</h6>";
					$str .= "<div class='clearfix'></div ></div ></div ></td >";
					$str .= "<td class='td2' style ='vertical-align: middle'><img data-status='off' data-id='" . $v['id'] . "' src='../../css/img/off.png' class='check'/></td >";
					$str .= "</tr>";
				}
			}
			echo $str;
			?>
		</table>
	</div>
	<nav class="navbar navbar-default navbar-fixed-bottom">
		<div class="container text-center">
			<button class="btn btn-lg submit" style="width:60%;background:#EC6F7F">确定</button>
		</div>
	</nav>
</div>
</body>
<script>
	$(".check[data-id='<?php echo $order['couponid'];?>']").attr("data-status","on").attr("src","../../css/img/on.png");
	$('#list').height($(document).height()*0.63);
	$('.check').click(function(){
		if($(this).attr('data-status')=='off') {
			$('.check').attr('src',"../../css/img/off.png");
			$('.check').attr('data-status',"off");
			$(this).attr('src', "../../css/img/on.png");
			$(this).attr('data-status','on');
		}else {
			$(this).attr('src', "../../css/img/off.png");
			$(this).attr('data-status','off');
		}
	});
	$('.submit').click(function(){
		var id=$(".check[data-status='on']").attr('data-id');
		$.ajax({
			type:"POST",
			url:"coupon_post.php",
			data:{id:id,orderid:"<?php echo $orderid;?>"},
			success:function(data){
				if(data==1){
					location.href="order.php?orderid=<?php echo $orderid;?>";
				}else if(data==0){
					alert("订单非法");
				}else if(data==2){
					alert("优惠券非法,请重新选择");
				}
			}
		});
	});
</script>
</html>
