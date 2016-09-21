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
<div class="pad-10" >
    <form id="myform" name="myform" action="<?php echo u('items_cate/delete');?>" method="post" onsubmit="return check();">
    <div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            	<th width="50">ID</th>
                <th width="4%"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
              	<th width="200"><?php echo L('name');?></th>
                <th width="80"><?php echo L('img');?></th>              	 
                <th width="80">商品数</th>  
                <th>匹配分类关键字</th>  
                <th width="60"><?php echo L('ordid');?></th>
				<th width="60"><?php echo L('recommend');?></th>
                <th width="60"><?php echo L('is_hots');?></th>             
              	<th width="40"><?php echo L('status');?></th>             
              	<th width="220">操作</th>
            </tr>
        </thead>
    	<tbody>
        <?php if(is_array($items_cate_list)): $i = 0; $__LIST__ = $items_cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr class="<?php echo ($val["cls"]); ?> level_<?php echo ($val["level"]); ?>" iid="<?php echo ($val["id"]); ?>" pid="<?php echo ($val["pid"]); ?>" level="<?php echo ($val["level"]); ?>">
        	<td align="center"><?php echo ($val["id"]); ?></td>
            <td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td>
            <td>
            	<div style="margin-left:<?php echo ($val['level']*30); ?>px">
            		
					<?php if($val["cls"] != ''): ?><img src="__ROOT__/statics/admin/images/tv-collapsable.gif" class="expandable" id="<?php echo ($val["id"]); ?>" pid="<?php echo ($val["pid"]); ?>" level="<?php echo ($val["level"]); ?>"/>	
					<?php else: ?>
					 	<img src="__ROOT__/statics/admin/images/tv-expandable.gif" class="expandable" id="<?php echo ($val["id"]); ?>" pid="<?php echo ($val["pid"]); ?>" level="<?php echo ($val["level"]); ?>"/><?php endif; ?>
					
					
                   
                    <span style="color:<?php echo ($val["color"]); ?>; padding-left:5px;"><?php echo ($val["name"]); ?></span>
                </div>            	
           	</td>
            <td>
            	<?php if($val['img'] != ''): ?><img src="<?php echo ($val["img"]); ?>" height="25px;"/><?php endif; ?>
            </td>			
            <td align="center"></td>
            <td align="left"><?php echo ($val["matching_title"]); ?></td>
            <td align="center">
            	<input type="text" class="input-text-c input-text" value="<?php echo ($val["ordid"]); ?>" size="4" name="listorders[<?php echo ($val["id"]); ?>]" id="sort_<?php echo ($val["id"]); ?>" onblur="sort(<?php echo ($val["id"]); ?>,'ordid',this.value)"  onkeyup="this.value=this.value.replace(/D/g,'')" onafterpaste="this.value=this.value.replace(/D/g,'')">
			</td>	
			<td align="center" <?php if($val["level"] == 2): ?>onclick="status(<?php echo ($val["id"]); ?>,'recommend')" id="recommend_<?php echo ($val["id"]); ?>"<?php endif; ?> >
				<?php if($val["level"] == 2): ?><img src="__ROOT__/statics/images/status_<?php echo ($val["recommend"]); ?>.gif" />
				<?php else: ?>	
					&nbsp;<?php endif; ?>				
			</td>	
            <td align="center" onclick="status(<?php echo ($val["id"]); ?>,'is_hots')" id="is_hots_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["is_hots"]); ?>.gif" /></td>
            <td align="center" onclick="status(<?php echo ($val["id"]); ?>,'status')" id="status_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["status"]); ?>.gif" /></td>
            <td align="center"><a class="blue" href="<?php echo u('huodong/index',array('canyuhdid'=>$val['id'],'leim'=>'1'));?>">参与到活动</a>&nbsp;|&nbsp;<a class="blue" href="<?php echo u('items_cate/edit',array('id'=>$val['id']));?>">编辑</a></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    	</tbody>
    </table>

    <div class="btn">
    <label for="check_box">全选/取消</label>
    <input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')"/>
    
    </div>

    </div>
    </form>
</div>
<script type="text/javascript">
$(function(){
$('.expandable').toggle(
		function(){
			var id=
			$('.sub_'+$(this).attr('id')).hide();		
			$(this).attr('src',ROOT_PATH+'/statics/admin/images/tv-expandable.gif');
		},
		function(){
			$('.sub_'+$(this).attr('id')).show();
			$(this).attr('src',ROOT_PATH+'/statics/admin/images/tv-collapsable.gif');
		}
	);
});

var lang_items_cate_name = "商品分类";
function check(){
	if($("#myform").attr('action') == '<?php echo u("items_cate/delete");?>') {
		var ids='';
		$("input[name='id[]']:checked").each(function(i, n){
			ids += $(n).val() + ',';
		});

		if(ids=='') {
			window.top.art.dialog({content:lang_please_select+lang_items_cate_name,lock:true,width:'200',height:'50',time:1.5},function(){});
			return false;
		}
	}
	return true;
}
function status(id,type){
    $.get("<?php echo u('items_cate/status');?>", { id: id, type: type }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data.data+'.gif')
	}); 
}
//排序方法
function sort(id,type,num){
    
    $.get("<?php echo u('items_cate/sort');?>", { id: id, type: type,num:num }, function(jsondata){
        
		$("#"+type+"_"+id+" ").attr('value', jsondata.data);
	},'json'); 
}
</script>
</body>
</html>