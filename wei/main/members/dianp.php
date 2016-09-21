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
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>线下店铺定位</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v1.6" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=quick&ak=kZwQwUE6HuzAYw6Nzndrev2q"></script>
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
#allmap{ width:100%;height:300px;}
#dianp{ width:98%; border:1px solid #f4f4f4;margin:10px auto;}
#dianp tr{ width:98%; margin:8px 1%; border-bottom:1px solid #f4f4f4; font-size:14px;line-height:25px;}
#dianp td{ padding:5px;}
#dianp .dp_bianhao{ width:40%;}
#dianp .dp_name{ width:40%;}
#dianp .bianji{ width:20%;}
</style>
</head>
<body style="margin:0px;padding:0px;background:#fff;">
<div id="allmap"></div>
<!--<div><input placeholder="请输入该地址信息（如店铺名称、详细地址）" class="text" name="shouji" id="shouji" type="search"></div>-->
<table id="dianp">

</table>
<div style="height:80px;"></div>
<?php include '../gony/nav.php';?>
<?php include './main/gony/returntop.php';?>
</body>
<script>
var map = new BMap.Map("allmap");        
//map.centerAndZoom(new BMap.Point(116.4035,39.915),10);
//map.centerAndZoom('深圳', 5);     
//map.addControl(new BMap.ZoomControl()); 
var true_false = true;
$.ajax({
	type: "POST",
	url: "dianpget.php",
	data: {},
	success: function(data){
		//$("#dianp").empty();
		var data = eval("("+data+")");
		var appStr = "";
		if(data.length>0){
			for(var i=0;i<data.length;i++){
				if(data[i].zuobiao!=""&&data[i].zuobiao!=null){
					var str= data[i].zuobiao;
					var strs= new Array();
					strs=str.split(",");
					var point = new BMap.Point(strs[0], strs[1]);
					addMarker(point, data[i].xxdizhi);
					if(true_false){
					    map.centerAndZoom(point, 10);
					    true_false = false;
					}
				}else if(true_false==true&&i==data.length-1)
				    map.centerAndZoom(new BMap.Point(116.404, 39.915), 10);
				var nam = data[i].dianpname;
				if(nam == "" || nam == null)
					nam = "";
				appStr +="<tr align=\"center\"><td class=\"dp_bianhao\">"+data[i].bianhao+"</td><td class=\"dp_name\">"+nam+"</td><td class=\"bianji\"><a href=\"./dianp_bj.php?dpi="+data[i].id+"\">编辑</a><td></tr>";
			}
		}else
			appStr +="<li style=\"text-align:center;font-size:14px;line-height:100px;color:#888;\">暂无私人店铺信息</li>";
		$("#dianp").append(appStr);
	}
});
/* var createMark = function(lng, lat, info_html) {  
    var _marker = new BMap.Marker(new BMap.Point(lng, lat));  
    _marker.addEventListener("click", function(e) {  
        this.openInfoWindow(new BMap.InfoWindow(info_html));  
    });  
    map.addOverlay(_marker)
    //return _marker;  
};  */ 
function addMarker(point,info_html){
	  var marker = new BMap.Marker(point);
	  marker.addEventListener("click", function(e) {
	      this.openInfoWindow(new BMap.InfoWindow(info_html));  
	  });  
	  map.addOverlay(marker);
	}
</script>
</html>