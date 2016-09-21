<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=7" />
        <link href="__ROOT__/statics/admin/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="__ROOT__/statics/css/dialog.css" rel="stylesheet" type="text/css" />
        <link href="__ROOT__/statics/js/magiczoomplus/magiczoomplus.css" rel="stylesheet" type="text/css" />

        <!-- <script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/jquery-1.4.2.min.js"></script> -->
        <script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/jquery-2.0.3.min.js"></script>
        <script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/plugins/formvalidator.js"></script>
        <script language="javascript" type="text/javascript" src="__ROOT__/statics/js/jquery/plugins/formvalidatorregex.js"></script>

        <script language="javascript" type="text/javascript" src="__ROOT__/statics/admin/js/admin_common.js"></script>
        <script language="javascript" type="text/javascript" src="__ROOT__/statics/js/dialog.js"></script>
        <script language="javascript" type="text/javascript" src="__ROOT__/statics/js/iColorPicker.js"></script>
        <script language="javascript" type="text/javascript" src="__ROOT__/statics/js/My97DatePicker/WdatePicker.js"></script>
        <script language="javascript" type="text/javascript" src="__ROOT__/statics/js/magiczoomplus/magiczoomplus.js"></script>

        <script type="text/javascript">

            var URL = '__URL__';
            var ROOT_PATH = '__ROOT__';
            var APP = '__APP__';
            var lang_please_select = "<?php echo (L("please_select")); ?>";
            var def = <?php echo ($def); ?>;
            $(function ($) {
                $("#ajax_loading").ajaxStart(function () {
                    $(this).show();
                }).ajaxSuccess(function () {
                    $(this).hide();
                });
            });

        </script>
        <title><?php echo (L("website_manage")); ?></title>
    </head>
    <body>
        <div id="ajax_loading">提交请求中，请稍候...</div>
        <?php if($show_header != false): if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav">
                    <div class="content-menu ib-a blue line-x">
                        <?php if(!empty($big_menu)): ?><a class="add fb" href="<?php echo ($big_menu["0"]); ?>"><em><?php echo ($big_menu["1"]); ?></em></a>　<?php endif; ?>
                    </div>
                </div><?php endif; endif; ?>
<link href="__ROOT__/statics/admin/css/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
$(function(){	
	$(".main_con").hover(
			  function () {
			    $(this).css("background","#ffffcc");
			  },
			  function () {
			    $(this).css("background","#ffffff");
			  }
	);
})
</script>
<div style="padding:10px; overflow:hidden;">
	<div class="main_top" style="clear:both;">			
		<?php if($no_security_info == 1): ?><h4>安全提示</h4>	
			 <?php if(is_array($security_info)): $i = 0; $__LIST__ = $security_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><p class="red" style="font-size:14px;">※　<?php echo ($v); ?></p><?php endforeach; endif; else: echo "" ;endif; endif; ?>	
		<h4>网站无法显示</h4>	
		<p class="green">网站以前正常显示，现在无法显示请清除缓存试试，【<a href="<?php echo u('cache/index');?>"><?php echo (L("flush_cache")); ?></a>】</p>
		
	</div>
	<div style="width:50%;" class="fl">

		<div class="main_con fl">
			<h6>配置信息</h6>
			<div class="content">				
				<?php if(is_array($server_info)): $i = 0; $__LIST__ = $server_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><p><?php echo ($key); ?> : <?php echo ($v); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
				<div class="hr">			
				</div>					
			
			</div>
		</div>
	</div>
	<div style="width:49%;" class="fr">
		<div class="main_con fl">
			<h6>我的个人信息</h6>
			<div class="content">
				<p>您好，<b style="color:#06C"><?php echo ($my_info["user_name"]); ?></b> &nbsp;[<?php echo ($admin_level["name"]); ?>]<br></p>
				<div class="hr">			
				</div>               
			 </div>
		</div>
        <div class="main_con fl">
            <h6>版权信息</h6>
            <div class="content">
                <p>版权所有：深圳IT飞虎队</p>
                <p>程序版本：v2.6</p>
                <p>更新时间：2015-3-13</p>
                <div class="hr">
                </div>

            </div>
        </div>
	</div>
</div>
</body>
</html>