<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	//header("Location:".$cxtPath."/wei/login.php");
	//return;
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
	<title>线下体验店</title>
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
#dianp{ width:98%;margin:0 auto;color:#555;}
#dianp li{ width:98%;border:1px solid #f4f4f4; margin:4px auto; font-size:14px; line-height:20px;}
#dianp li .div{ width:94%;margin:5px auto;}
#dianp tr{ width:98%; margin:8px 1%; border-bottom:1px solid #f4f4f4; font-size:14px;line-height:25px;}
#dianp td{ padding:5px;}
#dianp .dp_bianhao{ width:40%;}
#dianp .dp_name{ width:40%;}
#dianp .bianji{ width:20%;}

#dianp a:link {color:#555;}		
#dianp a:visited {color:#555;}	
#dianp a:hover {color:#555;}	
#dianp a:active {color:#555;}

#dianp li .fujin_yx{width:20%;float:left;margin:5px 2%;}
#dianp li .fujin_yx img{width:100%;border-radius:50%;-moz-border-radius:50%;-webkit-border-radius:50%;-o-border-radius:50%;-ms-border-radius:50%;}
#dianp li .fujin_xx{ width:74%;float:left;}
.ssclick{ background:#fe7182;width:65px;margin-left:-25px;text-align:center;line-height:30px; border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;-o-border-radius:8px;-ms-border-radius:8px;font-size:14px;height:30px;color:#fff;float:left;}
.dianp_ss{ margin:0 0 8px 8px;float:left;background:#fff; width:160px;padding:0 25px 0 5px;border:1px solid #fe7182;border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;-o-border-radius:8px;-ms-border-radius:8px;font-size:14px;height:30px;}
</style>
</head> 
<body style="margin:0px;padding:0px;background:#fff;">
<div></div>
<div id="ddss" style="padding-top:10px;background:#fff;">
	<!-- <div style="font-size:16px;padding:8px;color:#fe7182;float:left;">[<a id="curCityText" href="javascript:void(0)"><span id="xzcs">选择城市</span></a>]</div> -->
	<input placeholder="请手动输入地址" class="dianp_ss" name="sousuodizhi" id="sousuodizhi" type="search">
	<div class="ssclick" id="dizhi_ss">搜索</div>
	<div style="clear:both;"></div>
</div>
	<!--<div class="map_popup" id="cityList" style="display:none;">
		<div class="popup_main">
			<div class="cityList" id="citylist_container"></div>
			<button id="popup_close"></button>
		</div>
	</div>-->
	<div id="dangqianweizhi" style="font-size:14px;border-top:1px solid #fe7182; text-align:center;line-height:40px; color:#fe7182;">定位当前位置</div>
<div id="allmap"></div>
<div style="border-top:2px solid #fe7182"></div>
<div id="jjd" style="font-size:14px;position:fixed;bottom:0;width:100%; line-height:40px;background:#fe7182;text-align:center;">洋仆淘体验店全国分布已有<span style="font-size:22px;color:red;" id="duoshaojia"> </span>家</div>
<!--<div><input placeholder="请输入该地址信息（如店铺名称、详细地址）" class="text" name="shouji" id="shouji" type="search"></div>-->
<div id="ssweizhi" style=" font-size:16px; text-align:center;line-height:30px;"></div>
<ul id="dianp">

</ul>
<div style="height:40px;"></div>
</body>
<script type="text/javascript" src="http://api.map.baidu.com/library/CityList/1.2/src/CityList_min.js"></script>
<script>
$('#allmap').height($(window).height()-132);//128
$('#sousuodizhi').width($(window).width()-100);//180

//alert($(window).height()+","+$('#ddss').height()+","+$('#dangqianweizhi').height()+","+$('#jjd').height()+","+$('#allmap').height());
var map = new BMap.Map("allmap");
//map.centerAndZoom(new BMap.Point(116.4035,39.915),10);
map.centerAndZoom('炎陵县', 6);
//map.addControl(new BMap.ZoomControl()); 
var fujin = [];
$.ajax({
	type: "POST",
	url: "xianxiaget.php",
	data: {},
	success: function(data){
		//$("#dianp").empty();
		var data = eval("("+data+")");
		var appStr = "";
		if(data.length>0){
			$('#duoshaojia').html(data.length);
			for(var i=0;i<data.length;i++){
				if(data[i].zuobiao!=""&&data[i].zuobiao!=null){
					var str= data[i].zuobiao;
					var strs= new Array();
					strs=str.split(",");
					var point = new BMap.Point(strs[0], strs[1]);
					fujin.push(point);
					var opts = {
						title : "<span style='font-size:16px'>"+data[i].dianpname+"</span>", // 信息窗口标题
						enableMessage:true//设置允许信息窗发送短息
					};
					data[i].xxdizhi+="<a href=\"tiyandian_xq.php?id="+data[i].id+"\" style='font-size:1.3em;padding-left:1em'>查看详情</a>";
					addMarker(point,data[i].xxdizhi,opts);
					//var ma = createMark(strs[0], strs[1], data[i].xxdizhi);
			        //map.addOverlay(ma);
				}
				//var nam = data[i].dianpname;
				//if(nam == "" || nam == null)
					//nam = "";
				//appStr +="<tr align=\"center\"><td class=\"dp_bianhao\">"+data[i].bianhao+"</td><td class=\"dp_name\">"+nam+"</td><td class=\"bianji\"><a href=\"./dianp_bj.php?dpi="+data[i].id+"\">编辑</a><td></tr>";
			}
		}else
			//appStr +="<li style=\"text-align:center;font-size:14px;line-height:100px;color:#888;\">暂无私人店铺信息</li>";
		$("#dianp").append(appStr);
	}
});

function addMarker(point,info_html,opts){
	  var marker = new BMap.Marker(point);
	  marker.addEventListener("click", function(e) {
	      this.openInfoWindow(new BMap.InfoWindow(info_html,opts));
	  });  
	  map.addOverlay(marker);
	}
	// 随机向地图添加25个标注
	/*var bounds = map.getBounds();
	var sw = bounds.getSouthWest();
	var ne = bounds.getNorthEast();
	var lngSpan = Math.abs(sw.lng - ne.lng);
	var latSpan = Math.abs(ne.lat - sw.lat);
	for (var i = 0; i < 25; i ++) {
		var point = new BMap.Point(sw.lng + lngSpan * (Math.random() * 0.7), ne.lat - latSpan * (Math.random() * 0.7));
		addMarker(point);
	}*/
var createMark = function(lng, lat, info_html) {  
    var _marker = new BMap.Marker(new BMap.Point(lng, lat));  
    _marker.addEventListener("click", function(e) {  
        this.openInfoWindow(new BMap.InfoWindow(info_html));  
    });  
    //_marker.addEventListener("mouseover", function(e) {  
     //   this.setTitle("位于: " + lng + "," + lat);  
    //});  
    return _marker;  
};  





/*var mOption = {
	    poiRadius : 500,           //半径为1000米内的POI,默认100米
	    numPois : 3                //列举出50个POI,默认10个
	}*/

$('#dangqianweizhi').click(function(){
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

			//var myGeo = new BMap.Geocoder(); //创建解析实例
			//map.addOverlay(new BMap.Circle(r.point,1500,{fillColor:"blue", strokeWeight: 1 ,fillOpacity: 0.3, strokeOpacity: 0.3}));        //添加一个半径1500圆形覆盖物


			var pt = r.point;
			var geoc = new BMap.Geocoder();    
			geoc.getLocation(pt, function(rs){
				var addComp = rs.addressComponents;
				var tx = addComp.city;
				//document.getElementById("cityList").style.display = "none";//隐藏各个城市
				//map.centerAndZoom(tx,12);
				$('#ssweizhi').html(tx+"的店铺加盟信息");
				ssdianpu(tx,"");

				$('#sousuodizhi').val(tx);
			});
		   /* myGeo.getLocation(r.point,
		        function mCallback(rs){
		            var allPois = rs.surroundingPois;       //获取全部POI（该点半径为100米内有6个POI点）
		            for(i=0;i<allPois.length;++i){
		                //document.getElementById("panel").innerHTML += "<p style='font-size:12px;'>" + (i+1) + "、" + allPois[i].title + ",地址:" + allPois[i].address + "</p>";
		                alert(allPois[i].title + ",地址:" + allPois[i].address);
		                //map.addOverlay(new BMap.Marker(allPois[i].point));                
		            }
		        },mOption
		    );*/


			
		}
		else {
			alert('failed'+this.getStatus());
		}        
	},{enableHighAccuracy: true})
})

