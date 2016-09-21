<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

session_start();
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
//手机，名称，公司名称 ，邮箱，官网，地址，头像，电话，职业
$sql = "select wei_phone,mingpian_name,gongsi_name,youxiang,guanwang,dizhi,touimg,dianhua,zhiye from sk_member where open_id='$openid'";
$popArr = $do->selectsql($sql);
$url='http://'.$_SERVER['HTTP_HOST'];

/*
$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$userArr = $com->getUserDet($openid, $do);

$selyear = date('Y',time())."-".date('m',time());
$sql = "select count(id) as con,sum(pop) as pop from wei_pop where openid='$openid'";
$popArr = $do->selectsql($sql);
$pop = $popArr[0]["pop"];
if($pop == "") $pop = "0.0";
$sql = "select count(order_id) as con from sk_orders where buyer_id='$openid' and order_time>='$selyear-01' and order_time<='$selyear-31'";
$myOrderArr = $do->selectsql($sql);*/

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
	<title>我的名片</title>
	<link rel="stylesheet" href="../../css/font-awesome.css"></link>
	<link rel="stylesheet" href="../../css/szzchqhd.css?v=2.5"></link>
	<link rel="stylesheet" href="../../css/tips.css"></link>
	<script type="text/javascript" src="../../js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="../../js/jquery.tipMessage.js"></script>
	<style type="text/css">
		.contact{background-color:rgba(0,0,0,.8);} /*滑动背景颜色*/
		.v_about,.v_hot h2,.v_commend,.v_hot .msg,.baoming .btn_bao a,.baoming .btn_bao input{background-color:#ff0000;}
		.v_ico,.v_hot h1{color:#ff0000}
		.v_hot li{color:#FFF; background:#ff0000;} /*导航背景及字体颜色*/
		.v_hot li a{color:#FFF;}
		.v_about,.v_hot h2,.v_commend,.v_hot .msg .title{color:#000;}}/*菜单字体颜色*/
		.v_hot p{color:#CCC;}
	</style>
</head>
<body ontouchstart="init(1)">
<input type="hidden" id="bgcolor" value="" />
<section class="g-wrap">
<div class="contact" id="contact" style="z-index:100;margin-top:300px;">
<div class="v_about">About Me</div>
<div class="v_infomation" style="padding-left:15px;">
<form action="mingpian_xiuget.php" method="post" enctype="multipart/form-data" target="ifm" >
	<span class="v_name"><?php echo $popArr[0]["mingpian_name"];?><i class="v_position"><?php echo $popArr[0]["zhiye"];?></i></span>
	<span class="v_node">名称：<input class="inputc" id='name' type="text" name="name" placeholder="请输入名称" value="<?php echo $popArr[0]["mingpian_name"];?>"></span>	  
	<span class="v_node">职业：<input class="inputc" id='zhiye' type="text" name="zhiye" placeholder="请输入职业" value="<?php echo $popArr[0]["zhiye"];?>"></span>	  
	<span class="v_node">图片：<input class="inputc" type="file" name="file" id="file" /></span>	   
	<span class="v_node">公司：<input class="inputc" id='gongsi' type="text" name="gongsi" placeholder="请输入公司名称" value="<?php echo $popArr[0]["gongsi_name"];?>"></span>	  
	<span class="v_nodes">手 机：<input class="inputc" id='shouji' type="text" name="shouji" placeholder="请输入手机号码" value="<?php echo $popArr[0]["wei_phone"];?>"></span>	  
	<span class="v_nodes">邮 箱：<input class="inputc" id='youxiang' type="text" name="youxiang" placeholder="请输入邮箱" value="<?php echo $popArr[0]["youxiang"];?>"></span>	  	  
	<span class="v_nodes">电 话：<input class="inputc" id='dianhua' type="text" name="dianhua" placeholder="请输入电话号码" value="<?php echo $popArr[0]["dianhua"];?>"></span>	  
	<span class="v_nodes">官网：<input class="inputc" id='guanwang' type="text" name="guanwang" placeholder="请输入官网地址" value="<?php echo $popArr[0]["guanwang"];?>"></span>	  
	<span class="v_nodes">地 址：<input class="inputc" id='dizhi' type="text" name="dizhi" placeholder="请输入公司地址" value="<?php echo $popArr[0]["dizhi"];?>"></span>	
	<span class="v_nodes"></span>	    
	<div style="text-align:center;"><input type="submit" name="submit" value="提交" type="button" style="color:#555;padding:2px 20px; maring:0 auto;"/></div>
</form>
</div>
	<div class="v_hot">
		  <h1>金天优惠</h1>
		  <h2>国内移动微商领导品牌</h2>
		  <p style="text-align: justify;"><span style="font-family: 微软雅黑;letter-spacing: 0;font-size: 16px">&nbsp; &nbsp; &nbsp; &nbsp; 金天优惠商城属金天医药集团全资子公司。金天医药集团（<span style="font-family: 微软雅黑; text-align: justify;">股票代码：</span>02211）源于1998年，经香港上市，旗下现营10家连锁公司、近千家连锁药房，6大批发公司,是国内知名复合型健康产业集团。金天优惠商城集品牌销售与推广、会员加盟、培训、电子商务于一体，是跨境电商销售平台，也是国内移动微商领导品牌，旗下经营10000多款产品，注册会员持续攀升。</span></p><p><span style="font-family: 微软雅黑;letter-spacing: 0;font-size: 16px"><br/>
		  </span></p>
		  <p style="text-align: center;"><span style="font-family: 微软雅黑;letter-spacing: 0;font-size: 16px"><img src="../../img/I3ThV4lG5LLhEzLmlDlQ9fDHb5bb03.png"></span></p>
		  <p style="text-align: center;"><span style="font-family: 微软雅黑;letter-spacing: 0;font-size: 16px"><img src="../../img/PnZ8GfKLeyMOw8982k32eo3oNeK9LL.png" style="white-space: normal;"></span></p>
		  <p style="text-align: center;"><img src="../../img/B688BSgVwwDbD24U68V8b8u8dzi74G.png"></p>
		  <p style="text-align: center;"><img src="../../img/g5wvWSI7cL2Vh8Y7Y2V72ej058vyVd.jpg"></p>
	</div>
	
	  </div>
</section>
<div class="pimg"> <img src="<?php echo $url.$popArr[0]["touimg"];?>" style="width:100%;z-index:10;"> <div class="arrow"></div></div>
<div id="itude-parent-div" class="itude-parent-div">
	<div class="itude-div" id="itude-div"></div>
</div>
<img src="./img/map_close.gif" class="map-close-img" id="map-close-img">
<img src="./img/bus_guide.gif" class="bus-guide-img" id="bus-guide-img">
<img src="./img/self_guide.gif" class="self-guide-img" id="self-guide-img">
<div id="r-result" class="r-result"></div>
<div id="open-road" class="open-road">
	<a href="javascript:;" class="open-road-btn" id="open-road-btn">打开路线图</a>
</div>
<script type="text/javascript">
			$(function(){
				//_init_map();	
		setTimeout(function(){
			var _h = $(window).height(), d_w = $(window).width(), _w = $('.g-wrap').width();
			$('.pimg').css({				
				left : (d_w - _w) /2
			});
			$('.ctel,.right_bar').css({				
				right : (d_w - _w) /2
			});
		},/android 2/i.test(navigator.userAgent) ? 150 : 0);
		$('.g-wrap').on('mousemove touchmove',function(e){
	              $('#arrow-h').addClass('hide_');
				})
			});			  
</script>
<style type="text/css">
body { margin-bottom:49px !important; }
a, button, input { -webkit-tap-highlight-color:rgba(255, 0, 0, 0); }
ul, li { list-style:none; margin:0; padding:0 }
.top_bar { position: fixed; z-index: 900; bottom: 0; left: 0; right: 0; margin: auto; font-family: Helvetica, Tahoma, Arial, Microsoft YaHei, sans-serif; }
.top_menu { display:-webkit-box; border-top: 1px solid #3D3D46; display: block; width: 100%; background: rgba(255, 255, 255, 0.7); height: 48px; display: -webkit-box; display: box; margin:0; padding:0; -webkit-box-orient: horizontal; background: -webkit-gradient(linear, 0 0, 0 100%, from(#524945), to(#48403c), color-stop(60%, #524945)); box-shadow: 0 1px 0 0 rgba(255, 255, 255, 0.1) inset; }
.top_bar .top_menu>li { -webkit-box-flex:1; position:relative; text-align:center; }
.top_menu li:first-child { background:none; }
.top_bar .top_menu>li>a { height:48px; margin-right: 1px; display:block;font-size:20px; text-align:center; color:#FFF; text-decoration:none; text-shadow: 0 1px rgba(0, 0, 0, 0.3); -webkit-box-flex:1; }
.top_bar .top_menu>li>a:before{
    margin:5px 0 0 5px;
}
.top_bar .top_menu>li.home { max-width:70px }
.top_bar .top_menu>li.home a { height: 66px; width: 66px; margin: auto; border-radius: 60px; position: relative; top: -22px; left: 2px; background: url(./resource/content/images/in.png) no-repeat center center; background-size: 100% 100%; }
.top_bar .top_menu>li>a label { overflow:hidden; margin: 0 0 0 0; font-size: 14px; display: block !important; line-height: 48px; text-align: center; }
.top_bar .top_menu>li>a img { padding: 3px 0 0 0; height: 24px; width: 24px; color: #fff; line-height: 48px; vertical-align:middle; }
.top_bar li:first-child a { display: block; }
.menu_font { text-align:left; position:absolute; right:10px; z-index:500; background: -webkit-gradient(linear, 0 0, 0 100%, from(#524945), to(#48403c), color-stop(60%, #524945)); border-radius: 5px; width: 120px; margin-top: 10px; padding: 0; box-shadow: 0 1px 5px rgba(0, 0, 0, 0.3); }
.menu_font.hidden { display:none; }
.menu_font { top:inherit !important; bottom:60px; }
.menu_font li a { height:40px; margin-right: 1px; display:block; text-align:center; color:#FFF; text-decoration:none; text-shadow: 0 1px rgba(0, 0, 0, 0.3); -webkit-box-flex:1; }
.menu_font li a { text-align: left !important; }
.top_menu li:last-of-type a { background: none; overflow:hidden; }
.menu_font:after { top: inherit!important; bottom: -6px; border-color: #48403c rgba(0, 0, 0, 0) rgba(0, 0, 0, 0); border-width: 6px 6px 0; position: absolute; content: ""; display: inline-block; width: 0; height: 0; border-style: solid; left: 80%; }
.menu_font li { border-top: 1px solid rgba(255, 255, 255, 0.1); border-bottom: 1px solid rgba(0, 0, 0, 0.2); }
.menu_font li:first-of-type { border-top: 0; }
.menu_font li:last-of-type { border-bottom: 0; }
.menu_font li a { height: 40px; line-height: 40px !important; position: relative; color: #fff; display: block; width: 100%; text-indent: 10px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
.menu_font li a img { width: 20px; height:20px; display: inline-block; margin-top:-2px; color: #fff; line-height: 40px; vertical-align:middle; }
.menu_font>li>a label { padding:3px 0 0 3px; font-size:14px; overflow:hidden; margin: 0; }
#menu_list0 { right:0; left:10px; }
#menu_list0:after { left: 20%; }
#sharemcover { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); display: none; z-index: 20000; }
#sharemcover img { position: fixed; right: 18px; top: 5px; width: 260px; height: 180px; z-index: 20001; border:0; }
.top_bar .top_menu>li>a:hover, .top_bar .top_menu>li>a:active { background-color:#333; }
.menu_font li a:hover, .menu_font li a:active { background-color:#333; }
.menu_font li:first-of-type a { border-radius:5px 5px 0 0; }
.menu_font li:last-of-type a { border-radius:0 0 5px 5px; }
#plug-wrap { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0); z-index:800; }
#cate18 .device {bottom: 49px;}
#cate18 #indicator {bottom: 240px;}
#cate19 .device {bottom: 49px;}
#cate19 #indicator {bottom: 330px;}
#cate19 .pagination {bottom: 60px;}
</style>
<div id="sharemcover" onClick="document.getElementById('sharemcover').style.display='';" style=" display:none"></div>
<div class="top_bar" style="-webkit-transform:translate3d(0,0,0)">
  <nav>
    <ul id="top_menu"  class="top_menu"> 
		  		
    	<li><a href='mingpian_xiu.php' class="" style="color:;font-size:px;"><label>修改名片</label></a></li>
						  		
    	<li><a href='http://mp.weixin.qq.com/s?__biz=MzA4OTcyOTM5NQ==&mid=209415610&idx=1&sn=e073ffaa48bacda621dadecf9966123f#rd' class="" style="color:;font-size:px;"><label>关注我们</label></a></li>
						  		
    	<li><a href='../../index.php' class="" style="color:;font-size:px;"><label>进入商城</label></a></li>
	</ul>
  </nav>
</div>
<?php include '../gony/returntop.php';?>
</body>
</html>