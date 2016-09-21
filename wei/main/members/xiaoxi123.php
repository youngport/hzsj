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
<?php
$sql="select sum(if(isnull(open_id),1,0)) count from sk_message a left join sk_message_read b on a.id=b.mid where rec='$openid' or recpid=(select if(pid!='',pid,0) from sk_member where open_id='$openid')";
$pcount=$do->getone($sql);
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
	<title>消息中心</title>
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
            msgSuccess("成功分享到朋友圈");
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
getxiaoxi(1,0);
getxiaoxi(2,0);
var p=0;
var read=1;
function get(data,type){
	//var data = eval("("+data+")");
	var data = JSON.parse(data); 
	var str='';
	for(var i=0;i<data.length;i++){
		if(data[i].is_read==1&&read==1){
			str+="<h3 style='text-align:center'>以下已读</h3><hr/>";
			read=0;
		}
		str+="<h3>"+data[i].create_time+"</h3>";
		if(data[i].title!=''||data[i].abst!=''){
			str+="<div class='message' data-id="+data[i].id+" data-read="+data[i].is_read+" ><h2>"+data[i].title+"</h2>";
			if(data[i].img!='')str+="<a href='xiaoxi_content.php?id="+data[i].id+"'><img src='"+data[i].img+"'/></a>";
			str+="<p>"+data[i].abst+"</p><a href='xiaoxi_content.php?id="+data[i].id+"'>查看正文</a></div>";
		}else {
			str+="<div class='sms' data-id="+data[i].id+" data-read="+data[i].is_read+" ><img src='/data/logo/wlogo.jpg' style='width:20%;float:left;'/><p>"+data[i].intro+"</p></div><div class='clear'></div>";
		}
	}
	var cl='';
	if(type==1)cl='#public';else cl='#private';
	$(cl).append(str);
	if(data.length==5){
		$(cl).find('.get').remove();
		p+=5;
		$(cl).append("<p onclick='getxiaoxi("+type+","+p+")' class='get'>加载更多</p>");
	}
}
function getxiaoxi(type,p){
	if(type==1)cl='#public';else cl='#private';
	$(cl+' .get').remove();
	$.ajax({
		type: "POST",
		url: "xiaoxiget123.php",
		data: {type:type,p:p},
		success: function(data){
			//console.log(data);
			get(data,type);
		}
	});
}
</script>
<style>
body{background:#f1f1f1;margin:0px;padding:0px;}
.tou li{width:50%; height:35px;line-height:35px;font-size:14px;float:left;text-align:center;background: #fff;border-bottom: solid 1px #8e8e8e;}
.tou li font{border-radius: 100%;background: #f298a6;color: #fff;}
.xiaoxishow{ line-height:30px; }
.xiaoxishow{ font-size:14px;color:#555;}
.xim{ width:65%; background:#900;}
.xis{ width:40%;}
.neirul{width:98%;margin:0 auto;}
.neirul .ullishijain{ margin-top:10px; padding-left:10px; font-size:12px;color:#555; height:20px; line-height:20px; border-top:1px solid #ebebeb; border-left:1px solid #ebebeb; border-right:1px solid #ebebeb;}
.neirul .ulliname{ font-size:14px;color:#555; padding:10px;line-height:20px; border:1px solid #ebebeb;}
.neirul .ulliname span{margin:0 10px;}
h3{margin:13px auto;text-align:center;color: #f9f9f9;background: #d4d5d5;padding: 3px 0px;border-radius: 3px;display: block;font-weight: 100;font-size: 12px;width: 124px;}
.message{background:#fff;width:86%;margin:0 auto;padding: 0 10px;border-radius: 3px;}
.sms{background:#fff;width:86%;margin:0 auto;border-radius: 3px;}
.message img{display:block;width:100%;margin:0 auto;}
.message p{font-size: 12px;color: #717171;font-weight: bold;display: block;line-height: 20px;}
.message h2{font-size: 14px;display: block;line-height:30px;}
.message a{color: #010101;font-size: 13px;display: block;margin: 7px 0;}
.sms p{width:75%;float:right;background:#fff;border-radius: 3px;padding:4px;font-size:15px}
.get {text-align:center;font-size:16px;padding:5px 0px;}
.col{color:#ec6d7e}
.clear{clear:both}
</style>
</head>
<body>
<ul class="tou">
	<li id="lixt" style="margin-left:-1px;border-right:1px solid #8e8e8e;" class='col'>公众消息</li>
	<li id="ligr" style="">个人消息<?php echo $pcount['count']>0?"(".$pcount['count'].")":'';?></li>
</ul>
<div style="clear:both;"></div>
<div id='public'>
</div>
<div id='private' style='display:none'></div>
</body>
<script>
$('#lixt').click(function(){
	$('#public').show();
	$('#ligr').removeClass('col');
	$(this).addClass('col');
	$('#private').hide();
});
$('#ligr').click(function(){
	$('#public').hide();
	$('#lixt').removeClass('col');
	$(this).addClass('col');
	$('#private').show();
	is_read();
});
function is_read(){
	var id='';
	$("#private div:not('.clear')").each(function(){
		if($(this).attr("data-read")==0)
			id+=$(this).attr("data-id")+',';
	});
	$.post("xiaoxiread.php",{id:id});
}
$(".get").click(function(){
	is_read;
});
</script>
</html>