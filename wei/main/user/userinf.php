<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION["access_token"])){
	//header("Location:".$cxtPath."/wei/login.php");
	//return;
}
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];

$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$userArr = $com->getUserDet($openid, $do);
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
	<title>提现信息编辑</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/area.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
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

function save(){
	$("#form_add").ajaxSubmit({
		type: "post",
		url: "userinfsave.php",
		dataType: "json",
		success: function(data){
			if(data.success == "1"){
				msgSuccess("保存成功！");
				location.href="dui.php";
			}else{
				msgError("保存失败，请稍后重试！");
			}
		}
	});
}
</script>
<style>
.xmsmd{ height:45px;border-bottom:1px solid #dddddd;}
.xmsmd .xxmm{ line-height:45px; font-size:18px;margin-left:15px; float:left;}
.xmsmd .ipt{ margin:5px 0 0 7px;width:250px; float:left;}
.xmsmd .ipt .tkxinx{ height:28px;width:250px;font-size:16px; border:none;}
.bianji{ background:#fe7182;text-align:center;font-size:16px; line-height:40px; height:40px; width:180px; margin:20px auto; color:#fff;}
</style>
</head>
<body style="margin:0px;padding:0px;background:#fff">
<form id="form_add">
		<div style="width:100%;height:auto;background:white;">
			<div class="userxiao" style="height:30px;line-height:30px;background:#F3F3F3">
				<font style="font-size:16px;color:#535758;">基本信息</font>
			</div>
			<div class="xmsmd"><div class="xxmm"><strong>姓名</strong></div><div class="ipt"><input class="tkxinx" type="text" name="yin_username" id="yin_username" value='<?php echo $userArr[0]["yin_username"] ?>'></div></div>
			<div class="xmsmd"><div class="xxmm"><strong>手机</strong></div><div class="ipt"><input class="tkxinx" type="text" name="wei_phone" id="wei_phone" value='<?php echo $userArr[0]["wei_phone"] ?>'></div></div>
			<!-- <span class="userinfsp"><strong>姓名</strong></span>
			<br/>
			<span class="userinfsp">手机</span><br/>
			<input class="tipInput userinp" type="text" name="wei_phone" id="wei_phone" value='<?php echo $userArr[0]["wei_phone"] ?>'> -->
		</div>
		<div style="width:100%;height:auto;background:white;">
			<div class="userxiao" style="height:30px;line-height:30px;background:#F3F3F3">
				<font style="font-size:16px;color:#535758;">银行卡信息</font>
			</div>
			<div class="xmsmd"><div class="xxmm"><strong>收款银行</strong></div><div class="ipt" style="width:auto;">
    			<select class="tkxinx" style="width:auto;" name="brank" id="brank">
    				<option value=""></option>
    				<option value="中国工商银行">中国工商银行</option>
    				<option value="中国建设银行">中国建设银行</option>
    				<option value="中国银行">中国银行</option>
    				<option value="中国农业银行">中国农业银行</option>
    				<option value="交通银行">交通银行</option>
    				<option value="招商银行">招商银行</option>
    				<option value="中信银行">中信银行</option>
    				<option value="中国光大银行">中国光大银行</option>
    				<option value="华夏银行">华夏银行</option>
    				<option value="浦发银行">浦发银行</option>
    				<option value="兴业银行">兴业银行</option>
    				<option value="民生银行">民生银行</option>
    				<option value="平安银行">平安银行</option>
    				<option value="广发银行">广发银行</option>
    				<option value="恒丰银行">恒丰银行</option>
    				<option value="渤海银行">渤海银行</option>
    				<option value="浙商银行">浙商银行</option>
    			</select>
			</div></div>
			<div class="xmsmd"><div class="xxmm"><strong>卡号</strong></div><div class="ipt"><input class="tkxinx" type="text" name="yin_code" id="yin_code" value='<?php echo $userArr[0]["yin_code"] ?>'></div></div>
			<!-- <span class="userinfsp"><strong>姓名</strong></span>
			<br/>
			<span class="userinfsp">手机</span><br/>
			<input class="tipInput userinp" type="text" name="wei_phone" id="wei_phone" value='<?php echo $userArr[0]["wei_phone"] ?>'> -->
		</div>
		<div style="width:100%;height:auto;background:white;">
			<div class="userxiao" style="height:30px;line-height:30px;background:#F3F3F3">
				<font style="font-size:16px;color:#535758;">支付宝信息</font>
			</div>
			<div class="xmsmd"><div class="xxmm"><strong>支付宝账号</strong></div><div class="ipt" style="width:200px;"><input class="tkxinx" style="width:200px;" type="text" name="pay_code" id="pay_code" value='<?php echo $userArr[0]["pay_code"] ?>'></div></div>
		</div>
		<!-- <div style="margin-top:2%;width:100%;height:auto;background:white;">
			<div class="userxiao" style="height:30px;line-height:30px;background:#F3F3F3">
				<font style="font-size:16px;color:#535758;"><strong>支付宝账号</strong></font>
			</div>
			<input class="tipInput userinp" style="margin-top:10px;" type="text" name="pay_code" id="pay_code" value='<?php echo $userArr[0]["pay_code"] ?>'>
		</div>
		<div style="margin-top:2%;width:100%;height:auto;background:white;">
			<div class="userxiao" style="height:30px;line-height:30px;background:#F3F3F3">
				<font style="font-size:16px;color:#535758;"><strong>银行账号</strong></font>
			</div>
			<span class="userinfsp">收款银行户名</span><br/>
			<input class="tipInput userinp" type="text" name="yin_username" id="yin_username" value='<?php echo $userArr[0]["yin_username"] ?>'><br/>
			<span class="userinfsp">收款银行账号</span><br/>
			<input class="tipInput userinp" type="text" name="yin_code" id="yin_code" value='<?php echo $userArr[0]["yin_code"] ?>'><br/>
			<span class="userinfsp">收款银行</span><br/>
			<select class="tipInput userinp" name="brank" id="brank">
				<option value=""></option>
				<option value="中国工商银行">中国工商银行</option>
				<option value="中国建设银行">中国建设银行</option>
				<option value="中国银行">中国银行</option>
				<option value="中国农业银行">中国农业银行</option>
				<option value="交通银行">交通银行</option>
				<option value="招商银行">招商银行</option>
				<option value="中信银行">中信银行</option>
				<option value="中国光大银行">中国光大银行</option>
				<option value="华夏银行">华夏银行</option>
				<option value="浦发银行">浦发银行</option>
				<option value="兴业银行">兴业银行</option>
				<option value="民生银行">民生银行</option>
				<option value="平安银行">平安银行</option>
				<option value="广发银行">广发银行</option>
				<option value="恒丰银行">恒丰银行</option>
				<option value="渤海银行">渤海银行</option>
				<option value="浙商银行">浙商银行</option>
			</select><br/>
			<span class="userinfsp">收款银行地址</span><br/>
			<select id="brank_adda" name="brank_adda" class="tipInput userinp" onchange="selectArea('brank_adda', 'brank_addb');">
				<option value="">请选择省</option>
				<option value="北京">北京</option>
		    	<option value="深圳">深圳</option>
		    	<option value="上海">上海</option>
		    	<option value="重庆">重庆</option>
		    	<option value="天津">天津</option>
		    	<option value="广东">广东</option>
		    	<option value="河北">河北</option>
		    	<option value="山西">山西</option>
		    	<option value="内蒙古">内蒙古</option>
		    	<option value="辽宁">辽宁</option>
		    	<option value="吉林">吉林</option>
		    	<option value="黑龙江">黑龙江</option>
		    	<option value="江苏">江苏</option>
		    	<option value="浙江">浙江</option>
		    	<option value="安徽">安徽</option>
		    	<option value="福建">福建</option>
		    	<option value="山东">山东</option>
		    	<option value="河南">河南</option>
		    	<option value="湖北">湖北</option>
		    	<option value="湖南">湖南</option>
		    	<option value="广西">广西</option>
		    	<option value="海南">海南</option>
		    	<option value="四川">四川</option>
		    	<option value="贵州">贵州</option>
		    	<option value="云南">云南</option>
		    	<option value="西藏">西藏</option>
		    	<option value="陕西">陕西</option>
		    	<option value="甘肃">甘肃</option>
		    	<option value="青海">青海</option>
		    	<option value="宁夏">宁夏</option>
		    	<option value="新疆">新疆</option>
		    	<option value="港澳台">港澳台</option>
			</select><br/>
			<select id="brank_addb" name="brank_addb" class="tipInput userinp">
				<option value="">请选择市</option>
			</select><br/>
			<span class="userinfsp">收款银行所属支行</span><br/>
			<input class="tipInput userinp" type="text" name="brank_zhi" id="brank_zhi" value='<?php echo $userArr[0]["brank_zhi"] ?>'>
		</div>-->
	</form>
	
	
	<div onclick="save()" class="bianji">保存资料</div>
<?php include '../gony/guanzhu.php';?>
<?php include './main/gony/returntop.php';?>
<script>

</script>
</body>
</html>