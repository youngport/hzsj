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


function quotes($content){
	if (is_array($content))	{
		foreach ($content as $key=>$value){
			$content[$key] = addslashes($value);
		}
	}else{
		$content=addslashes($content);
	}
	return htmlspecialchars($content);
}
$id=intval($_GET['id']);
$sql = "select id,openid,dianpname,xxdizhi,dianp_img,dianp_lxfs from sk_erweima where id=$id";
$dpArr = $do->selectsql($sql);
$count=$do->getone("select count(*) count from sk_dppinglun where jiancha=1 and dianpid=$id");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>体验店详情</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/css.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/home.css?v1.8"/>
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/TouchSlide.1.1.js"></script>
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
		title: "<?php echo $dpArr[0]['dianpname'].'线下体验店'; ?>",
		desc: "<?php echo $dpArr[0]['xxdizhi']; ?>", // 分享描述
		link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>|tiyandian|<?php echo $dpArr[0]['id'];?>&connect_redirect=1#wechat_redirect',
		imgUrl: "<?php echo $cxtPath.$dpArr[0]['dianp_img']; ?>",
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
<style>
.clearfix img{ display:inline-block;}
.tt{position:absolute;top:80%;width:100%;height:20%;color:#fff;background-color:rgba(0,0,0,0.5);}
.info li {width:100%;border:1px solid #DEDEDE;height:4em;font-size:1.2em;}
.info li img{padding-top:0.6em;}
.preply{font-size:1.5rem}
.preply div:first-child{float:left;width:25%;text-align:center}
.preply div:last-child{float:left;width:70%;}
</style>
</head> 
<body style="margin:0px;padding:0px;background:#fff;">
<script>

jQuery(document).ready(function($){

	$('.cd-popup-trigger').on('click', function(event){
		event.preventDefault();
		$('.cd-popup').addClass('is-visible');
	});
	

	$('.cd-popup').on('click', function(event){
		if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
			event.preventDefault();
			$(this).removeClass('is-visible');
		}
	});
	
	$(document).keyup(function(event){
    	if(event.which=='27'){
    		$('.cd-popup').removeClass('is-visible');
	    }
    });

	$('#tijiao').click(function(){
		$.ajax({
			type: "POST",
			url: "tiyandian_into.php",
			data: {dianpid:"<?php echo quotes($_GET['id']);?>",neirong:$('#dppingluna').val()},
			success: function(data){
				if(data==1)
					alert('内容不能为空');
				else if(data==0)
					alert('评论已提交');
			}
		});
	});

	$('.dianzhan').unbind('click');
	$('.dianzhan').click(function(){
		if($(this).attr('data-zan') != -1){
			$this = $(this);
			$.ajax({
				type: "POST",
				url: "tiyandian_zan.php",
				data: {dianpid:$(this).attr('data-id')},
				success: function(data){
					$this.html(parseInt($this.attr('data-zan'))+1);
					$this.attr('data-zan',-1);
				}
			});
		}else
			alert("已赞过~");
	});
});
</script>
<div class="container-fluid" style="padding:0">
	<div class="view container" style="padding:0;">
		<div id="focus" class="focus">
			<div class="bd">
				<ul id="banner_li">
					<li><img name="ad_img" src="<?php echo $cxtPath.$dpArr[0]['dianp_img']; ?>"/></li>
					<?php if($dpArr[0]['dianp_img2']!=''){?>
					<li><img name="ad_img" src="<?php echo $cxtPath.$dpArr[0]['dianp_img2']; ?>"/></li>
					<?php }?>
				</ul>
			</div>
			<div class="tt row" style="margin:0;">
				<div class="col-xs-9 col-md-8">
					<h5><?php echo $dpArr[0]['dianpname'];?></h5>
					<h6>洋仆淘线下体验店</h6>
				</div>
				<div class="col-xs-3 col-md-4" style="padding:0.5em">
					<img class="pull-left xx" style="width:60%;" src="<?php echo $cxtPath;?>/wei/img/x_1.png"/>
					<h4><?php echo $count['count']!=0?$count['count']:'';?></h4>
				</div>
			</div>
		</div>
	</div>
	<div class="info clearfix">
		<ul style="margin-bottom:0">
			<li>
				<img class="img-responsive" src="<?php echo $cxtPath;?>/wei/img/x_2.png"/>
				<?php echo $dpArr[0]['xxdizhi'];?>
			</li>
			<li>
				<img class="img-responsive" src="<?php echo $cxtPath;?>/wei/img/x_3.png"/>
				<?php echo $dpArr[0]['dianp_lxfs'];?>
			</li>
		</ul>
	</div>
	<div class="pinglun clearfix">
		<div class="clearfix"><a class="cd-popup-trigger witiry right" href="#0" >写评论</a></div>
		<ul class="imgts clearfix" id="dppinglun">
			<?php
			$sql = "select pl.id,me.headimgurl,me.wei_nickname,from_unixtime(pl.shijian) shijian,zan,neirong from sk_dppinglun pl join sk_member me on pl.openid=me.open_id where pl.dianpid='$id' and pl.jiancha=1 and pl.pid=0 ORDER BY shijian DESC";
			$resultArr = $do->selectsql($sql);
			$str='';
			if(empty($resultArr)){
				echo "<li style='text-align:center;font-size:14px;color:#888;height:5em'>暂无评论</li>";
			}else {
				foreach($resultArr as $v) {
					$str.= "<li style='border-bottom:1px solid #d9d9d9;font-size:1.6em;padding-bottom:0.4em;'>";
					$str.= "<div class='left'><img style='border:none;' src='". $v['headimgurl']."'></div>";
					$str.= "<div class='centervv left'>";
					$str.= "<h3>".$v['wei_nickname']."<a class='dianzhan right' data-zan=".$v['zan']." data-id=".$v['id'].">".$v['zan']."</a></h3>";
					$str.= "<p>".$v['neirong']."</p>";
					$str.= "<h4>".$v['shijian']."</h4>";
					$str.="</div>";
					$preply=$do->selectsql("select openid,neirong from sk_dppinglun where pid=".$v['id']." and jiancha=1");
					if(!empty($preply)) {
						foreach($preply as $p) {
							$str .= "<div class='preply'><div>";
							if($p['openid']==$dpArr[0]['openid']) {
								$str .= "商家回复";
							}else{
								$member=$do->getone("select wei_nickname from sk_member where open_id='".$p['openid']."'");
								$str.=$member['wei_nickname']!=''?$member['wei_nickname']:"匿名";
							}
							$str.=":</div><div>".$p['neirong']."</div></div>";
						}
					}
					$str.="</li>";
				}
				echo $str;
			}
			?>
		<!-- <li>
		<img src="img/xianxia_07.jpg"/ class="left">
		<div class="centervv left">
		<h3>我老恭<a class="dianzhan right">5</a></h3>
		<p>啦啦啦啦啦啦啦啦啦啦啦啦阿里啦啦啦阿拉啦啦啦啦啦啦啦啊啦啦</p>
		<h4>今天</h4></div></li>
		<li>
		<img src="img/xianxia_07.jpg"/ class="left">
		<div class="centervv left">
		<h3>我老恭<a class="dianzhan right">5</a></h3>
		<p>啦啦啦啦啦啦啦啦啦啦啦啦阿里啦啦啦阿拉啦啦啦啦啦啦啦啊啦啦</p>
		<h4>今天</h4></div></li> -->
		</ul>
	</div>
	<div class="cd-popup" role="alert">
		<div class="cd-popup-container clearfix" style="margin:8em auto">
			<textarea class="pingrr clearfix" id="dppingluna" placeholder="评论将由商城后台工作员筛选后显示，对所有人可见。"></textarea>

			<a href="#" class="cd-popup-close img-replace" style="border:none;background:#fe7182;border-radius:3px;color:#fff;text-align:center;height:40px;line-height:40px;margin:6px auto;width:80%" id="tijiao">提 交</a>
		</div>
	</div>
</div>
</body>
<script>
	if($(".view").width()>700) {
		$(".view").css("width", "50%");
		$(".view img").width($(".view").width());
		$(".xx").css("width", "40%");
		$(".view h4").css("font-size","50px");
		$(".view h5").css("font-size","30px");
		$(".view h6").css("font-size","20px");
	}
	$(".info li img").css("width",$(".info li").height()*0.7);
	TouchSlide({
		slideCell:"#focus",
		titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
		mainCell:".bd ul",
		delayTime:600,
		interTime:4000,
		effect:"leftLoop",
		//autoPlay:true,//自动播放
		//autoPage:true, //自动分页
		switchLoad:"_src" //切换加载，真实图片路径为"_src"
	});
</script>
</html>