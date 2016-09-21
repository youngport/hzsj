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
<style>
	.reply{width:95%;resize: none;}
</style>
</head>
<body class="J_scroll_fixed">
	<div class="wrap J_check_wrap">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('store/index');?>">所有终端</a></li>
			<li class="active"><a href="#">评论管理</a></li>
		</ul>
		<form class="well form-search" method="post" action="<?php echo U('store/comment');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						&nbsp;用户：
						<input type="text" name="wei_nickname" style="width: 200px;" value="<?php echo ($formget["wei_nickname"]); ?>" placeholder="请输入用户名称...">
						&nbsp;内容：
						<input type="text" name="neirong" style="width: 200px;" value="<?php echo ($formget["neirong"]); ?>" placeholder="请输入内容...">
						<input type="hidden" name="sid" value="<?php echo ($sid); ?>">
						<input type="submit" class="btn btn-primary" value="搜索" />
						<input type="reset" class="btn btn-primary" value="重置" />
					</span>
				</div>
			</div>
		</form>
		<table class="table table-hover table-bordered table-list">
			<thead>
				<tr>
					<th>用户</th>
					<th>内容</th>
					<th>时间</th>
					<th>点赞数</th>
					<th>精选</th>
					<th>我的回复</th>
					<th>审核状态</th>
					<th>操作</th>
				</tr>
			</thead>
			<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
				<td><?php echo ($vo["wei_nickname"]); ?></td>
				<td><?php echo ($vo["neirong"]); ?></td>
				<td><?php echo (date('Y-m-d H:i:s',$vo["shijian"])); ?></td>
				<td><?php echo ($vo["zan"]); ?></td>
				<td>
					<label class="radio">
						<input class="zan" type="radio" name="zan<?php echo ($vo["id"]); ?>" data-cid="<?php echo ($vo["id"]); ?>" value="1" <?php if(($vo["is_jx"]) == "1"): ?>checked<?php endif; ?>>
						是
					</label>
					<label class="radio">
						<input class="zan" type="radio" name="zan<?php echo ($vo["id"]); ?>" data-cid="<?php echo ($vo["id"]); ?>" value="0" <?php if(($vo["is_jx"]) == "0"): ?>checked<?php endif; ?>>
						否
					</label>
				</td>
				<td>
					<?php echo $dppinglun->where(array('pid'=>$vo['id']))->getFIeld('neirong'); ?>
				</td>
				<td><?php switch($vo["jiancha"]): case "0": ?>不通过<?php break; case "1": ?>通过<?php break; endswitch;?></td>
				<td>
					<a href="#myModal" data-toggle="modal" data-cid="<?php echo ($vo["id"]); ?>">回复</a>
				</td>
			</tr><?php endforeach; endif; ?>
			<tfoot>
				<tr>
					<th>用户</th>
					<th>内容</th>
					<th>时间</th>
					<th>点赞数</th>
					<th>精选</th>
					<th>我的回复</th>
					<th>审核状态</th>
					<th>操作</th>
				</tr>
			</tfoot>
		</table>
		<div class="pagination pull-right"><span href="#" class="btn btn-primary disabled"><?php echo ($count); ?>条记录</span><?php echo ($Page); ?></div>
	</div>
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">回复</h3>
		</div>
		<div class="modal-body">
			<textarea rows="3" name="comment_reply" class="reply"></textarea>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
			<button class="btn btn-primary" name="reply_submit">发送</button>
		</div>
	</div>
	<script src="/store/statics/js/common.js"></script>
	<script>
		$("input[type='reset']").click(function(){
			$(this).siblings("input").not("input[type='submit']").attr("value","");
			$("select").find("option").attr("selected",false);
		});
		$(".zan").click(function(){
			var cid=$(this).attr("data-cid");
			$.ajax({
				type:"POST",
				url:"<?php echo U('store/is_jx');?>",
				data:{sid:"<?php echo ($_GET['sid']); ?>",cid:cid},
				success:function(data){
					if(data.status!=1){
						alert("修改失败");
					}
				}
			});
		});
		$("a[data-toggle='modal']").click(function(){
			$("button[name='reply_submit']").attr("data-cid",$(this).attr("data-cid"));
		});
		$("button[name='reply_submit']").click(function(){
			var reply=$(".reply").val();
			var cid=Number($(this).attr("data-cid"));
			if(reply==''){
				alert("内容不能为空");
			}else if(cid<1){
				alert("ID非法");
			}else {
				$.ajax({
					type: "POST",
					url: "<?php echo U('store/comment_reply');?>",
					data: {sid: "<?php echo ($_GET['sid']); ?>", cid: cid,reply:reply},
					success:function(data){
						if(data.status!=1){
							alert("发送失败");
						}
					}
				});
				$('#myModal').modal('hide');
			}
		});
	</script>
</body>
</html>