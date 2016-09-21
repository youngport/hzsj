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
			<li class="active"><a href="<?php echo U('tasset/index');?>">培训资源列表</a></li>
			<?php if(sp_auth_check($admin['id'],"/admin/tasset/add")){ ?>
				<li><a href="<?php echo U('tasset/add');?>">添加资源</a></li>
			<?php } ?>
		</ul>
		<form class="well form-search" method="post" action="<?php echo U('tasset/index');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						&nbsp;
						<select name="cate" style="width:100px">
							<option value="">状态</option>
							<option value="0">导购必看</option>
							<option value="1">分销必知</option>
						</select>
						&nbsp;文件名：
						<input type="text" name="name" style="width: 200px;" value="<?php echo ($formget["name"]); ?>" placeholder="请输入文件名...">
						<input type="submit" class="btn btn-primary" value="搜索" />
						<input type="reset" class="btn btn-primary" value="重置" />
					</span>
				</div>
			</div>
		</form>
		<table class="table table-hover table-bordered table-list">
			<thead>
				<tr>
					<th>ID</th>
					<th>分类</th>
					<th>封面图片</th>
					<th>文件名</th>
					<th>后缀</th>
					<th>大小</th>
					<th>发布时间</th>
					<th>备注</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
				<td><?php echo ($vo["id"]); ?></td>
				<td><?php switch($vo["cate"]): case "0": ?>导购必看<?php break; case "1": ?>分销必知<?php break; endswitch;?></td>
				<td><img src="<?php echo ($vo["img"]); ?>" style="width:70px;"/></td>
				<td><?php echo ($vo["name"]); ?></td>
				<td><?php echo ($vo["suffix"]); ?></td>
				<td><?php echo ($vo["size"]); ?></td>
				<td><?php echo (date('Y-m-d H:i:s',$vo["time"])); ?></td>
				<td><?php echo ($vo["meta"]); ?></td>
				<td><?php switch($vo["status"]): case "0": ?>停用<?php break; case "1": ?>正常<?php break; endswitch;?></td>
				<td>
					<a href="<?php echo ($vo["path"]); ?>">下载</a>
					<?php if(sp_auth_check($admin['id'],"/admin/tasset/delete")){ ?>
						&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo U('delete',array('id'=>$vo['id']));?>">删除</a>
					<?php } ?>
				</td>
			</tr><?php endforeach; endif; ?>
			<tfoot>
				<tr>
					<th>ID</th>
					<th>分类</th>
					<th>封面图片</th>
					<th>文件名</th>
					<th>后缀</th>
					<th>大小</th>
					<th>发布时间</th>
					<th>备注</th>
					<th>状态</th>
					<th>操作</th>
				</tr>
			</tfoot>
		</table>
		<div class="pagination pull-right"><span href="#" class="btn btn-primary disabled"><?php echo ($count); ?>条记录</span><?php echo ($Page); ?></div>
	</div>
</body>
<script src="/store/statics/js/common.js"></script>
<script>
	$("select[name='cate']").find("option").each(function(){
		if($(this).val()=="<?php echo ($formget["cate"]); ?>")
			$(this).attr("selected",true);
	});
	$("input[type='reset']").click(function(){
		$(this).siblings("input").not("input[type='submit']").attr("value","");
		$("select").find("option").attr("selected",false);
	});
</script>
</html>