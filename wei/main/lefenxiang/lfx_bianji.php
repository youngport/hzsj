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
$openid = $_SESSION["openid"];

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl = 'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);


setcookie();
$cookie_up = $_COOKIE["fenxiang"] ;
$arr = explode(",",$cookie_up);
$sql_in = "";
for($i=0;$i<count($arr);$i++){
    $sql_in .= $arr[$i].",";
}
$sql_in = substr($sql_in, 0, strlen($sql_in)-1);
if($sql_in != ""){
    $sql = "select id,img from sk_goods where id in(".$sql_in.")";
    $cookieArr = $do -> selectsql($sql);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>乐分享</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/home.css"/>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
	
	
	
		<link rel="stylesheet" href="<?php echo $cxtPath ?>/wei/demo/control/css/zyUpload.css" type="text/css">
		<!--图片弹出层样式 必要样式-->	
		<!-- 引用核心层插件 -->
		<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/demo/core/zyFile.js"></script>
		<!-- 引用控制层插件 -->
		<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/demo/control/js/zyUpload.js"></script>
		<!-- 引用初始化JS -->
		<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/demo/demo.js"></script>
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
</script>
	<style type="text/css">
	body{font-family: '黑体';letter-spacing : 0.5px; }
	.top{background: #000;width: 100%;color: #fff;font-size:2em;line-height:2em;color:#fff;width:100%;text-align: center;}
	.nav{background: #eee;width: 100%;color: #3d3d3d;font-size:2em;line-height:2em;color:#000;width:100%;text-indent: 1em;}
	.fenlei{background: #fff;height: 120px;width: 100%;color: #fff;font-size:1.7em;line-height:2em;color:#000;width:100%;}
	ul{margin: 0;padding: 0;list-style: none;margin-left: 1em;}
	.a{float: left;padding: 0px 5px;border: 1px solid #dcdcdc;background: #e5e5e5;margin-right: 28px;margin-top: 15px;border-radius:4px;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		-o-border-radius:4px;
		-ms-border-radius:4px;}
	.b{float: left;padding: 0px 5px;border: 1px solid #dcdcdc;background: #eb6877;margin-right: 28px;margin-top: 15px;border-radius:4px;color: #fff;
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	-o-border-radius:4px;
	-ms-border-radius:4px;}
	.kuang{width:90%;margin:0.2em auto;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		-o-border-radius:4px;
		-ms-border-radius:4px;}
.txt{ font-size:1.7em;line-height:1.6em;color:#000;width:100%;height:7em;border:none;font-family: 黑体;letter-spacing : 0.5px; }
.sub{ width:10em;height:2em;font-size:2em;color:#fff;text-align:center;background:#fe7182;line-height:2em;margin:0  auto;margin-top: 15px;
		border-radius:4px;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		-o-border-radius:4px;
		-ms-border-radius:4px;}
	</style>
</head>
<body>
<div class="top">分享</div>
<div class="nav">选择标签</div>
<div class="fenlei">
	<ul id="tab_t">
		<li class="a" value="1">最优惠</li>
		<li class="a" value="2">新入手</li>
		<li class="a" value="3">爆款</li>
		<li class="a" value="4">热推</li>
		<li class="a"value="5">最爱</li>
	</ul>
</div>
<div class="nav">分享心得</div>
<div class="kuang">
<div style="width:94%;margin:0.6em auto;">
<textarea id="xinde" class='txt' placeholder="请输入内容" ></textarea>
</div>
</div>
<div class="nav">上传图片<span style="font-size:10px">(注:上传图片不能超过6m)</span></div>
<div id="demo" class="demo"></div>  
<div class="sub" id="fabiao">确认分享</div> 
<script> 
//标签点击效果
 var menu = document.getElementById('tab_t');
var links = menu.getElementsByTagName('li');
for (var i = 0; i < links.length; i++) {
    links[i].onclick = function () {
        for (var j = 0; j < links.length; j++) {
            if(links[j] == this) {
                links[j].className = "b";   
                 //alert(links[j].value) ;             
            } else {
                links[j].className = "a";
            }
        }
    }
}

$('#sanchu_cookie').click(function(){
	$.ajax({
		type: "POST",
		url: "lfx_cookie.php",
		data: {},
		success: function(data){
			$('#sanchu_cookie').hide();
			$('#cookie_god').empty();
		}
	})
})
function replaceTextarea1(str){
	var reg=new RegExp("\r\n","g"); 
	var reg1=new RegExp(" ","g"); 

	str = str.replace(reg,"＜br＞"); 
	str = str.replace(reg1,"＜p＞"); 

	return str; 
	}
//发表ajax
$('#fabiao').click(function(){
	if(!$('li').hasClass("b"))
		alert('请选择标签！');			
	else if($('#xinde').val()=="") 
		alert('分享心得不能为空！');
	else if(imgname=="")
		alert('请上传图片！');
	else{
		$.ajax({
			type: "POST",
			url: "lfx_insert.php",
			data: {"fenlei":$(".b").val(),"xinde":$('#xinde').val().replace(/\r/gi,"<br\/><br\/>").replace(/\n/gi,"<br\/><br\/>"),"imgname":imgname},
			success: function(data){
				if(data == 1){
				    alert('已发表成功，等待审核');
				    location.href="lfx_index.php";
				}else
					alert('发表失败，请稍后重试');
			}
		});
	}
})
</script>
<?php include '../gony/nav.php';?>
<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
<div style="clear:both;height:90px;"></div>
</body>
</html>