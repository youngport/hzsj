<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}

$do = new condbwei();
$com = new commonFun();
$orderid = intval($_GET["orderid"]);

$openid = $_SESSION["openid"];

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
?>
<!DOCTYPE html>
<html lang="cn">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>加盟信息填写</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/css.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="http://cdn.bootcss.com/jquery.form/3.51/jquery.form.min.js"></script>
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

$(function(){
	$('#wancheng').click(function(){
		if($('#name').val()=="" || $('#shouji').val() =="" || $('#weixinqq').val() =="" || $('#dizhi').val() ==""|| $('#business').val() ==""|| $('#dianzhao').val() ==""){
		    alert("星号标志不得为空，请填写完整信息");
		}else{
			var data = new FormData($('#form')[0]);
			data.append('goodsid',"<?php echo $_GET['goodsid'];?>");
    		$.ajax({
    			type: "POST",
    			url: "dpgou_zlget.php",
    			data: data,
				contentType: false,    //不可缺
				processData: false,    //不可缺
    			success: function(data){
    				data = eval("("+data+")");
    				if(data.success==1){
    				    alert('填写成功，申请已提交，请耐心等待审核...');
    				    location.replace("./myorder.php?type=1");
    				}else if(data.success==0){
    				    alert('填写失败，请稍后重试');
        			}else if(data.success==2)
        			    alert('加盟条件不符合，请与客服联系...');
					else if(data.success==3)
						alert('图片文件非法');
					else if(data.success==4)
						alert('图片最大为5MB');
					else if(data.success==5)
						alert('图片上传错误');
    			}
    		})
		}
	})
})
</script>
<style>
html{background:#edecec;}
.top_text{ font-size:1.2em;width:80%;margin:0.4em auto;}
.text_input{ font-size:1em;width:85%;margin:0 auto; height:2.8em;line-height:2.8em;color:#585858}
.text_input input{ border:none;margin-left:0.5em;}
.text_input span{ font-size:0.7em;float:right;color:#ec8794;}
.wancheng{ background:#ec8794;width:90%;margin:2em auto;height:3em;font-size:1em;text-align:center;line-height:3em;color:#fff;
border-radius:4px;
-moz-border-radius:4px;
-webkit-border-radius:4px;
-o-border-radius:4px;
-ms-border-radius:4px;}
</style>
</head> 
<body style="background:#edecec;">
<div class="top_text">为了方便联系，请您填写真实信息</div>
<div style="background:#fff;">
	<form enctype="multipart/form-data" id="form" method="post">
    <div class="text_input">姓名:<input class="text" name="name" id="name" type="search"><span>*必填</span></div>
    <div style="border-top:1px solid #edecec;width:90%;margin:0 auto;"></div>
    <div class="text_input">联系电话:<input class="text" name="shouji" id="shouji" type="search"><span>*必填</span></div>
    <div style="border-top:1px solid #edecec;width:90%;margin:0 auto;"></div>
    <div class="text_input">微信号:<input class="text" name="weixinqq" id="weixinqq" type="search"><span>*必填</span></div>
    <div style="border-top:1px solid #edecec;width:90%;margin:0 auto;"></div>
    <div class="text_input">联系地址:<input class="text" name="dizhi" id="dizhi" type="search"><span>*必填</span></div>
	<div style="border-top:1px solid #edecec;width:90%;margin:0 auto;"></div>
	<div class="text_input">上传营业执照:<input name="business" id="business" type="file" style="width:50%"  accept="image/*"><span>*必填</span></div>
	<div style="border-top:1px solid #edecec;width:90%;margin:0 auto;"></div>
	<div class="text_input">上传店招:<input name="dianzhao" id="dianzhao" type="file" style="width:50%"  accept="image/*"><span>*必填</span></div>
	<div style="border-top:1px solid #edecec;width:90%;margin:0 auto;"></div>
    <div style="border-top:1em solid #edecec;width:100%;margin:0 auto;"></div>
    <div class="text_input">代理人姓名:<input class="text" name="dailiren" id="dailiren" type="search"></div>
    <div style="border-top:1px solid #edecec;width:90%;margin:0 auto;"></div>
    <div class="text_input">代理人号码:<input class="text" name="dailihaoma" id="dailihaoma" type="search"></div>
	</form>
</div>
<div class="wancheng" id="wancheng">完成</div>
<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
</body>
</html>