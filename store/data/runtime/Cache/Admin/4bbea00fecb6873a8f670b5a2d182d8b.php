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
<script></script>
</head>
<body class="J_scroll_fixed">
	<div class="wrap J_check_wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="javascript:;">所有终端</a></li>
		</ul>
		<form class="well form-search" method="post" action="<?php echo U('Store/index');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						&nbsp;
						<select name="shenhe" style="width:70px">
							<option value="">状态</option>
							<option value="0">待审</option>
							<option value="1">通过</option>
							<option value="2">不通过</option>
						</select>
						&nbsp;编号：
						<input type="text" name="bianhao" style="width: 200px;" value="<?php echo ($formget["bianhao"]); ?>" placeholder="请输入编号...">
						&nbsp;名称：
						<input type="text" name="name" style="width: 200px;" value="<?php echo ($formget["name"]); ?>" placeholder="请输入名称...">
						&nbsp;
						<select name="order" style="width:100px">
							<option value="">排序</option>
							<option value="pop">收益</option>
						</select>
						<input type="submit" class="btn btn-primary" value="搜索" />
						<input type="reset" class="btn btn-primary" value="重置" />
					</span>
				</div>
			</div>
		</form>
		<table class="table table-hover table-bordered table-list">
			<thead>
				<tr>
					<th>店铺缩略图</th>
					<th>终端编号</th>
					<th>终端名称</th>
					<th>收益</th>
					<th>状态</th>
					<th>查看</th>
					<th>统计</th>
					<th>操作</th>
				</tr>
			</thead>
			<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
				<td><img src="../<?php echo ($vo["dianp_img"]); ?>" style="max-width:200px;max-height:200px;"/></td>
				<td><?php echo ($vo["bianhao"]); ?></td>
				<td><a href="<?php echo U('Store/info',array('sid'=>$vo['id']));?>"><?php echo ($vo["dianpname"]); ?></a></td>
				<td>￥<?php echo ((isset($vo["pop"]) && ($vo["pop"] !== ""))?($vo["pop"]):0); ?></td>
				<td><?php switch($vo["shenhe"]): case "1": ?>通过<?php break; case "2": ?>不通过<?php break; default: ?>待审<?php endswitch;?></td>
				<td>
					<a href="<?php echo U('Store/partner',array('sid'=>$vo['id']));?>">盟友信息</a>&nbsp;|&nbsp;
					<a href="<?php echo U('Store/income',array('sid'=>$vo['id']));?>">收益信息</a>&nbsp;|&nbsp;
					<a href="#">修改</a>
				</td>
				<td>
					<a href="<?php echo U('Store/order_count',array('sid'=>$vo['id']));?>">订单统计</a><br>
					<a href="<?php echo U('Store/partner_count',array('sid'=>$vo['id']));?>">盟友统计</a><br>
					<a href="<?php echo U('Store/action_count',array('sid'=>$vo['id']));?>">盟友访问统计</a><br>
					<a href="<?php echo U('Store/router_count',array('sid'=>$vo['id']));?>">客流统计</a><br>
				</td>
				<td>
					<a href="<?php echo U('Store/cfcoupon',array('sid'=>$vo['id'],'sendid'=>$vo['id']));?>">拆分现金券</a><br>
					<a href="<?php echo U('Store/comment',array('sid'=>$vo['id']));?>">评论管理</a><br>
					<a href="javascript:window.parent.openapp('<?php echo U('Goods/index',array('sid'=>$vo['id']));?>','index','商品管理');">商品台签</a><br>
				</td>
			</tr><?php endforeach; endif; ?>
			<tfoot>
				<tr>
					<th>店铺缩略图</th>
					<th>终端编号</th>
					<th>终端名称</th>
					<th>收益</th>
					<th>状态</th>
					<th>查看</th>
					<th>统计</th>
					<th>操作</th>
				</tr>
			</tfoot>
		</table>
		<div class="pagination pull-right"><span href="#" class="btn btn-primary disabled"><?php echo ($count); ?>条记录</span><?php echo ($Page); ?></div>
	</div>
	<script src="/store/statics/js/common.js"></script>
	<script>
		$("select[name='shenhe']").find("option").each(function(){
			if($(this).val()=="<?php echo ($formget["shenhe"]); ?>")
				$(this).attr("selected",true);
		});
		$("select[name='order']").find("option").each(function(){
			if($(this).val()=="<?php echo ($formget["order"]); ?>")
				$(this).attr("selected",true);
		});
		$("input[type='reset']").click(function(){
			$(this).siblings("input").not("input[type='submit']").attr("value","");
			$("select").find("option").attr("selected",false);
		});
	</script>
</body>
</html>