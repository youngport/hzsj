<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<script type="text/javascript" src="__ROOT__/statics/js/jquery/plugins/jquery.imagePreview.js"></script>
<div class="pad-10" >
    <a href="<?php echo U('goods/test');?>">现金券拆分历史记录</a>
    <form name="searchform" action="?kucun=0" method="get" >
    <table width="100%" cellspacing="0" class="search-form">
        <tbody>
            <tr>
            <td>
            <div class="explain-col">
            	<?php if($huodong_id != '' ): ?>活动ID :
                <input name="huodong_id" type="text" class="input-text" width="50" size="25" value="<?php echo ($huodong_id); ?>" />
                &nbsp;<?php endif; ?>
            	商品编号 :
                <input name="pcode" type="text" class="input-text" size="25" value="<?php echo ($pcode); ?>" />
                &nbsp;
            	商品录入时间：
                <input type="text" name="addtime_start" id="addtime_start"  value="<?php echo ($addtime_start); ?>" width="130px"
                       onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',startDate:'1980-05-01',alwaysUseStartDate:true})"
                        placeholder="选择录入起始时间">

                -  <input type="text" name="addtime_end" id="addtime_end"  value="<?php echo ($addtime_end); ?>" width="130px"
                          onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',startDate:'1980-05-01',alwaysUseStartDate:true})"
                           placeholder="选择录入截止时间">
                &nbsp;
            	上架时间：
                <input type="text" name="time_start" id="time_start"  value="<?php echo ($time_start); ?>" width="130px"
                       onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',startDate:'1980-05-01',alwaysUseStartDate:true})"
                        placeholder="选择上架起始时间">

                -  <input type="text" name="time_end" id="time_end"  value="<?php echo ($time_end); ?>" width="130px"
                          onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',startDate:'1980-05-01',alwaysUseStartDate:true})"
                           placeholder="选择上架截止时间">
            	&nbsp;<div style="height:10px;"></div>商品分类：
                <select name="cate_id">
            	<option value="0">--请选择分类--</option>
				<?php if(is_array($cate_list)): $i = 0; $__LIST__ = $cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" level="<?php echo ($val["level"]); ?>" <?php if($cate_id == $val['id']): ?>selected="selected"<?php endif; ?>>
                    <?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$val['level']);?>
                    <?php echo trim($val['name']);?>
                </option><?php endforeach; endif; else: echo "" ;endif; ?>                
              	</select>
				 &nbsp;上架状态:
                <select name="status">
            	<option value="-1">-请选择-</option>
                <option value="1" <?php if($status == 1): ?>selected="selected"<?php endif; ?>>已上架</option>
                <option value="0" <?php if($status == 0): ?>selected="selected"<?php endif; ?>>已下架</option>
                </select>
				 &nbsp;今日推荐活动状态:
                <select name="canyuhd">
            	<option value="-1">-请选择-</option>
                <option value="1" <?php if($canyuhd == 1): ?>selected="selected"<?php endif; ?>>参与</option>
                <option value="0" <?php if($canyuhd == 0): ?>selected="selected"<?php endif; ?>>不参与</option>
                </select>
                &nbsp;首页推荐状态:
                <select name="is_hots">
                    <option value="-1">-请选择-</option>
                    <option value="1" <?php if($is_hots == 1): ?>selected="selected"<?php endif; ?>>参与</option>
                    <option value="0" <?php if($is_hots == 0): ?>selected="selected"<?php endif; ?>>不参与</option>
                </select>
				 &nbsp;商品类型 :
                <select name="goodtype">
            	<option value="-1">-请选择-</option>
                <option value="0" <?php if($goodtype == 0): ?>selected="selected"<?php endif; ?>>普通</option>
                <option value="1" <?php if($goodtype == 1): ?>selected="selected"<?php endif; ?>>会员</option>
                <option value="2" <?php if($goodtype == 2): ?>selected="selected"<?php endif; ?>>店铺</option>
                <option value="3" <?php if($goodtype == 3): ?>selected="selected"<?php endif; ?>>积分</option>
                <option value="99" <?php if($goodtype == 99): ?>selected="selected"<?php endif; ?>>商城界面图</option>
                </select><br /><br />
                &nbsp;关键字 :
                <input name="keyword" type="text" class="input-text" size="25" value="<?php echo ($keyword); ?>" />
                &nbsp;供应商编号:
                <input name="gys_bianma" type="text" class="input-text" size="25" value="<?php echo ($gys_bianma); ?>" />
                &nbsp;仓库编号 :
                <input name="cangku_bianhao" type="text" class="input-text" size="25" value="<?php echo ($cangku_bianhao); ?>" />
                &nbsp;<br /><br />供应商名称:
                <input name="gys_name" type="text" class="input-text" size="25" value="<?php echo ($gys_name); ?>" />
                &nbsp;仓库名称 :
                <input name="cangku_name" type="text" class="input-text" size="25" value="<?php echo ($cangku_name); ?>" />
                <input type="hidden" name="m" value="goods" />
                <input type="submit" name="search" class="button" value="搜索" />
                <input type="submit" name="daochu" class="button" value="搜索结果导出Excel" />
        	</div>
            </td>
            </tr>
        </tbody>
    </table>
    </form>
    <form id="myform" name="myform" action="<?php echo u('goods/delete');?>" method="post" onsubmit="return check();">
    <div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width="30">ID</th>
                <th width=15><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>                
                <th width="40">&nbsp;</th>
                <th  width="60">商品编号</th>
                <th  width="60">产品规格</th>
                <th    align="left"> 商品名称 </th>
                <th width=60>库存</th>
                <th width=60>所属分类</th>
                <th  width="60">单价(元)</th>
                <th width=150><a href="<?php echo U('goods/index',array('time_start'=>$time_start,'time_end'=>$time_end,'cate_id'=>$cate_id,'keyword'=>$keyword,'order'=>'add_time','sort'=>$sort));?>" class="blue <?php if($order == 'add_time'): ?>order_sort_<?php if($sort == 'desc'): ?>1<?php else: ?>0<?php endif; endif; ?>">上架时间</a></th>
				<th width=150>商品录入时间</th>
                <th width=40><a href="<?php echo U('goods/index',array('time_start'=>$time_start,'time_end'=>$time_end,'cate_id'=>$cate_id,'keyword'=>$keyword,'order'=>'hits','sort'=>$sort));?>" class="blue <?php if($order == 'hits'): ?>order_sort_<?php if($sort == 'desc'): ?>1<?php else: ?>0<?php endif; endif; ?>">人气</a></th>
                <th width="30">排序</th>			
                <th width=60>上架状态</th>
                <th width=170>操作</th>
            </tr>
        </thead>
    	<tbody>
        <?php if(is_array($items_list)): $i = 0; $__LIST__ = $items_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
        	<td align="center"><?php echo ($val["id"]); ?></td>
            <td align="center"><input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]"></td>           
            <td align="right"><img src="<?php echo ($val["simg"]); ?>" width="35" width="35" class="preview" bimg="<?php echo ($val["img"]); ?>"></td>
            <td align="center"><?php echo ($val["pcode"]); ?></td>
            <td align="center"><?php echo ($val["guige"]); ?></td>
            <td align="left"><a href="<?php echo U('goods/edit', array('id'=>$val['id']));?>"><?php echo ($val["good_name"]); ?></a></td>
            <td align="center"><?php echo ($val["kucun"]); ?></td>
            <td align="center"><?php echo ($val["items_cate"]["name"]); ?></td>
            <td align="center"><em style="color:green;"><?php echo ($val["price"]); ?></em></td>
            <td align="center" id="time_<?php echo ($val["id"]); ?>"><?php if($val["add_time"] == 0): ?>--<?php else: echo (date("Y-m-d H:i:s",$val["add_time"])); endif; ?></td>
            <td align="center"><?php echo (date("Y-m-d H:i:s",$val["goods_addtime"])); ?></td>
            <td align="center"><em style="color:green;"><?php echo ($val["hits"]); ?></em></td>
            <td>
            <input type="text" class="input-text-c input-text" value="<?php echo ($val["sort_order"]); ?>" size="4" name="listorders[<?php echo ($val["id"]); ?>]" id="sort_<?php echo ($val["id"]); ?>" onblur="sort(<?php echo ($val["id"]); ?>,'sort_order',this.value)" onkeyup="this.value=this.value.replace(/D/g,'')" onafterpaste="this.value=this.value.replace(/D/g,'')">
            </td>
             <td align="center" onclick="status(<?php echo ($val["id"]); ?>,'status')" id="status_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["status"]); ?>.gif" /></td>
            <td align="center"><a class="blue" href="<?php echo U('goods_gongying/goods_cangku_bang',array('id'=>$val['id']));?>">绑定仓库</a> | <a class="blue" href="<?php echo U('goods_gongying/gys_cangku_index',array('goodsid'=>$val['id']));?>">查看供应商</a> | <a class="blue" href="<?php echo U('goods/edit',array('id'=>$val['id']));?>">编辑</a> | <a class="blue" href="<?php echo U('goods/gs_cz',array('cz'=>'add','gid'=>$val['id']));?>">添加团购</a> | <a class="blue" href="<?php echo U('goods/sg_cz',array('cz'=>'add','gid'=>$val['id']));?>">添加闪购</a></td><?php endforeach; endif; else: echo "" ;endif; ?>
    	</tbody>
    </table>

    <div class="btn">
    	<label for="check_box" style="float:left;">全选/取消</label>
    	<input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')" style="float:left;margin:0 10px 0 10px;"/>
        
    	<div id="pages"><?php echo ($page); ?></div>
    </div>

    </div>
    </form>
