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
<style type="text/css">
.col-auto { overflow: auto; _zoom: 1;_float: left;}
.col-right { float: right; width: 210px; overflow: hidden; margin-left: 6px; }
.table th, .table td {vertical-align: middle;}
.picList li{margin-bottom: 5px;}
</style>
</head>
<body class="J_scroll_fixed">
<div class="wrap J_check_wrap">
  <ul class="nav nav-tabs">
     <li><a href="<?php echo U('Asset/index');?>">附件列表</a></li>
     <li class="active"><a href="<?php echo U('Asset/add');?>"  target="_self">添加附件</a></li>
  </ul>
  <form name="myform" id="myform" action="<?php echo U('Asset/add_post');?>" method="post" class="form-horizontal J_ajaxForms" enctype="multipart/form-data">
  <div class="col-auto">
    <div class="table_full">
      <table class="table table-bordered">
		  <tr>
			  <th width="80">分类</th>
			  <td>
				  <select name="cate" multiple="multiple" required>
					  <option value="0">其他</option>
					  <option value="1">文档</option>
					  <option value="2">图片</option>
					  <option value="3">视频</option>
				  </select>
			  </td>
		  </tr>
		  <tr>
			  <th width="80">上传 </th>
			  <td>
				  <input type='file' id="file" required/>
				  <div id="bar" class="text-center" style="background:#00ff00"></div>
			  </td>
		  </tr>
		  <tr>
			  <th width="80">备注 </th>
			  <td><textarea name='meta' id='meta' style='width:98%;height:50px;' placeholder='请填写备注(可选)'></textarea></td>
		  </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="form-actions">
        <button class="btn btn-primary btn_submit J_ajax_submit_btn"type="submit">提交</button>
        <a class="btn" href="<?php echo U('Asset/index');?>">返回</a>
  </div>
 </form>
</div>
<script type="text/javascript" src="/store/statics/js/common.js"></script>
<script type="text/javascript" src="/store/statics/js/content_addtop.js"></script>
<script type="text/javascript">
$(function () {
	//setInterval(function(){public_lock_renewal();}, 10000);
	$(".J_ajax_close_btn").on('click', function (e) {
	    e.preventDefault();
	    Wind.use("artDialog", function () {
	        art.dialog({
	            id: "question",
	            icon: "question",
	            fixed: true,
	            lock: true,
	            background: "#CCCCCC",
	            opacity: 0,
	            content: "您确定需要关闭当前页面嘛？",
	            ok:function(){
					setCookie("refersh_time",1);
					window.close();
					return true;
				}
	        });
	    });
	});
	/////---------------------
	Wind.use('validate', 'ajaxForm', 'artDialog', function () {
		//javascript
		var form = $('form.J_ajaxForms');
		//ie处理placeholder提交问题
		if ($.browser.msie) {
			form.find('[placeholder]').each(function () {
				var input = $(this);
				if (input.val() == input.attr('placeholder')) {
					input.val('');
				}
			});
		}

		var formloading=false;
		//表单验证开始
		form.validate({
			//是否在获取焦点时验证
			onfocusout:false,
			//是否在敲击键盘时验证
			onkeyup:false,
			//当鼠标掉级时验证
			onclick: false,
			//验证错误
			showErrors: function (errorMap, errorArr) {
				//errorMap {'name':'错误信息'}
				//errorArr [{'message':'错误信息',element:({})}]
				try{
					$(errorArr[0].element).focus();
					art.dialog({
						id:'error',
						icon: 'error',
						lock: true,
						fixed: true,
						background:"#CCCCCC",
						opacity:0,
						content: errorArr[0].message,
						cancelVal: '确定',
						cancel: function(){
							$(errorArr[0].element).focus();
						}
					});
				}catch(err){
				}
			},
			//验证规则
			rules: {'cate':{required:1}},
			//验证未通过提示消息
			messages: {'cate':{required:'请选择分类'}},
			//给未通过验证的元素加效果,闪烁等
			highlight: false,
			//是否在获取焦点时验证
			onfocusout: false,
			//验证通过，提交表单
			submitHandler: function (forms) {
				if(formloading) return;
				$(forms).ajaxSubmit({
					url: form.attr('action'), //按钮上是否自定义提交地址(多按钮情况)
					dataType: 'json',
					beforeSubmit: function (arr, $form, options) {
						formloading=true;
					},
					success: function (data, statusText, xhr, $form) {
						formloading=false;
						if(data.status){
							setCookie("refersh_time",1);
							//添加成功
							Wind.use("artDialog", function () {
								art.dialog({
									id: "succeed",
									icon: "succeed",
									fixed: true,
									lock: true,
									background: "#CCCCCC",
									opacity: 0,
									content: data.info,
									button:[
										{
											name: '继续添加？',
											callback:function(){
												reloadPage(window);
												return true;
											},
											focus: true
										},{
											name: '返回列表页',
											callback:function(){
												location="<?php echo U('Asset/index');?>";
												return true;
											}
										}
									]
								});
							});
						}else{
							isalert(data.info);
						}
					}
				});
			}
		});
	});
	////-------------------------
	$('td').css("text-align","left");
	$("#file").change(function(){
		var file = $("#file")[0].files[0];
		cutfile(file,0);
	});
	function cutfile(file,i){
		const shardSize=5*1024*1024;
		var name=file.name;
		var size=file.size;
		shardCount = Math.ceil(size / shardSize);
		if(i >= shardCount){
			return;
		}
		var start = i * shardSize;
		var end = Math.min(size, start + shardSize);
		var form = new FormData();
		form.append("data", file.slice(start,end));  //slice方法用于切出文件的一部分
		form.append("name", name);
		form.append("total", shardCount);  //总片数
		form.append("index", i+1);        //当前是第几片
		form.append("size",size);
		//Ajax提交
		$.ajax({
			url: "<?php echo U('Asset/cutfile');?>",
			type: "POST",
			data: form,
			async: true,        //异步
			processData: false,  //很重要，告诉jquery不要对form进行处理
			contentType: false,  //很重要，指定为false才能形成正确的Content-Type
			success: function(data){
				if(data!='no'){
					i = data++;
					var num = Math.ceil(i*100 / shardCount);
					$("#bar").css("width",num+'%');
					$("#bar").text(num+'%');
					cutfile(file,i);
				}
			}
		});
	}
});
</script>
</body>
</html>