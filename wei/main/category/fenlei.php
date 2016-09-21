<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$openid = $_SESSION["openid"];
$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl = 'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$sql = "select jointag from sk_member where open_id='$openid'";
$do->getone($sql);
$time=time();
$sql="select g.id,g.bimg,g.good_name,g.price,ug.price ugprice from sk_user_goods ug inner join sk_goods g on g.id=ug.gid  where ug.status=1 and ug.start_time<=$time and ug.end_time>=$time";
$list=$do->selectsql($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=100%; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<title>会员加盟</title>
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v2.0" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/swiper.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>

	<style>
		*{margin:0;padding:0;}
		a{text-decoration: none;}
		ul,ol{list-style:none}
		body{font-family:'微软雅黑';}
		h1,h2,h3,h4,h5,h6{font-weight:100;}

		.clearfix{clear:both;overflow:hidden;}
		.bigbox,.botbox{width:100%;}
		.head{width:100%;margin:0 auto;padding:5px 0;background:#fff;}
		.head span{text-align:center;width:50%;display:inline-block;float:left;height:27px;line-height:27px;}
		.head span:nth-child(1){border-right:solid 1px #898989;margin-left:-1px;}
		.head span font{width:80%;display:block;font-size:18px;margin:0 auto;}
		.head span a{width:80%;display:block;font-size:18px;margin:0 auto;text-decoration: none;color:#000}
		.head span font.on{border-bottom:solid 2px #e83428;}
		.botbox{background:#fff}
		.botbox .yyou{width:100%;margin:0 auto;display:none;}
		.botbox .yyou .banner{width:100%;}
		.botbox .yyou .obg{width:90%;background:#eeefef;padding:0 5% 20px 5%;}
		.botbox .yyou .obg li img{width:45%;float:left;}
		.botbox .yyou .obg li{background:#fff;margin-top:12px;clear:both;overflow:hidden;border:solid 1px #c9c9ca;clear:both;overflow:hidden;}
		.botbox .yyou .obg li .riht{width:45%;float:right;margin-right:5%;}
		.botbox .yyou .obg li .riht h3{margin:9% 0 6% 0;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
		.botbox .yyou .obg li .riht span{display:block;width:100%;clear:both;overflow:hidden;}
		.botbox .yyou .obg li .riht span font{float:left;color:#3f3a39;height:16px;line-height:16px;}
		.botbox .yyou .obg li .riht span b{float:left;color:#e45194;font-size:16px;height:16px;line-height:16px;}
		.buy{display:block;background:#c03f61;height:30px;line-height:30px;color:#fff;text-align:center;border-radius:4px;font-size:16px;display:block;width:100%;margin:5% auto 0 auto;}
		.swiper-slide img{width:100%;display:block;}

		@media (min-width: 600px) and (max-width: 900px) {
			.botbox .yyou .obg li .riht h3{font-size:20px;margin-top:12%}
			.botbox .yyou .obg li .riht span font{float:left;color:#3f3a39;height:20px;line-height:26px;font-size:20px;}
			.botbox .yyou .obg li .riht span b{float:left;color:#e45194;font-size:26px;height:26px;line-height:26px;}
			.buy{height:40px;line-height:40px;margin-top:8%;}
		}
		@media (min-width: 901px) and (max-width: 1200px) {
			.botbox .yyou .obg li .riht h3{font-size:24px;margin-top:16%}
			.botbox .yyou .obg li .riht span font{float:left;color:#3f3a39;height:20px;line-height:26px;font-size:24px;}
			.botbox .yyou .obg li .riht span b{float:left;color:#e45194;font-size:28px;height:26px;line-height:26px;}
			.buy{height:50px;line-height:50px;margin-top:10%;font-size:28px;}
		}
		@media (min-width: 1201px) and (max-width: 120000px) {
			.botbox .yyou .obg li .riht h3{font-size:26px;margin-top:20%}
			.botbox .yyou .obg li .riht span font{float:left;color:#3f3a39;height:20px;line-height:26px;font-size:26px;}
			.botbox .yyou .obg li .riht span b{float:left;color:#e45194;font-size:33px;height:26px;line-height:26px;}
			.buy{height:56px;line-height:56px;margin-top:13%;font-size:30px;}
		}
		.bottom span{font-size:10px;}
	</style>
	<script type="text/javascript">
		var img_url = 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/logo.png';
		wx.config({
			appId: '<?php echo $shareCheckArr["appid"];?>',
			timestamp: '<?php echo $shareCheckArr["timestamp"];?>',
			nonceStr: '<?php echo $shareCheckArr["noncestr"];?>',
			signature: '<?php echo $shareCheckArr["signature"];?>',
			jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','hideMenuItems'] // 功能列表，我们要使用JS-SDK的什么功能
		});
		wx.ready(function(){
			var shareData = {
				title: '洋仆淘跨境商城——99元购会员送百元商品',
				desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
				link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>|huiyuan&connect_redirect=1#wechat_redirect',
				imgUrl: img_url,
				success: function (res) {
					msgSuccess("分享成功");
				},
				cancel: function (res) {
				}
			};
			wx.onMenuShareTimeline(shareData);
			wx.onMenuShareAppMessage(shareData);
			wx.onMenuShareQQ(shareData);
			wx.onMenuShareWeibo(shareData);
			wx.hideMenuItems({
				menuList: [
					'menuItem:readMode',
					'menuItem:openWithSafari',
					'menuItem:openWithQQBrowser',
					'menuItem:copyUrl'
				]
			});
		});
	</script>
</head>
<body>
	<div class="bigbox">
		<div class="head clearfix">
			<span><font class="on">会员加盟</font></span>
			<span><a href="ddjx.php?id=455">店铺加盟</a></span>
		</div>
		<div class="botbox clearfix">

			<div class="yyou" style="display:block;">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<div class="swiper-slide"><img src="../../img/ug-1.jpg"/></div>
						<div class="swiper-slide"><img src="../../img/ug-2.jpg"/></div>
						<div class="swiper-slide"><img src="../../img/ug-3.jpg"/></div>
					</div>
					<div class="swiper-pagination"></div>
				</div>
				<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/swiper.min.js"></script>
				<script>
					var swiper = new Swiper('.swiper-container', {
						pagination: '.swiper-pagination',
						paginationClickable: true,
						loop: true,
						autoplay : 5000,
						autoplayDisableOnInteraction : true,
					});
				</script>
				<ul class="obg clearfix">
					<?php
					$str='';
					foreach($list as $v) {
						$str.="<li><img src='$cxtPath/".$v['bimg']."'/><div class='riht'>";
						$str.="<h3>".$v['good_name']."</h3>";
						$str.="<span><font>市场价&nbsp;&nbsp;¥</font><b>".$v['price']."</b></span>";
						$str.="<a class='buy' data-id='".$v['id']."'>".$v['ugprice']."购买</a>";
						$str.="</div></li>";
					}
					echo $str;
					?>
				</ul>
			</div>
		</div>
		<div>
			<p style="text-align:center">注意:会员商品不支持退货</p>
		</div>
	</div>
		<script type="text/javascript">
			$(".buy").click(function(){
				var id=$(this).attr("data-id");
				$.ajax({
					type: "POST",
					url: "gmAddOrder.php",
					data: {id:id},
					success: function(data){
						data=eval("("+data+")");
						if(data.status==1){
							location.href="../order/order.php?orderid="+data.order_id;
						}else if(data.status==2){
							msgError("你已经是会员了,不能享受该优惠");
						}else if(data.status==4){
							msgError("该商品库存不足");
						}else{
							msgError("下单失败,请稍后再试");
						}
					}
				});
			});
		</script>
	<?php include '../gony/nav.php'; ?>
	<?php include '../gony/guanzhu.php'; ?>
</body>
</html>