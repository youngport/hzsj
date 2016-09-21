<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/store/statics/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/store/statics/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/store/statics/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/store/statics/simpleboot/font-awesome/4.2.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		.length_3{width: 180px;}
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/store/statics/simpleboot/font-awesome/4.2.0/css/font-awesome-ie7.min.css">
	<![endif]-->
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "/store/",
    JS_ROOT: "statics/js/",
    TOKEN: ""
};
</script>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/store/statics/js/jquery.js"></script>
    <script src="/store/statics/js/wind.js"></script>
    <script src="/store/statics/simpleboot/bootstrap/js/bootstrap.min.js"></script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
</head>
<body class="J_scroll_fixed">
	<div class="wrap J_check_wrap">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('Store/index');?>" target="_self">所有终端</a></li>
			<li class="active"><a href="javascript:;">终端信息</a></li>
		</ul>
		<div id="myCarousel" class="carousel slide pull-left" style="width:45%">
			<!-- Carousel items -->
			<div class="carousel-inner">
				<div class="active item"><img src="..<?php echo ($info["dianp_img"]); ?>" style="width:100%"/></div>
				<?php if(($info["dianp_img2"]) != ""): ?><div class="item"><img src="..<?php echo ($info["dianp_img2"]); ?>" style="width:100%"/></div><?php endif; ?>
			</div>
			<?php if(($info["dianp_img2"]) != ""): ?><!-- Carousel nav -->
			<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
			<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a><?php endif; ?>
		</div>
		<div class="clearfix"></div>
		<div class="hero-unit">
			<div class="pull-left">
				<h3>店铺名称:<?php echo ($info["dianpname"]); ?></h3>
				<h5>编号:<?php echo ($info["bianhao"]); ?></h5>
				<h5>地址:<?php echo ($info["xxdizhi"]); ?></h5>
				<h5>所有者:<?php echo ((isset($info["wei_nickname"]) && ($info["wei_nickname"] !== ""))?($info["wei_nickname"]):'匿名'); ?></h5>
				<h5>微信登录帐号:<?php echo ((isset($info["weixin_account"]) && ($info["weixin_account"] !== ""))?($info["weixin_account"]):'未填写'); ?></h5>
				<h5>状态:<?php switch($vo["shenhe"]): case "1": ?>通过<?php break; case "2": ?>不通过<?php break; default: ?>待审<?php endswitch;?></h5>
			</div>
			<div class="pull-right">
				<ul class="thumbnails">
					<li class="span4">
						<div class="thumbnail">
							<img src="../<?php echo ($info["dizhi"]); ?>" style="width:100%">
							<h4 class='text-center'>店铺二维码</h4>
						</div>
					</li>
				</ul>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<script src="/store/statics/js/common.js"></script>
	<script>
		$('.carousel').carousel({
			interval: 2000
		})
	</script>
</body>
</html>