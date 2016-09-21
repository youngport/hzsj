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
$subscribe = $_SESSION["subscribe"];

$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
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
	<title>商品分类</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
	<style>
		ul,li{ margin:0;padding:0;}
		.fenli_li{ height:32px; border-bottom:1px solid #cecece; color:#920; line-height:32px; font-size:14px; text-indent:30px}; 
		.fenl_img{ background:url(../../img/fenl_xian.png) no-repeat; display:inline-block; width:55px; height:35px;}
	</style>
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
	$.ajax({
		type: "POST",
		url: "categorySearch.php",
		data: "",
		success: function(data){
			var data = eval("("+data+")");
			var appStr = "";
			for(var i=0;i<data.length;i++){
				appStr = "<div class='taodiv' style='position:relative;width:100%;height:3em;line-height:3em;background:white;font-size:1.5em;border-bottom:1px solid #E5E5E5'>";
				appStr += "<span style='margin-left:1em;float:left;'>"+data[i].name+"</span>";
				//appStr += "<img src='<?php echo $cxtPath ?>/wei/images/rightjian.png' style='margin-top:0.8em;margin-right:1em;float:right;'></img>";
				appStr += "</div>";
				var appq = data[i].chmenu;
				if(appq.length>0){
					var appqtext = "<ul style='display:none;'>";
					for(var j=0;j<appq.length;j++){
						appqtext += "<a href='categoryGoods.php?categoryid="+appq[j].id+"&categoryname="+appq[j].name+"'><li class='fenli_li'>"+appq[j].name+"</li></a>";
					};
					appqtext += "</ul>";
				}
				appStr += appqtext;
				appqtext = "";
				$("#category").append(appStr);
			}
			
			$('.taodiv').click(function(){
				$(this).next('ul').slideToggle();
			})
		}
	});
});
function forwardUrl(url){
	window.open(url);
}
function search(){
	var searchtext = $("#searchtext").val();
	
	if(searchtext != ""){
		window.open("categoryShGoods.php?searchtext="+searchtext);
	}	
}
</script>
</head>
<body style="margin:0px;padding:0px;background:#EBEBEB;">
<div style="width:100%;height:3em;padding:1em; margin-bottom:15px;">
	<input id="searchtext" type="text" style="width:80%;height:2em;font-size:1.5em;" placeholder="搜索" />
	<img onclick="search()" src="<?php echo $cxtPath ?>/wei/images/title_btn_search.png" style="margin-left:10px;">
</div>
<div id="category" style="width:100%;">
</div>
<?php include '../gony/nav.php';?>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
</body>
</html>