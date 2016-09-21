<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION["access_token"])){
	//header("Location:".$cxtPath."/wei/login.php");
	//return;
}


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
	<title>评论商品</title>
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

$.ajax({
	type: "POST",
	url: "spplindex.php",
	data: {fenxiang:<?php echo $_GET['sppl']?>},
	success: function(data){
		data = eval("("+data+")");
		if(data.length>0){
			var appstr = "";
			for(var i=0;i<data.length;i++){
				//appstr = "<img src=\"<?php echo $cxtPath ?>/"+data[i].goods_images+"\" />";

				appstr = "<div class=\"pl_img\"><img src=\"<?php echo $cxtPath ?>/"+data[i].goods_images+"\" /></div>";
				appstr += "<div class=\"pl_name_x\">";
				appstr += "<div class=\"pl_name\">商品名称:"+data[i].goods_name+"</div>";
				appstr += "<div class=\"pl_x\" data-dx=5 data-spid='"+ data[i].goods_id +"' ><div style=\"margin-left:10px;\">打星：</div><img style=\"background:#fe7182\" /><img style=\"background:#fe7182\"/><img style=\"background:#fe7182\"/><img style=\"background:#fe7182\"/><img style=\"background:#fe7182\"/></div>";
				appstr += "</div>";
				appstr += "<div style=\"clear:both;height:0;\"></div>";
				appstr += "<div><div class=\"pl_nr\">内容：</div><div class=\"dingweik\"><textarea class=\"text_f\" id=\"text_f\" placeholder=\"请输入评论内容\" ></textarea></div><div class=\"wubaizi\">(请输入500字以内)</div></div>";

				$("#pinglun").append(appstr);
			}
			$('.pl_x img').click(function(){
				$(this).css('background','#fe7182');
				$(this).prevAll('img').css('background','#fe7182');
				$(this).nextAll('img').css('background','#fff');
				$(this).parent().attr('data-dx',$(this).prevAll('img').length+1);
			})
		}
	}
});
function fenxiang(){
	if($('#productName').val()==""||$('#productName').val()==null){
		alert('标题不能为空！');
	}else if($('#text_f').val()==""||$('#text_f').val()==null){
		alert('内容不能为空！');
	}else{
		/*$.ajax({
			type: "POST",
			url: "lefenxiangget.php",
			data: {fenxiang:<?php echo $_GET['fenxiang']?>,biaoti:$('#productName').val(),neirong:$('#text_f').val()},
			success: function(data){
				if(data==1)
					alert('分享成功！');
				else if(data==2)
					alert('该订单已分享！');
				else
					alert('分享失败！');
			}
		});*/
	}
	return false;
}
$(function(){
	$('#tj_pl').click(function(){
		var gos = $('.pl_x');
		var txtb = $('.text_f');
		var zifuquan = "";
		for(var i=0;i<gos.length;i++)
		{
			if($(txtb[i]).val()==""){
				alert("商品评论不得为空");
				return;
			}
			zifuquan += $(gos[i]).attr('data-dx')+";"+$(gos[i]).attr('data-spid')+";"+$(txtb[i]).val()+"|";
		}
		zifuquan = zifuquan.substring(0,zifuquan.length-1);
		$.ajax({
			type: "POST",
			url: "spplget.php",
			data: {gos_json:zifuquan,ddid:<?php echo $_GET['sppl']?>},
			success: function(data){
				if(data == "1"){
					alert('评论已提交');
					window.location.href = "myorder.php?type=0";
				}else if(data == 2){
					alert('已评论');
				}
			}
		});
	})
})
</script>
<style>
	.biaoti{ font-size:14px; color:#555; height:45px; line-height:30px; padding:15px 0 0 10px;}
	.biaoti1{ font-size:14px; color:#555; height:210px; line-height:30px; padding:15px 0 0 10px;}
	.biaoti input{ font-size:14px; color:#555; background:#fff; height:25px; width:97%;margin: auto; border:1px solid #ebebeb;background:#fff; color:#555; line-height:25px; font-size:12px;}
	.biaoti1 textarea{ width:97%;margin: auto; height:180px; border:1px solid #ebebeb;background:#fff; color:#555; line-height:25px; font-size:12px;}
	.wubaizi{ font-size:12px; color:#777;width:97%;margin:3px 0 3px 10px;}
	.tijiao{ width:100%;margin:0 auto 100px auto;  height:35px; border:1px solid #ebebeb; background:#fe7182; color:#fff; line-height:35px; font-size:14px; text-align:center;}
	.fenxiang_img{width:96%;margin:0 2%;}
	.fenxiang_img img{width:100%;margin:5px 0; background:red;}

	.pl_img{ width:30%; float:left;}
	.pl_img img{ width:100%;}
	.pl_name_x { width:70%; float:left;}
	.pl_name_x .pl_name{padding:5px; font-size:14px; line-height:25px; color:#555;}
	.pl_name_x .pl_x{ font-size:12px; color:#888; height:25px; line-height:25px; }
	.pl_name_x .pl_x div{ float:left;}
	.pl_name_x .pl_x img{ margin-right:5px; float:left; width:10px; height:10px; border:1px solid #ececec;}
	.pl_nr{ font-size:12px; color:#888; line-height:20px;}
	.dingweik{ width:98%;border:1px solid #ccc; margin:0 auto;}
	.text_f{ font-size:14px; line-height:20px; border:none; background:#fff; width:98%; display:block; margin:5px auto;}
	.tj_pl{ height:35px; font-size:14px; line-height:35px; color:#fff; text-align:center; margin:20px 0 0 0; background:#fe7182;}
</style>
</head>
<body style="background:#fff;">
<!--<form method="post" id="searchProductForm" onsubmit="return fenxiang()">
	<div class="biaoti"><div class="span">标题：</div><div><input placeholder="请输入标题" class="search_text" name="productName" id="productName" type="search"></div></div>
	<div class="biaoti1"><div>内容：</div><textarea id="text_f" placeholder="请输入内容" ></textarea></div>
	<div class="wubaizi">(请输入500字以内)</div>
	<div class="fenxiang_img" id="fenxiang_img"></div>
	<div><input class="tijiao" type="submit" name="Submit" value="提交分享"/></div>
</form>-->
<div id="pinglun" style="background:#fff;">
	<!--<div class="pl_img"><img /></div>
	<div class="pl_name_x">
		<div class="pl_name">商品名称</div>
		<div class="pl_x">打星：</div>
	</div>
	<div style="clear:both;height:0;"></div>
	<div><div  class="pl_nr">内容：</div><textarea id="text_f" placeholder="请输入内容" ></textarea><div class="wubaizi">(请输入500字以内)</div></div>-->
</div>
<div class="tj_pl" id="tj_pl">提交评论</div>

<div style="height:80px;"></div>
<?php include '../gony/nav.php';?>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
</body>
</html>