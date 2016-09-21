<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$cartnum = 0;
if(isset($_SESSION["cartnum"])){
	$cartnum = $_SESSION["cartnum"];
}
$openid = $_SESSION["openid"];
if($openid == 'oc4cpuKadXviOYYPCRwrrQ2Xvfds')
    echo "<script>window.location.href='shouyiq.php';</script>";
$subscribe = $_SESSION["subscribe"];

$do = new condbwei();
$com = new commonFun();
$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$openid = $_SESSION["openid"];
$taopenid = addslashes($_GET['shouyi']);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>收益详情</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v1.6" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/css.css" />
<script type="text/javascript">
wx.config({
    appId: '<?php echo $shareCheckArr["appid"];?>',
    timestamp: '<?php echo $shareCheckArr["timestamp"];?>',
    nonceStr: '<?php echo $shareCheckArr["noncestr"];?>',
    signature: '<?php echo $shareCheckArr["signature"];?>',
    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','hideMenuItems'] // 功能列表，我们要使用JS-SDK的什么功能
});
wx.ready(function(){
	var shareData = {
        title: '洋仆淘跨境商城——源自于深圳自贸区的专业跨境电商平台',
        desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
        link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
		imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/logo.png',
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
var cartnum = "<?php echo $cartnum ?>";
$(function(){
	if(parseInt(cartnum)>0){
		$("#ShoppingCartNum").css("display", "block").text(cartnum);
	}
});
</script>
<style>
.shijian{ width:45%;text-align:right; color:red; line-height:25px; font-size:14px; float:left;}
.shijian span{ margin-right:10px;}
.xiangqin{ width:52%; text-indent:10px; line-height:25px; color:#fff; font-size:14px; float:left; position:relative; border-left:2px solid #fff;}
.yuandian{ position:absolute; top:3px; left:-9px; background:#fff; width:16px;height:16px;border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;-o-border-radius:8px;-ms-border-radius:8px;}
.shijianm{ color:red;}
</style>
</head>
<body style="margin:0px;padding:0px;background:url(<?php echo $cxtPath ?>/wei/css/img/atrat.jpg)">
<?php 

/* $sql = "select pop,poptime,from_unixtime(shijianc) shijianc,(select pop from wei_pop where openid='".$openid."' and order_sn=p.order_sn) mypop,
    (select (select wei_nickname from sk_member where sk_orders.buyer_id=sk_member.open_id) wei_nickname,(select sum(quantity) from sk_order_goods where sk_orders.order_id=sk_order_goods.order_id) shuliang from sk_orders where order_sn=p.order_sn) 
    from wei_pop p where openid='".$taopenid."' order by poptime desc"; */
$sql = "select pop,poptime,from_unixtime(shijianc) shijianc,(select pop from wei_pop where openid='".$openid."' and order_sn=p.order_sn) mypop,
    (select (select wei_nickname from sk_member where sk_orders.buyer_id=sk_member.open_id) wei_nickname from sk_orders where sk_orders.order_sn=p.order_sn) name,(select (select sum(quantity) from sk_order_goods where sk_orders.order_id=sk_order_goods.order_id) shuliang from sk_orders where sk_orders.order_sn=p.order_sn) shuliang
    ,(select goods_amount from sk_orders where sk_orders.order_sn=p.order_sn) zongjia from wei_pop p where openid='".$taopenid."' order by poptime desc";
//$sql = "select pop,poptime,(select pop from wei_pop where openid='$openid' and order_sn=p.order_sn) mypop from wei_pop p where openid='$taopenid' order by poptime desc";
$popArr = $do->selectsql($sql);
$sjz_html = "";
if(count($popArr)>0){
    for($i=0;$i<count($popArr);$i++){
    	$sjz_html .= "<div>";
    	//if($popArr[$i]['shijianc']=="1970-01-01 08:00:00")
    		$sjz_html .= "<div class=\"shijian\"><span>".$popArr[$i]['poptime']."</span></div>";
    	//else		
    		//$sjz_html .= "<div class=\"shijian\"><span>".$popArr[$i]['shijianc']."</span></div>";
    	$sjz_html .= "<div class=\"xiangqin\">";
    	$sjz_html .= "<div class=\"yuandian\"></div>";
    	$sjz_html .= "	<div class=\"shijianm\">".$popArr[$i]['name']."  购买了 ".$popArr[$i]['shuliang']." 件商品共计 ".$popArr[$i]['zongjia']." 元</div>";
    	$sjz_html .= "	<div>Ta的收益:".$popArr[$i]['pop']."</div>";
    	if($popArr[$i]['mypop']==null||$popArr[$i]['mypop']=="")
    		$sjz_html .= "	<div>我的收益:0.00</div>";
    	else
    		$sjz_html .= "	<div>我的收益:".$popArr[$i]['mypop']."</div>";
    	$sjz_html .= "</div>";
    	$sjz_html .= "</div>";
    	
    	echo $sjz_html;
    	$sjz_html = "";
    }
}else 
    echo "<div style=\"width:140px;height:40px;line-height:40px;color:#fff;text-align:center;margin:30px auto;font-size:16px;\">暂无收益</div>"
?>
<!--<div>
	<div class="shijian"><span>2015-1231561</span></div>
	<div class="xiangqin">
	<div class="yuandian"></div>
		<div class="shijianm">大事件</div>
		<div>Ta的收益:</div>
		<div>我的收益:</div>
	</div>
</div>
--></body>
</html>