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
     <div class="col-tab">
      <ul class="tabBut cu-li">
        <li class="on">商品列表</li>
        <li><a href="<?php echo U('user_goods/ug_cz',array('cz'=>'add'));?>">添加会员商品</a></li>
      </ul>
      </div>
    <form name="searchform" action="" method="get" >
    <table width="100%" cellspacing="0" class="search-form">
        <tbody>
            <tr>
            <td>
            <div class="explain-col">
                &nbsp;商品ID :
                <input name="gid" type="text" id="gid" class="input-text" size="10"/>
                &nbsp;商品名称 :
                <input name="good_name" type="text" id="good_name" class="input-text" size="25"/>
                <input type="hidden" name="m" value="user_goods" />
                <input type="hidden" name="a" value="ug" />
                <input type="submit" name="search" class="button" value="搜索" />
            </div>
            </td>
            </tr>
        </tbody>
    </table>
    </form>
    <form id="myform" name="myform" action="<?php echo u('user_goods/ug_cz',array('cz'=>'del'));?>" method="post" onsubmit="return check();">
    <div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width=50>ID</th>
                <th width=25><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>              
                <th width=50>商品ID</th>
                <th width=200>会员商品</th>
                <th width=100>原价格</th>             
                <th width=100>价格</th>
                <th width="40">精品</th>
                <th width=250>日期</th>
                <th width="40">状态</th>
                <th width="80">编辑</th>
            </tr>
        </thead>
        <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>       
            <td align="center"><?php echo ($val["id"]); ?></td>   
            <td align="center">
             <input type="checkbox" value="<?php echo ($val["id"]); ?>" name="id[]">
            </td>        
            <td align="center"><?php echo ($val["gid"]); ?></td>
            <td align="center"><a class="blue" href="<?php echo u('goods/edit', array('id'=>$val['gid'],'cz'=>'edit'));?>"><?php echo ($val["good_name"]); ?></a></td>
            <td align="center"><?php echo ($val["orgprice"]); ?></td>
            <td align="center"><?php echo ($val["price"]); ?></td>
            <td align="center">
                <?php if(($val["boutique"]) == "0"): ?>否
                    <?php else: ?>
                    是<?php endif; ?>
            </td>            
            <td align="center">开始日期<?php echo (date('Y-m-d H:i:s',$val["start_time"])); ?></br>截至日期<?php echo (date('Y-m-d H:i:s',$val["end_time"])); ?></br><?php if(($val["start_time"] > time())): ?>还没开始<?php elseif(($val["end_time"] < time())): ?>已结束<?php else: ?>正在进行中<?php endif; ?></td>
            <td align="center" onclick="status(<?php echo ($val["id"]); ?>)" id="status_<?php echo ($val["id"]); ?>"><img src="__ROOT__/statics/images/status_<?php echo ($val["status"]); ?>.gif" /></td>
            <td align="center"><a class="blue" href="<?php echo u('user_goods/ug_cz', array('id'=>$val['id'],'cz'=>'edit'));?>">编辑</a></td><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>

    <div class="btn">
        <label for="check_box" style="float:left;"><?php echo (L("select_all")); ?>/<?php echo (L("cancel")); ?></label>
        <input type='hidden' name='table' value="<?php echo ($table); ?>"/>
        <input type="submit" class="button" name="dosubmit" value="<?php echo (L("delete")); ?>" onclick="return confirm('<?php echo (L("sure_delete")); ?>')" style="float:left;margin:0 10px 0 10px;"/>
        
        <div id="pages"><?php echo ($page); ?></div>
    </div>

    </div>
    </form>
<script>
function status(id){
    $.post("<?php echo u('user_goods/ug_cz',array('cz'=>'status'));?>", { id: id}, function(jsondata){
        var return_data  = eval("("+jsondata+")");
        $("#status_"+id+" img").attr('src', '__ROOT__/statics/images/status_'+return_data.data+'.gif')
    }); 
}
</script>
</body>
</html>