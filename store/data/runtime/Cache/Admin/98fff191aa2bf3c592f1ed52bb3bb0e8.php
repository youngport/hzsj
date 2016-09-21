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
			<li class="active"><a href="<?php echo U('Usermessage/index');?>">消息列表</a></li>
			<li><a href="<?php echo U('Usermessage/add',array('message'=>1));?>">推送短消息</a></li>
			<li><a href="<?php echo U('Usermessage/add');?>">推送文章</a></li>
		</ul>
		<form class="well form-search" method="post" action="<?php echo U('Usermessage/index');?>">
			<input type="hidden" name="my" value="<?php echo ($my); ?>"/>
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						&nbsp;用户：
						<input type="text" name="rec" style="width: 200px;" value="<?php echo ($formget["rec"]); ?>" placeholder="请输入用户名称...">
						&nbsp;标题：
						<input type="text" name="title" style="width: 200px;" value="<?php echo ($formget["title"]); ?>" placeholder="请输入标题...">
						<input type="submit" class="btn btn-primary" value="搜索" />
						<input type="reset" class="btn btn-primary" value="重置" />
					</span>
				</div>
			</div>
		</form>
		<table class="table table-hover table-bordered table-list">
			<thead>
				<tr>
					<th>发送方</th>
					<th>接收用户</th>
					<th>类型</th>
					<th>发布时间</th>
					<th>操作</th>
				</tr>
			</thead>
			<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
				<td><?php echo ((isset($vo["dianpname"]) && ($vo["dianpname"] !== ""))?($vo["dianpname"]):"系统"); ?></td>
				<td><?php if(!empty($vo["wei_nickname"])): echo ($vo["wei_nickname"]); endif; if(!empty($vo["recpid"])): ?>&nbsp;&nbsp;发送方的下级<?php endif; ?></td>
				<td><?php if(($vo["title"] == '') OR ($vo["abst"] == '') ): ?>短消息<?php else: ?>文章<?php endif; ?></td>
				<td><?php echo (date('Y-m-d',$vo["create_time"])); ?></td>
				<td>
					<a href="<?php echo U('Usermessage/info',array('id'=>$vo['id']));?>">查看</a>
					&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo U('Usermessage/edit',array('id'=>$vo['id']));?>">修改</a>
					&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo U('Usermessage/delete',array('id'=>$vo['id']));?>">删除</a>
				</td>
			</tr><?php endforeach; endif; ?>
			<tfoot>
				<tr>
					<th>发送方</th>
					<th>接收用户</th>
					<th>类型</th>
					<th>发布时间</th>
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
		if("<?php echo ($my); ?>"==1) {
			$(".nav-tabs").find("li").removeClass("active");
			$(".nav-tabs").find("li:eq(1)").addClass("active");
		}
	</script>
</body>
</html>