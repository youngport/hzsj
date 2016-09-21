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
    <form method="get" >
        <input type="hidden" name="m" value="orders"/>
        <input type="hidden" name="a" value="count"/>
        <table width="100%" cellspacing="0" class="search-form">
            <tbody>
            <tr>
                <td>
                    <div class="explain-col">
                    购买人：
                        <input name="buyer_name" type="text" class="input-text" size="15" value="<?php echo ($buyer_name); ?>" /> &nbsp;
                        openid：
                        <input name="openid" type="text" class="input-text" size="15" value="<?php echo ($openid); ?>" /> &nbsp;
                        时间：
                        <input id="time_start" style="width: 120px;" type="text"  value="<?php echo ($start_time); ?>" placeholder="选择下单起始时间" onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',minDate:'2015-01-01'})"
                               name="start_time" />-到-<input id="time_end" style="width: 120px;" type="text"
                                                           onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',minDate:'2015-01-01'})"
                                                           name="end_time"  value="<?php echo ($end_time); ?>" placeholder="选择下单截止时间" />
                        排序：
                        <select name="order">
                            <option value=""/>请选择</option>
                            <option value="type"/>重点</option>
                            <option value="money"/>金额</option>
                            <option value="order_count"/>总订单</option>
                            <option value="order_ycount"/>已完成订单</option>
                            <option value="order_wcount"/>未付款订单</option>
                            <option value="order_tcount"/>退货订单</option>
                        </select>
                        <input type="submit" name="search" class="button" value="搜索" />
                        <input type="submit" name="daochu" class="button" value="搜索结果导出Excel" />
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <form id="myform" name="myform"  method="post"  >
        <div class="table-list">
            <table width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th width="10"> </th>
                    <th>购买人ID</th>
                    <th>购买人 </th>
                    <th>已完成订单总价</th>
                    <th>订单总量</th>
                    <th>已完成订单总量</th>
                    <th>未付款订单总量</th>
                    <th>退货订单总量</th>
                    <th>最近下单</th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                        <td align="center"><span class="cz" data-i=<?php echo ($val['type']+0); ?>><?php if(($val["type"]) == "1"): ?><img src="/wei/css/img/sc_on.jpg" width="20"/><?php else: ?><img src="/wei/css/img/sc_off.jpg" width="20"/><?php endif; ?></span></td>
                        <td align="center"><?php echo ($val["buyer_id"]); ?></td>
                        <td align="center"><?php echo ($val["buyer_name"]); ?></td>
                        <td align="center"><?php echo ($val["money"]); ?> </td>
                        <td align="center"><?php echo ($val["order_count"]); ?> </td>
                        <td align="center"  ><?php echo ($val["order_ycount"]); ?></td>
                        <td align="center"  ><?php echo ($val["order_wcount"]); ?></td>
                        <td align="center"  ><?php echo ($val["order_tcount"]); ?></td>
                        <td align="center">
                            <?php $data=M('orders')->order('add_time desc')->field('order_id,order_sn,add_time')->where("buyer_id='".$val['buyer_id']."'")->find(); $url=U('orders/detail',array('id'=>$data['order_id'])); echo "<a href='$url'>".$data['order_sn']."</a></br>".date('Y-m-d H:i:s',$data['add_time']); ?>
                        </td><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>

            <div class="btn">
                <div id="pages"><?php echo ($page); ?></div>
            </div>
        </div>
    </form>
</div>
<script language="javascript">
    $("select[name='order']").find('option').each(function(){
        if($(this).val()=="<?php echo ($order); ?>")
            $(this).attr("selected",true);
    });
    $(".cz").click(function(){
        var cz=$(this);
        var id=cz.parent().next().text();
        $.post("<?php echo U('orders/important');?>",{cz:$(this).attr('data-i'),id:id},function(data){
            if(data==1){
                cz.attr('data-i',1);
                cz.find('img').attr("src","/wei/css/img/sc_on.jpg");
            }else if(data==2) {
                cz.attr('data-i',0);
                cz.find('img').attr("src","/wei/css/img/sc_off.jpg");
            }
        });
    });
</script>
</body>
</html>