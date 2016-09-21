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
$sql = "select id,bianhao,zuobiao,xxdizhi,dianpname,dianp_num,dianp_img,dianp_img2 from sk_erweima where id=".intval($_GET['dpi']);
$resultArr = $do->getone($sql);
//print_r($resultArr);exit;
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
	<title>线下店铺定位</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v1.6" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=quick&ak=kZwQwUE6HuzAYw6Nzndrev2q"></script>
	<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
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
.search{border:0;background:#fff url(../../css/img/search.png) no-repeat scroll right;background-size:60%;padding-left:2em;}
.dw{background:url(../../css/img/dw.png) no-repeat left;padding-left:1.5em;background-size:13%;}
#for input {border:0;box-shadow:inset 0 0px 0px rgba(0,0,0,0)}
.upload div{width:24%;position:relative;margin:0 3%;}
.ts{width:20%;background:#fff;position:absolute;top:50%;left:40%;z-index:111;display:none;}
.zb{width:100%;height:100%;position:absolute;left:0;top:0;bottom:0;right:0;background:#666;z-index:100;opacity: 0.5;filter: alpha(opacity=50);-moz-opacity: 0.5;display:none}
.xian{width:50%;height:0.1em;background:red;position:absolute;top:46%;left:-50%;margin:0;border:0;}
</style>
</head>
<body>
<div id="allmap"></div>
<div id="r-result"></div>
<div class="container-fluid" style="padding:0px;background:#EDEDED">
	<div class="input-group container-fluid" style="padding:0.5em 15px;background:#F3575B">
		<span class="input-group-addon search" id="basic-addon1"></span>
		<input type="text" class="form-control address" placeholder="请输入搜索地址" aria-describedby="basic-addon1" style="border:0;">
	</div>
	<div style="margin:0.5em 0px;background:#fff">
		<ul class="text-center center-block dwview" style="width:50%">
			<li class="dw"><h4 style="margin:0;padding:0.7em 0em;color:#F87F7F">定位到当前位置</h4></li>
		</ul>
	</div>
	<div>
		<form id="for" action="" method="post" enctype="multipart/form-data" >
			<table class="table text-center" style="background:#fff">
				<tr>
					<td><h4>店铺名称</h4></td>
					<td><input type="text" name="dianp_name" id="dianp_name" placeholder="请如实填写以便审核" class="form-control" value="<?php echo $resultArr['dianpname'];?>"/></td>
				</tr>
				<tr>
					<td><h4>手机号码</h4></td>
					<td><input type="text" name="dianp_num" id="dianp_num" placeholder="请如实填写以便审核" class="form-control" value="<?php echo $resultArr['dianp_num'];?>"/></td>
				</tr>
				<tr>
					<td><h4>店铺地址</h4></td>
					<td><input type="text" name="dianp_xxdizhi" id="dianp_xxdizhi" placeholder="请如实填写以便审核" class="form-control" value="<?php echo $resultArr['xxdizhi'];?>"/></td>
				</tr>
			</table>
			<div class="container-fluid upload">
				<div class="img pull-left">
					<?php
						if($resultArr['dianp_img']!='')
							echo "<img src='".$resultArr['dianp_img']."' style='width:100%;display:block';max-height:200px/>";
						else
							echo "<img src='../../css/img/img.png' class='img-responsive'/>";
					?>
					<input type="file" name="dianp_img" id="dianp_img" style="opacity: 0;position:absolute;left:0;top:0;width:100%;">
				</div>
				<div class="img pull-left">
					<?php
					if($resultArr['dianp_img2']!='')
						echo "<img src='".$resultArr['dianp_img2']."' style='width:100%;display:block';max-height:200px/>";
					else
						echo "<img src='../../css/img/img.png' class='img-responsive'/>";
					?>
					<input type="file" name="dianp_img2" style="opacity: 0;position:absolute;left:0;top:0;width:100%;">
				</div>
				<div class="pull-left" style="margin-top:8%;width:26%">
					<hr class="xian"/>
					<h5 class="pull-left">上传店铺照片</h5>
				</div>
			</div>
			<div class="text-center center-block" style="margin-top:1em;">
				<input type="submit" class="btn btn-danger" value="保存" style="width:40%"/>
				<p style="margin-top:0.5em;">注意：每修改一次都将要通过审核才能公布该体验店信息，操作需谨慎</p>
			</div>
		</form>
	</div>
</div>
<div class="text-center ts">
	<img src="../../images/load.gif" style="width:40px;height:40px;"/>
	<p>正在保存中</p>
</div>
<div class="zb"></div>
</body>
<script>
var map = new BMap.Map("allmap");        
//map.centerAndZoom(new BMap.Point(116.4035,39.915),10);
//map.centerAndZoom('北京', 12);     
//map.addControl(new BMap.ZoomControl());
var x="<?php echo $resultArr['zuobiao'];?>";
var id="<?php echo $resultArr['id'];?>";
if(x!=""&&x!=null){
	var strs= new Array();
	strs=x.split(",")
	var xx = 0;
	var yy = 0;
	if(strs[0]!=null)
		xx = strs[0];
	else
		xx = 116.404;
	if(strs[1]!=null)
		yy = strs[1];
	else
		yy = 39.915;
	point = new BMap.Point(xx,yy);
	var marker = new BMap.Marker(point);
	map.addOverlay(marker);
	map.centerAndZoom(point,12);
}else{
	var point = new BMap.Point(116.404, 39.915);
	map.centerAndZoom(point, 8);
}
$('#for').attr("action","dianp_bjupdate123.php?id="+id+"&zuobiao="+x);

//map.centerAndZoom(new BMap.Point(116.4035,39.915),12);

var marker1 = new BMap.Marker();  //创建标注

function showInfo(e){
	//map.removeOverlay(marker1);//移除指定覆盖物
	map.clearOverlays();//移除所有覆盖物
	x = e.point.lng + "," + e.point.lat;
	marker1 = new BMap.Marker(new BMap.Point(e.point.lng, e.point.lat));  //创建标注
	map.addOverlay(marker1);                 // 将标注添加到地图中
	var pt = e.point;
	var geoc = new BMap.Geocoder();    
	geoc.getLocation(pt, function(rs){
		var addComp = rs.addressComponents;
		$('#dianp_xxdizhi').val(addComp.province + addComp.city + addComp.district  + addComp.street + addComp.streetNumber);

		$('#for').attr("action","dianp_bjupdate123.php?id="+id+"&zuobiao="+x);
	});
}
map.addEventListener("click", showInfo);
$('#update').click(function(){
	$.ajax({
		type: "POST",
		url: "dianp_bjupdate123.php",
		data: {dpi:<?php echo $_GET['dpi']?>,name:$('#dianp_name').val(),xxdizhi:$('#dianp_xxdizhi').val(),zuobiao:x},
		success: function(data){
			if(data==1){
				alert('保存成功！');
	        	window.location.href=document.referrer;
			}
		}
	});
})
$('.search').click(function(){
	if($('.address').val()!="") {
		map.centerAndZoom($('.address').val(), 12);
		var local = new BMap.LocalSearch(map, {
			renderOptions: {map: map, panel: "r-result",autoViewport: true},pageCapacity: 5
		});
		local.search($('.address').val());
	}else
		alert('不能为空');
});
$('.dwview').click(function(){
	var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
			var mk = new BMap.Marker(r.point,{
				// 指定Marker的icon属性为Symbol
				icon: new BMap.Symbol(BMap_Symbol_SHAPE_POINT, {
					scale: 1,//图标缩放大小
					fillColor: "Yellow",//填充颜色
					fillOpacity: 1//填充透明度
				})
			});
			map.addOverlay(mk);
			map.centerAndZoom(r.point, 12);
			var pt = r.point;
			var geoc = new BMap.Geocoder();
			geoc.getLocation(pt, function(rs){
				var addComp = rs.addressComponents;
				var tx = addComp.city;
			});
			geoc.getLocation(pt, function(result){
				if (result) {
					var address=result.address;
					var opts = {
						title : "当前位置" , // 信息窗口标题
					}
					var infoWindow = new BMap.InfoWindow(address, opts);
				}
				map.openInfoWindow(infoWindow, pt);
			});
		}
		else {
			alert('failed'+this.getStatus());
		}
	},{enableHighAccuracy: true})
});
$(".dw").css("background-size",$(".dwview").height()*0.5);
$(".table input").focus(function(){
	$(this).css("box-shadow","inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)");
});
$(".table input").blur(function(){
	$(this).css("box-shadow","inset 0 0px 0px rgba(0,0,0,0)");
});
$("input[type='file']").change(function(){
	var reader = new FileReader();
	var file=$(this)[0].files[0];
	var img=$(this).prev();
	reader.onload = (function(theFile) {
		return function(e) {
			img.attr("src",e.target.result);
		};
	})(file);
	reader.readAsDataURL(file);
	$(this).height($(this).parent().height());
});
$("input[type='submit']").click(function(){
	$(".zb").height(document.documentElement.scrollHeight);
	$(".zb").show();
	$(".ts").show();
});
$(".upload input[type='file']").each(function(){
	$(this).prev().css("height",$(this).parent().width());
	$(this).height($(this).parent().height());
});$(".upload input[type='file']").each(function(){
	$(this).prev().css("height",$(this).parent().width());
	$(this).height($(this).parent().height());
});
</script>
</html>