</div>
<script language="javascript">
$(function(){
	$(".preview").preview();
});

var lang_cate_name = "商品名称";
function check(){
	if($("#myform").attr('action') == '<?php echo u("goods/delete");?>') {
		var ids='';
		$("input[name='id[]']:checked").each(function(i, n){
			ids += $(n).val() + ',';
		});

		if(ids=='') {
			window.top.art.dialog({content:lang_please_select+lang_cate_name,lock:true,width:'200',height:'50',time:1.5},function(){});
			return false;
		}
	}
	return true;
}
function status(id,type){
    $.get("<?php echo u('goods/status');?>", { id: id, type: type }, function(jsondata){
		var return_data  = eval("("+jsondata+")");
		$("#"+type+"_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data.data+'.gif')
		if(return_data.data==1){
			var time_d = new Date();
			var   year=time_d.getFullYear();     
	        var   month=time_d.getMonth()+1<10?'0'+(time_d.getMonth()+1):time_d.getMonth()+1;     
	        var   date=time_d.getDate()<10?'0'+time_d.getDate():time_d.getDate();    
	        var   hour=time_d.getHours()<10?'0'+time_d.getHours():time_d.getHours();   
	        var   minute=time_d.getMinutes()<10?'0'+time_d.getMinutes():time_d.getMinutes();     
	        var   second=time_d.getSeconds()<10?'0'+time_d.getSeconds():time_d.getSeconds();    
			$("#time_"+id).html(year+"-"+month+"-"+date+"   "+hour+":"+minute+":"+second) ;
		}
	});
}
//排序方法
function sort(id,type,num){
    $.get("<?php echo u('goods/sort');?>", { id: id, type: type,num:num }, function(jsondata){
        
		$("#"+type+"_"+id+" ").attr('value', jsondata.data);
	},'json'); 
}
</script>
</body>
</html>