function ssdianpu(cs,mc){
	$.ajax({
		type: "POST",
		url: "ssdianpu.php",
		data: {cs:cs,mc:mc},
		success: function(data){
			$("#dianp").empty();
			var data = eval("("+data+")");
			var appStr = "";
			if(data.length>0){
				for(var i=0;i<data.length;i++){
					appStr += "<a href=\"tiyandian_xq.php?id="+data[i].id+"\"><li><div class=\"fujin_yx\"><img src=\""+data[i].headimgurl+"\" /></div><div class=\"fujin_xx\"><div class=\"div\" style=\"border-bottom:1px solid #f4f4f4;\">店铺名："+data[i].dianpname+"</div><div class=\"div\">地址："+data[i].xxdizhi+"</div></div><div style=\"clear:both;float:none;height:0;\"></div></li></a>";
				}
			}else
				appStr = "<li style=\"text-align:center;line-height:30px;font-size:14px;color:#888;\">该地址暂无店铺加盟</li>";
			$("#dianp").append(appStr);
			$("html, body").animate({
				scrollTop: 90
			}, 500);
		}
	});
}
$('#dizhi_ss').click(function(){
	//document.getElementById("cityList").style.display = "none";//隐藏各个城市列表
	map.centerAndZoom($('#sousuodizhi').val(),12);
	$('#ssweizhi').html($('#sousuodizhi').val()+"的店铺加盟信息");
	ssdianpu($('#sousuodizhi').val(),"");
})



// 创建CityList对象，并放在citylist_container节点内
var myCl = new BMapLib.CityList({container : "citylist_container", map : map});

// 给城市点击时，添加相关操作
myCl.addEventListener("cityclick", function(e) {
	// 修改当前城市显示
	//document.getElementById("curCity").innerHTML = e.name;
	// 点击后隐藏城市列表
	document.getElementById("cityList").style.display = "none";
	$('#ssweizhi').html(e.name+"市的店铺加盟信息");
	//$('#xzcs').html(e.name+"市");
	$('#sousuodizhi').val(e.name);
	ssdianpu(e.name,"");
});
// 给“更换城市”链接添加点击操作
document.getElementById("curCityText").onclick = function() {
	var cl = document.getElementById("cityList");
	if (cl.style.display == "none") {
		cl.style.display = "";
	} else {
		cl.style.display = "none";
	}	
};
// 给城市列表上的关闭按钮添加点击操作
document.getElementById("popup_close").onclick = function() {
	var cl = document.getElementById("cityList");
	if (cl.style.display == "none") {
		cl.style.display = "";
	} else {
		cl.style.display = "none";
	}
};
</script>
</html>