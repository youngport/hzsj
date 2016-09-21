<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$openid = $_SESSION["openid"];


$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$sql ="select jifen from sk_member where open_id='$openid'";
$jifen = $do->getone($sql);
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
	<title>我的优惠</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v1.6" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/css.css" />
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
	<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	wx.config({
		appId: '<?php echo $shareCheckArr["appid"];?>',
		timestamp: '<?php echo $shareCheckArr["timestamp"];?>',
		nonceStr: '<?php echo $shareCheckArr["noncestr"];?>',
		signature: '<?php echo $shareCheckArr["signature"];?>',
		jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','hideMenuItems'] // 功能列表，我们要使用JS-SDK的什么功能
	});
	ready(0,0,0);
	function ready(type,id,sn){
		var title='洋仆淘跨境商城——源自于深圳自贸区的专业跨境电商平台';
		var desc='洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.';
		var image='logo.png';
		var state="<?php echo $openid; ?>";
		if(type==1){
			title='送你一张优惠券';
			desc='你的朋友叫你领券啦，洋仆淘商城大放价，优惠享不停';
			image='coupon.png';
			state+='|coupon|'+id+'|'+sn;
		}
		wx.ready(function(){
			var shareData = {
				title: title,
				desc: desc, // 分享描述
				link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state='+state+'&connect_redirect=1#wechat_redirect',
				imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/'+image,
				success: function (res) {
					msgSuccess("分享成功");
					if(type==1) {
						$('#hide').hide();
						$.ajax({
							type: "POST",
							url: "coupon_cz.php",
							data: {cz:1,id:id,sn:sn},
							success: function(data){
							}
						});
					}
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
	}
	</script>
	<style>
		div{background:#fff}
		#hide{display:none;position:absolute;left:0;top:0;background-color:#000;background-color:rgba(0,0,0,0.7);width:100%;height:100%;z-index:1000;text-align:center;padding-top:5.3rem;color:#fff}
		#hide img{position:absolute;left:78%;top:20px;width:15%}
		.nav{border-bottom:1px solid #ddd;padding:0 15px;}
		.nav ul{margin-bottom:0px;width:100%}
		.nav li{width:49%;}
		.active{color:#F57074;border-bottom:2px solid #F57074}
		.int{background:#F5F5F5;display:none;}
		.conpon{display:none;}
		.info{width:100%;}
		.info td{width:33%;text-align:center;}
		.liebiao{display:none;}
		.liebiao li{border-top:1px solid #ddd;}
		.goods {margin-bottom:-1px;}
		.goods .col-xs-6{border-bottom:1px solid #ddd;}
		.goods h6{margin:0;margin-bottom:0.4em}
		.goods .col-xs-6 button{width:90%}

		.conpon-list{background:#EC6F7F;margin-top:15px;border-radius:10px;border:1px solid #ededed;overflow:hidden;}
		.conpon-list h1{padding:1.3rem 0;color:#fff}
		.conpon-list small{color:#fff}

		.conpon-info h6{padding:0.5rem 0;margin:0;}
		.conpon-info{padding:0 10px;}
	</style>
</head>
<body style="padding:0;">
<div class="text-center nav">
	<ul class="list-inline center-block">
		<li id="conpon"><h4>现金券</h4></li>
		<li id="int"><h4>积分</h4></li>
	</ul>
</div>
<div class="container-fluid conpon">
	<?php
	$list=$do->selectsql("select * from sk_coupon where (rec='' or rec='$openid') and status in(1,3) order by end_time desc");
	$str='';
	//查询所有，输出模板
	foreach($list as $v) {
		$str.="<div class='conpon-list text-center' data-id='".$v['id']."' data-sn='".$v['sn']."'";
		if(($v['status']==1||$v['status']==3)&&$v['end_time']>=time()){
			$str.=" data-status=1";
		}else{
			$str.=" data-status=0";
		}
		//如果有效期过了，且是店铺发送的，就返回金额给店铺的现金券中
		if (($v['end_time']<time() || $v['status'] == 2 )&& $v['sendid'] ==0 ) {
			$arr = $do->getone("select openid from sk_erweima where id = '".$v['sendid']."'");
			$money = $do->getone("select money from sk_mcoupon where open_id = '".$arr['openid']."'");		$money['money'] += $v['xz'];
			$do->dealsql("update sk_mcoupon set money ='".$money['money']."' where open_id = '".$arr['openid']."'");              
		}
		//如果有效时间过了，就删除记录。
		if($v['end_time']<time()) {
			//$str .= " style='background:#ddd'";
			$do->dealsql("delete from sk_coupon where id='".$v['id']."'");
		}
		$str.=">";
		$str.="<h1>".$v['js']."<small >元  &nbsp;&nbsp;代金券</small ></h1 >";
		$str.="<div class='conpon-info'>";
		$str.="<h6 class='pull-left'> 购满".$v['xz']."元后可用</h6>";
		$str.="<h6 class='pull-right'>有效期至".date('Y-m-d',$v['end_time'])."</h6 >";
		$str.="<div class='clearfix'></div ></div ></div >";
	}
	echo $str;
	?>
</div>
<div class="container-fluid int">
	<div>
		<table class="info">
			<tr>
				<td>&nbsp;</td>
				<td align="center"><h4>我的积分</h4></td>
				<td style="text-align:right;padding-right:0.5em"><a style="color:#B39933" href="jifenguize.php">? 积分规则</a></td>
			</tr>
			<tr>
				<td colspan="3"><h1 style="color:#F3575B"><?php echo $jifen['jifen'];?></h1></td>
			</tr>
			<tr>
				<td colspan="3"><h5 style="color:#89BC6F">积分可用来兑换商品</h5></td>
			</tr>
			<tr>
				<td colspan="3"><button class="btn btn-danger kh" style="width:50%;margin-bottom:0.6em">兑换商品</button></td>
			</tr>
		</table>
	</div>
	<div class="container-fluid row" style="margin:0.7em 0px;">
		<div class="col-xs-10">
			<h4>积分明细</h4>
		</div>
		<div class="col-xs-2" style="margin:1em 0px;"><img class="img-responsive pull-right xq" data-cz="0" src="../../css/img/xl.png"></div>
		<div class="clearfix"></div>
		<div class="liebiao">
			<ul>
				<?php
				$list=$do->selectsql("select jifen,laiyuan,shijian from sk_jifen where openid='$openid' order by shijian desc");
				if(empty($list)){
					echo "<li><div class='col-xs-12'><h5>暂时还没有积分</h5></div><div class='clearfix'></div></li>";
				}else {
					foreach ($list as $v) {
						$info = array("发表了乐分享", "邀请了好友", "兑换了礼品", "发表意见与建议", "参与了商品评论", "分享普通商品", "成功分享活动", "关注商城", "成功分享文章", "确认收货");
						$str = "<li>";
						$str .= "<div class='col-xs-8'><h5>" . $info[$v['laiyuan']] . "</h5><h5>" . date("Y-m-d H:i:s", $v['shijian']) . "</h5></div>";
						$str .= "<div class='col-xs-4 text-right'><h5>";
						$str .=$v['laiyuan']==2?"-":"+";
						$str .=$v['jifen'] . "</h5></div>";
						$str .= "<div class='clearfix''></div>";
						$str .= "</li>";
						echo $str;
					}
				}
				?>
			</ul>
		</div>
	</div>
	<div class="container-fluid text-center goods">
		<?php
		$list=$do->selectsql("select id,good_name,img,dhjifen from sk_goods where goodtype=3 and status=1 order by dhjifen desc");
		foreach($list as $v){
			$str="<div class='col-xs-6' style='padding:0.5em 0px;'>";
			$str.="<div class='goodsview'>";
			$str.="<img class='center-block img-responsive' style='max-width:70%' src=".$cxtPath."/".$v['img']." />";
			$str.="<h6>".$v['good_name']."</h6>";
			$str.="<h6>需积分 ".$v['dhjifen']."</h6>";
			$str.="<a class='btn btn-danger' onclick='(duihuan(".$v['id']."))'>立即兑换</a>";
			$str.="</div></div>";
			echo $str;
		}
		?>
	</div>
</div>
<div id="hide">
	<h4>点击右上角</h4>
	<h4>分享优惠券给好友</h4>
	<img src="../../css/img/jt.png"/>
</div>
</body>
<script>
var type="<?php echo $_GET['type']?intval($_GET['type']):0;?>";
if(type=="0"){
	$("#conpon").addClass("active");
	$(".conpon").show();
}else{
	$("#int").addClass("active");
	$(".int").show();
}
//积分相关
$("#int").click(function(){
	$("#int").addClass("active");
	$("#conpon").removeClass("active");
	$(".int").show();
	$(".conpon").hide();
});
$("#conpon").click(function(){
	$("#conpon").addClass("active");
	$("#int").removeClass("active");
	$(".conpon").show();
	$(".int").hide();
});
$(".nav li").click(function(){
	var cz=$(this).attr("id");
	$(this).addClass("active");
	$().show();
});
$(".xq").click(function(){
	if($(this).attr("data-cz")=="0") {
		$(this).css("-webkit-transform", "rotate(-180deg)");
		$(this).attr("data-cz","1");
		$("html,body").animate({scrollTop:$(this).parents(".row").offset().top},800);
	}else {
		$(this).css("-webkit-transform", "");
		$(this).attr("data-cz","0");
		$("html,body").animate({scrollTop:$("body").offset().top},800);
	}
	$(".liebiao").fadeToggle();
});
$(".kh").click(function(){
	$("html,body").animate({scrollTop:$(".goods").offset().top},800);
});
$(".goodsview:odd").css({'border-left':'1px solid #ddd','margin-left':'-1px'});
function duihuan(id){
	$.ajax({
		type: "POST",
		url: "../goods/goodsAddOrder.php",
		data: {goodsid:id},
		success: function(data){
			data = eval("("+data+")");
			if(data.success == "1"){
				var urljs="../order/order.php?orderid="+data.orderid;
				window.location.href = urljs;
			}else if(data.success == "2"){
				alert("该商品库存不足");
			}else if(data.success == "4"){
				alert("您的积分不足以兑换该商品");
			}else{
				alert("兑换失败，请稍后重试");
			}
		}
	});
}
//积分结束


//优惠券相关
$('.conpon-list').click(function(){
	var status=$(this).attr('data-status');
	if(status==1) {
		ready(1,$(this).attr('data-id'),$(this).attr('data-sn'));
		$('#hide').show();
	}
});
$('#hide').click(function(){
	$(this).hide();
});
</script>
</body>
</html>