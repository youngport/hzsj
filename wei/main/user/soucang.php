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
$nickname = $_SESSION["nickname"];
$headimgurl = $_SESSION["headimgurl"];
$openid = $_SESSION["openid"];


$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$userArr = $com->getUserDet($openid, $do);

$selyear = date('Y',time())."-".date('m',time());
$sql = "select count(id) as con,sum(pop) as pop from wei_pop where openid='$openid'";
$popArr = $do->selectsql($sql);
$pop = $popArr[0]["pop"];
if($pop == "") $pop = "0.0";
$sql = "select count(order_id) as con from sk_orders where buyer_id='$openid' and order_time>='$selyear-01' and order_time<='$selyear-31'";
$myOrderArr = $do->selectsql($sql);

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
	<title>我的收藏</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v1.6" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
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
	.soucang_ul { width:100%; background:#fff; font-size:12px; color:#555; line-height:18px;}
	.soucang_ul tr{ border-bottom:1px solid #ebebeb;padding: 5px;}
	.soucang_ul .ta_ximg{ width:10%;display:none;}
	.soucang_ul .ta_ximg img{ width:25px;}
	.soucang_ul .ta_dimg{ width:20%;}
	.soucang_ul .ta_dimg img{ width:100%;}
	.soucang_ul .ta_text{ width:55%;}
	.soucang_ul .dele{ width:15%;}

	.soucang_ul .span_img{ display:inline-block; width:30%; background:red;}
	.soucang_ul .span_img img{width:100%}
	.soucang_ul .span_text{ display:inline-block; width:56%;padding:0 2%; height:100%; overflow:hidden;}
	.soucang_ul .span_del{ display:inline-block; width:10%;}
	.soucang_ul a:link{ color:#555;}
	.soucang_ul a:hover{ color:#555;}
	.soucang_ul a:visited{ color:#555;}	

	.sousuotxt{ margin:0 auto; width:300px; }
	.sousuotxt .da{ width:30px; background:#fff; float:left;}
	.sousuotxt .da img{ width:20px; margin:5px;}
	.sousuotxt .text{ width:73%; height:30px; font-size:14px; background:#fff; border:none; color:#555; float:left; padding-right:5px;}
	.sousuotxt .bj{ width:40px;margin:5px; color:#555; float:left;}
	.gw_qujiesuan{ position:fixed; height:45px; width:100%; border-top:1px solid #ebebeb; bottom:0;left:0;right:0; z-index:500;background:#fff;}
	.gw_qujiesuan tr{ border-top:1px solid #ebebeb;}
	.gw_qujiesuan .zuo{ width:50%; font-size:12px;}
	.gw_qujiesuan .zuo img{ width:18px; margin-right:5px;}
	.gw_qujiesuan .you{ width:50%; font-size:12px;}
	.gw_qujiesuan .you img{ width:16px; margin-right:5px;}
	.nav{border-bottom:5px solid rgb(206, 206, 206);float:left;width:100%}
	.nav li{float:left;width:50%;padding:10px 0px;text-align:center;font-size:16px;}
</style>
</head>
<body id="bodyk" style="margin:0px;padding:0px;background:#fff">
<div style="background:#cecece;height:40px;padding-top:10px;display:none;" id="sousid">
<div class="sousuotxt">
	<div class="da" ><img src="<?php echo $cxtPath; ?>/wei/img/sc_fd.png"/></div>
		<form method="post" id="searchProductForm">
		     <input placeholder="搜索收藏品" class="text" name="productName" id="productName" type="search">
		</form>
	<img class="bj" src="<?php echo $cxtPath; ?>/wei/img/sc_bj.png"/>
	<div style="clear:both;height:0;"></div>
</div>
</div>
<div id='nav'>
	<ul class='nav'>
		<li style='border-right:1px solid rgb(206, 206, 206);margin-left:-1px;border-bottom:1px solid red'>商品<font></font></li>
		<li>文章<font></font></li>
	</ul>
</div>
<table class="soucang_ul" id="soucang_ul">
</table>
<table class="soucang_ul" id="soucang_ul2" style='display:none'>
</table>


<table class="gw_qujiesuan" style='display:none'>
	<tr align="center">
		<td class="zuo"><img data-d="0" id="qxd" src='<?php echo $cxtPath; ?>/wei/img/sc_qx1.png' />全选</td>
		<td class="you"><img id='sub' src='<?php echo $cxtPath; ?>/wei/img/sc_lj.png' /></td>
	</tr>
</table>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
<script type="text/javascript">
$(function(){
	get(0);
	get(1);
});
//function sousuo(){
//	$.ajax({
//		type: "POST",
//		url: "soucangget.php",
//		data: {"sous":$('#productName').val()},
//		success: function(data){
//			$("#soucang_ul").empty();
//			data = eval("("+data+")");
//			if(parseInt(data.total)>0){
//				$('#sousid').css('display','block');
//				var root = data.root;
//				for(var i=0;i<root.length;i++){
//					appStr = "<tr align=\"center\">";
//					appStr += "<td class=\"ta_ximg\"><img class=\"xuanz\" data-x=\"1\" data-i=\""+root[i].id+"\" src='<?php echo $cxtPath ?>/wei/img/gw_xuanz2.png' /></td>";
//					appStr += "<td class=\"ta_dimg\"><img src=\"<?php echo $cxtPath ?>/"+root[i].img+"\"/></td>";
//					appStr += "<td class=\"ta_text\"><a href=\"<?php echo $cxtPath ?>/wei/main/goods/goods.php?id="+root[i].id+"\">"+root[i].good_name+"</a></td>";
//					appStr += "<td class=\"ta_dele\"><span data-del=\""+root[i].id+"\">删除</span></td>";
//					appStr += "</tr>";
//					$("#soucang_ul").append(appStr);
//				}
//				bangd();
//				$('.span_del').click(function(){
//					dele($(this).attr("data-del"));
//				});
//			}else{
//				$("#bodyk .gw_qujiesuan").remove(); 
//				appStr = "<tr align=\"center\" height=\"50\"><td>没有搜索到收藏品~</td></tr>";
//				$("#soucang_ul").append(appStr);
//			}
//		}
//	});
//	return false;
//};
function get(type,cz){
	$.ajax({
		type: "POST",
		url: "soucangget.php",
		data: {type:type,cz:cz},
		success: function(data){
			data = eval("("+data+")");
			var ys='';
			if(type==1)ys='#soucang_ul2';else ys='#soucang_ul';
			$(ys).empty();
			if(data&&data.length>0){
				$('#sousid').css('display','block');
				$('ul.nav li font:eq('+type+')').html('(<span>'+data.length+'</span>)');
				var appStr='';
				for(var i=0;i<data.length;i++){
					//appStr = "<li><a href=\"<?php echo $cxtPath ?>/wei/main/goods/goods.php?id="+root[i].id+"\"><span class=\"span_img\"><img src=\"<?php echo $cxtPath ?>/"+root[i].img+"\"/></span><span class=\"span_text\">"+root[i].good_name+"</span></a><span class=\"span_del\" data-del=\""+root[i].id+"\">删除</span></li>";
					if(type==1){
						if(data[i].wei_nickname){
							appStr+="<tr align=\"center\" style='border:0'>";
							appStr+="<td class=\"ta_ximg\" rowspan='2' style='border-bottom:1px solid #ebebeb;'><img class='xuanz' data-x='1' data-i='"+data[i].sid+"' src='<?php echo $cxtPath ?>/wei/img/gw_xuanz1.png'  onclick='zx(this)'/></td>";
							appStr+="<td width=5% rowspan='2' class='z' style='border-bottom:1px solid #ebebeb;'></td>";
							appStr+="<td width=8% valign='top' rowspan='2' style='padding-top:0.7em;border-bottom:1px solid #ebebeb;'><img src='"+data[i].headimgurl+"' style='width:100%;border-radius:50%;'/></td>";
							appStr+="<td align='left' colspan='2' style='padding-top:0.7em'><span>&nbsp;&nbsp;"+data[i].wei_nickname+"</span><br/><h1 style='color:rgb(206, 206, 206)'>&nbsp;&nbsp;"+data[i].shijian+"</h1></td>";
							appStr+="</tr>";
							appStr+= "<tr align=\"center\">";
							appStr+="<td width=17%><a href='<?php echo $cxtPath ?>/wei/main/lefenxiang/lfx_xiangqin.php?spid="+data[i].id+"'>"+"<img src='<?php echo $cxtPath ?>/"+data[i].img+"' style='width:100%;display:block;max-height:7em;padding-bottom:10px'/></a></td>";
							appStr+="<td align='left' valign='top'><h1 style='padding-left:0.7em;line-height:1.5em;font-size:1.2em'><a href='<?php echo $cxtPath ?>/wei/main/lefenxiang/lfx_xiangqin.php?spid="+data[i].id+"'>"+data[i].title+"</a></h1><p style='padding-left:0.7em;'>"+data[i].abst+"</p></td>";
							appStr+="<td width=5% class='z'> </td>";
							appStr+="</tr>";
						}else {
							appStr += "<tr align=\"center\">";
							appStr += "<td class=\"ta_ximg\"><img class=\"xuanz\" data-x=\"1\" data-i=\""+data[i].sid+"\" src='<?php echo $cxtPath ?>/wei/img/gw_xuanz1.png' onclick='zx(this)'/></td>";
							appStr +="<td width=5% class='z'></td>";
							appStr += "<td class=\"ta_dimg\" style='width:30%' colspan='2'><a href='<?php echo $cxtPath ?>/wei/main/members/xiaoxi_content.php?id="+data[i].id+"'><img src=\"<?php echo $cxtPath ?>/"+data[i].img+"\" style='display:block;width:100%;height:5em;margin:15px 0px' /></a></td>";
							appStr+="<td align='left'><h1 style='padding-left:0.7em;line-height:1.5em;font-size:1.2em'><a href='<?php echo $cxtPath ?>/wei/main/members/xiaoxi_content.php?id="+data[i].id+"'>"+data[i].title+"</a></h1><p style='padding-left:0.7em;'>"+data[i].abst+"</p></td>";
							appStr+="<td width=5% class='z'> </td>";
							appStr += "</tr>";
						}
					}else if(type==0){
						appStr += "<tr align=\"center\">";
						appStr += "<td class=\"ta_ximg\"><img class=\"xuanz\" data-x=\"1\" data-i=\""+data[i].sid+"\" src='<?php echo $cxtPath ?>/wei/img/gw_xuanz1.png' onclick='zx(this)'/></td>";
						appStr += "<td class=\"ta_dimg\"><img src=\"<?php echo $cxtPath ?>/"+data[i].img+"\"/></td>";
						appStr += "<td class=\"ta_text\"><a href=\"<?php echo $cxtPath ?>/wei/main/goods/goods.php?id="+data[i].id+"\">"+data[i].good_name+"</a></td>";
						appStr += "<td class=\"ta_dele\"><span class=\"deldan\" data-del=\""+data[i].sid+"\">删除</span></td>";
						appStr += "</tr>";
					} 
				}
				$(ys).html(appStr);
				$('.deldan').click(function(){
					dele(1,$(this).attr("data-del"),$(this));
				});
//					$("#bodyk .gw_qujiesuan").remove(); 
//					appStr = "<table class=\"gw_qujiesuan\">";
//					appStr += "<tr align=\"center\">";
//					appStr += "<td class=\"zuo\"><img data-d=\"1\" id=\"qxd\" src='<?php echo $cxtPath; ?>/wei/img/sc_qx2.png' />全选</td>";
//					appStr += "<td class=\"you\"><img onclick=\"deles()\" src='<?php echo $cxtPath; ?>/wei/img/sc_lj.png' /></td>";
//					appStr += "</tr>";
//					appStr += "</table>";
//					$("#bodyk").append(appStr);
			}
		}
	});
}
function zx(info){
	info=$(info);
	if(info.attr('data-x')==1){
		info.attr('src','<?php echo $cxtPath ?>/wei/img/gw_xuanz2.png');
		info.attr('data-x',2);
	}else {
		info.attr('src','<?php echo $cxtPath ?>/wei/img/gw_xuanz1.png');
		info.attr('data-x',1);
	}
}
function dele(type,id,info){
	$.ajax({
		type: "POST",
		url: "soucangget.php",
		data: {id:id,cz:'del'},
		success: function(data){
			if(data=='ok'){
				if(type==1){
					var table=info.parents('table').attr('id');
					info.parents('tr').remove();
					if(table=='soucang_ul')table='.nav li:eq(0) span';else table='.nav li:eq(1) span';
					if($(table).text()-1<0)
						$(table).parent().empty();
					else
					$(table).text($(table).text()-1);
				}else if(type==2)location.reload();
			}else if(data=='no')alert('删除失败');
		}
	});
}
$('#qxd').click(function(){
	var cz='';
	if($('#soucang_ul').is(':hidden'))cz='#soucang_ul2 .xuanz';else cz='#soucang_ul .xuanz';
	if($(this).attr('data-d')==1){
		$(this).attr('src','<?php echo $cxtPath; ?>/wei/img/sc_qx1.png');
		$(this).attr('data-d',0);
		$(cz).attr('data-x',0);
		$(cz).attr('src','<?php echo $cxtPath ?>/wei/img/gw_xuanz1.png');
	}else{
		$(this).attr('src','<?php echo $cxtPath; ?>/wei/img/sc_qx2.png');
		$(this).attr('data-d',1);
		$(cz).attr('data-x',1);
		$(cz).attr('src','<?php echo $cxtPath ?>/wei/img/gw_xuanz2.png');
	}
})
$('#sub').click(function(){
	var cz='';
	var id={};
	if($('#soucang_ul').is(':hidden'))cz='#soucang_ul2 .xuanz';else cz='#soucang_ul .xuanz';
	var data=$(cz);
	for(var i=0;i<data.length;i++){
		if(data.eq(i).attr('data-x')==2){
			id[i]=data.eq(i).attr('data-i');
		}
	}
	dele(2,id);
});
$('.nav li:eq(0)').click(function(){
	$('#soucang_ul').show();
	$(this).css('border-bottom','1px solid red');
	$(this).next().css('border-bottom','');
	$('#soucang_ul2').hide();
});
$('.nav li:eq(1)').click(function(){
	$('#soucang_ul2').show();
	$(this).css('border-bottom','1px solid red');
	$(this).prev().css('border-bottom','');
	$('#soucang_ul').hide();
});
$('.bj').click(function(){
	$('td.ta_ximg').toggle();
	$('table.gw_qujiesuan').toggle();
	$('td.z').toggle();
	$('td.ta_dele').toggle();
});
$('#productName').blur(function(){
	var sou=$(this).val();
	var ys='';
	if($('#soucang_ul').is(':hidden')){
		get(1,sou);
		ys='#soucang_ul2';
		cz='.nav li:eq(1) span';
	}else{ 
		get(0,sou);
		ys='#soucang_ul';
		cz='.nav li:eq(0) span';
	}
	if($(ys).find('td')){
		$(cz).parent().empty();
	}
});
</script>
</body>
</html>