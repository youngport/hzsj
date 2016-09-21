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
			<li class="active"><a href="javascript:;">盟友信息</a></li>
		</ul>
		<table class="table table-hover table-bordered table-list" style="margin-bottom:10px">
			<thead>
			<tr>
				<th colspan="6" style="font-size:25px">洋仆淘--<?php echo ($info["dianpname"]); ?></th>
			</tr>
			<tr>
				<th>一级盟友人数</th>
				<th><?php echo ($info["now"]["zcount"]); ?>人</th>
				<th>一级会员</th>
				<th><?php echo ($info["now"]["zmcount"]); ?>人</th>
				<th>一级会员转化率</th>
				<th><?php echo ($info["now"]["mper"]); ?>%</th>
			</tr>
			<tr>
				<th>总盟友人数</th>
				<th><?php echo ($info["total"]["zcount"]); ?>人</th>
				<th>总会员</th>
				<th><?php echo ($info["total"]["zmcount"]); ?>人</th>
				<th>总会员转化率</th>
				<th><?php echo ($info["total"]["mper"]); ?>%</th>
			</tr>
			</thead>
		</table>
		<form class="well form-search" method="post" action="<?php echo U('Store/partner');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						&nbsp;微信名称：
						<input type="text" name="nickname" style="width: 150px;" value="<?php echo ($formget["nickname"]); ?>" placeholder="请输入名称...">
						&nbsp;
						<select name="status" style="width:100px">
							<option value="">会员状态</option>
							<option value="1">会员</option>
							<option value="0">普通用户</option>
							<option value="2">店铺会员</option>
						</select>
						&nbsp;注册时间：
						<input type="text" name="add_start_time" class="J_date" value="<?php echo ($formget["add_start_time"]); ?>" style="width: 80px;" autocomplete="off">-
						<input type="text" class="J_date" name="add_end_time" value="<?php echo ($formget["add_end_time"]); ?>" style="width: 80px;" autocomplete="off">
						&nbsp;最近登录：
						<input type="text" name="start_time" class="J_date" value="<?php echo ($formget["start_time"]); ?>" style="width: 80px;" autocomplete="off">-
						<input type="text" class="J_date" name="end_time" value="<?php echo ($formget["end_time"]); ?>" style="width: 80px;" autocomplete="off">
						&nbsp;
						<select name="order" style="width:80px">
							<option value="">排序：</option>
							<option value="pidnum">盟友人数</option>
							<option value="last_login">登录时间</option>
						</select>
						<input type="hidden" name="sid" value="<?php echo ($sid); ?>"/>
						<input type="submit" class="btn btn-primary" value="搜索" />
						<input type="reset" class="btn btn-primary" value="重置" />
					</span>
				</div>
			</div>
		</form>
		<table class="table table-hover table-bordered table-list">
			<thead>
				<tr>
					<th>微信昵称</th>
					<th>盟友人数</th>
					<th>注册时间</th>
					<th>最近登陆</th>
					<th>状态</th>
				</tr>
			</thead>
			<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
				<td><?php echo ((isset($vo["wei_nickname"]) && ($vo["wei_nickname"] !== ""))?($vo["wei_nickname"]):'匿名'); ?></td>
				<td><?php echo ($vo["pidnum"]); ?>人</td>
				<td><?php echo ($vo["login_time"]); ?></a></td>
				<td><?php echo (date("Y-m-d H:i:s",$vo["last_login"])); ?></td>
				<td><?php switch($vo["jointag"]): case "1": ?>会员<?php break; case "2": ?>店铺会员<?php break; default: ?>普通用户<?php endswitch;?></td>
			</tr><?php endforeach; endif; ?>
			<tfoot>
				<tr>
					<th>微信昵称</th>
					<th>盟友人数</th>
					<th>注册时间</th>
					<th>最近登陆</th>
					<th>状态</th>
				</tr>
			</tfoot>
		</table>
		<div class="pagination pull-right"><span href="#" class="btn btn-primary disabled"><?php echo ($count); ?>条记录</span><?php echo ($Page); ?></div>
	</div>
	<script src="/store/statics/js/common.js"></script>
	<script>
		$("select[name='status']").find("option").each(function(){
			if($(this).val()=="<?php echo ($formget["status"]); ?>")
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