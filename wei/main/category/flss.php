<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$cartnum = $_SESSION["cartnum"];
$productName = addslashes($_POST["productName"]);
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];


$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl = 'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>分类搜索</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/home.css"/>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
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
	paix("pp");

	$('#jiage').click(function(){
		$('.paix_ul li').css("border-color","#EBEBEB");
		$(this).css("border-color","#900");
		paix("fl");
	});
	$('#renqi').click(function(){
		$('.paix_ul li').css("border-color","#EBEBEB");
		$(this).css("border-color","#900");
		paix("pp");
	});
	$('#xiaoliang').click(function(){
		$('.paix_ul li').css("border-color","#EBEBEB");
		$(this).css("border-color","#900");
		paix("gx");
	});
});
function paix(fenhtml){
	$.ajax({
		type: "POST",
		url: "flssget.php",
		data: {fen:fenhtml},
		success: function(data){
			var data = eval("("+data+")");
			var appStr = "";
			var shopStr = "";
			$("#viewport").empty();
			for(var i=0;i<data.length;i++){
				appStr = "<div class=\"flss_qu\"><a href='./flss_xq.php?quid="+data[i].id+"&quname="+data[i].name+"'>"+data[i].name+"</div>";
				var shop = data[i].shop;
				if(shop.length>0){
					shopStr = "<div class=\"flss_lei\">";
					for(var j=0;j<shop.length;j++){
						shopStr += "<span><a href='./flss_xq.php?quid="+data[i].id+"&"+shop[j].fen+"="+shop[j].id+"'>"+shop[j].name+"</a></span>";
					}
					shopStr += "</div>";
				}else{
					shopStr = "";
				}
				$("#viewport").append(appStr + shopStr );
			}
		}
	});
};
</script>
<style>
	.flss{ border-left:1px solid #cecece; display:inline-block; margin-left:10px; padding-left:10px;font-family:'微软雅黑'; font-size:16px;}
    .index_daohang{ height:35px; background:#999; margin:0; padding:0;}
	.index_daohang_z{ width:100%; display:none; background:#fff;}
	.index_daohang_z ul li { border-bottom:1px solid #ececec; padding:5px 0 5px 10px;}
	.index_daohang_z ul li span{ display:inline-block; padding:7px 5px; color:#555; font-size:12px;}
	
	.yijidaoh { font-size:16px;}
	.yijidaoh a:link{ color:#333; font-size:16px;}
	.yijidaoh a:hover{ color:#333; font-size:16px;}
	.yijidaoh a:visited{ color:#333; font-size:16px;}
	.daoh_tubiao{ display: inline-block; background:url("../../img/shouye.png") no-repeat;float:right;width:35px;height:24px;}

	.paix_ul{ width:99%; margin:0 auto; height:30px; line-height:30px; text-align:center; font-size:14px;}
	.paix_ul li{ float:left; width:33%; height:30px; border-bottom:2px solid #EBEBEB;}

	.flss_qu{ font-size:16px; text-indent:10px; height:35px; line-height:35px;}
	.flss_lei{ border:1px solid #ececec; padding:5px 0 5px 0px;}
	.flss_lei span{display:inline-block; padding:7px 10px 7px 15px; color:#555; font-size:12px;}
	.fanhuisouye{ font-size:14px; height:30px; line-height:30px; text-indent:10px; border:1px solid #fff;}
	.fanhuisouye a:link{ color:#666;}
	.fanhuisouye a:hover{ color:#666;}
	.fanhuisouye a:visited{ color:#666;}
	.flss_qu a:link{ color:#333; font-size:14px;}
	.flss_qu a:hover{ color:#333; font-size:14px;}
	.flss_qu a:visited{ color:#333; font-size:14px;}
	.flss_lei a:link{ color:#555; font-size:12px;}
	.flss_lei a:hover{ color:#555; font-size:12px;}
	.flss_lei a:visited{ color:#555; font-size:12px;}
	.flss_ssk{ width:90%; margin:10px auto; height:30px; border:1px solid #fff;}
	.flss_ssk .search_text{ width:100%; height:30px; background:#fff; color:#555; margin:0 auto;}	
</style>
</head>
<body style="background:#EBEBEB;">
<!-- <div class="fanhuisouye"><a href="<?php echo $cxtPath ?>/wei/index.php"><返回首页</a></div> -->
<form action="./flss_xq.php" method="post" id="searchProductForm">
<div class="flss_ssk">
     <input placeholder="请输入搜索词" class="search_text" name="productName" id="productName" type="search">
</div>
</form>
<ul class="paix_ul">
	<li id="renqi" style="border-bottom:2px solid #900;" >品牌</li>
	<li id="jiage" >分类</li>
	<li id="xiaoliang" >功效</li>
</ul>
<div style="clear:both;float:none;"></div>
<div id="viewport" class="viewport">
	<!--<div class="flss_qu">母婴区</div>
	<div class="flss_lei"><span>都能康</span><span>都能健康</span><span>都能康</span><span>都能康</span></div>
	<div class="flss_qu">母婴区</div>
	<div class="flss_lei"><span>都能健康</span><span>都健康</span><span>都能健康</span><span>能健康</span></div>-->
	</div>

<?php include '../gony/nav.php';?>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
<div style="clear:both;height:90px;"></div>
</body>
</html>