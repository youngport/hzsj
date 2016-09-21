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
     <li><a href="<?php echo U('Store/index');?>">终端列表</a></li>
     <li class="active"><a href="<?php echo U('Store/cscoupon');?>" target="_self">拆分现金券</a></li>
  </ul>
  <form name="myform" id="myform" action="<?php echo u('Store/cfcoupon_post');?>" method="post" class="form-horizontal J_ajaxForms" enctype="multipart/form-data">
  <div class="col-auto">
    <div class="table_full">
      <table class="table table-bordered">
		  <tr>
			  <th width="80">现金券列表</th>
			  <td width="450">
				  <select name="mcoupon" multiple="multiple" required>
					  <?php if(is_array($mcoupon)): foreach($mcoupon as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>" data-money="<?php echo ($vo["money"]); ?>"><?php echo ($vo["money"]); ?>元券</option><?php endforeach; endif; ?>
				  </select>
			  </td>
			  			  <td rowspan="5" style="font-size:15px">
<span style="font-weight:bold">现金券使用说明：</span><br/>
功能：洋仆淘现金券可用于购买样品和拆分为优惠券。<br/><br/>
购买样品:<br/>
洋仆淘现金券可用于在商城购买商品，每次购买金额不能超过500元，<br/>
每次使用需要支付0.1元。<br/><br/>
拆分为优惠券:<br/>
1、洋仆淘现金券可以拆分为优惠券发送给一级盟友。<br/>
2、在拆分时需要选取盟友，填写拆分限额（即：满多少可用）、减免金额（减多少）、<br/>
有效时间（优惠券生效时间、失效时间）。<br/>
3、未选取盟友默认发送给所有盟友。<br/>
4、拆分限额需大于减免金额。<br/><br/>
<span style="margin-left:300px;">现金券解释权归洋仆淘所有</span>
</td>
		  </tr>
		  <tr>
			  <th width="80">用户</th>
			  <td>
				  <select name="rec" multiple="multiple">
					  <?php if(is_array($partner)): foreach($partner as $key=>$vo): ?><option value="<?php echo ($vo["open_id"]); ?>"><?php echo ((isset($vo["wei_nickname"]) && ($vo["wei_nickname"] !== ""))?($vo["wei_nickname"]):'匿名 '); ?></option><?php endforeach; endif; ?>
				  </select>
			  </td>
		  </tr>
            <tr>
              <th width="80">拆分限额 </th>
              <td>
              	<input type="text" style="width:150px;" name="xz" id="xz"  required value="" class="input input_hd J_title_color" placeholder="请输入限制金额"/>
              	<span class="must_red">*</span>
              </td>
            </tr>
            <tr>
              <th width="80">减免金额</th>
              <td>
				  <input type="text" style="width:150px;" name="js" id="js"  required value="" class="input input_hd J_title_color" placeholder="请输入减免金额"/>
				  <span class="must_red">*</span>
			  </td>
            </tr>
			  <tr>
				  <th width="80">有效时间 </th>
				  <td>
					  <input type="text" name="start_time" class="J_date" style="width: 80px;" autocomplete="off">-
					  <input type="text" class="J_date" name="end_time" style="width: 80px;" autocomplete="off">
					  <span class="must_red">*</span>
				  </td>
			  </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="form-actions">
	  	<input type="hidden" value="<?php echo ($sid); ?>" name="sid"/>
        <button class="btn btn-primary btn_submit J_ajax_submit_btn"type="submit">提交</button>
        <a class="btn" href="<?php echo U('Store/index');?>">返回</a>
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
	        
	            //编辑器
	            editorcontent = new baidu.editor.ui.Editor();
	            editorcontent.render( 'content' );
	            try{editorcontent.sync();}catch(err){};
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
					//errorArr [{'Store':'错误信息',element:({})}]
					try{
						$(errorArr[0].element).focus();
						art.dialog({
							id:'error',
							icon: 'error',
							lock: true,
							fixed: true,
							background:"#CCCCCC",
							opacity:0,
							content: errorArr[0].Store,
							cancelVal: '确定',
							cancel: function(){
								$(errorArr[0].element).focus();
							}
						});
					}catch(err){
					}
	            },
	            //验证规则
	            rules: {'xz':{required:1}},
	            //验证未通过提示消息
	            Stores: {'xz':{required:'请输入限额'},'js':{required:'请输入金额'}},
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
													location='<?php echo U('Store/index');?>';
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
});
</script>
</body>
</html>