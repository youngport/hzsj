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
<div class="pad-10">
    <form name="searchform" action="" method="get">
        <table width="100%" cellspacing="0" class="search-form">
            <tbody>
            <tr>
                <td>
                    <div class="explain-col">
                        购买人：
                        <input name="goumairen" type="text" class="input-text" size="15" value="<?php echo ($goumairen); ?>"/> &nbsp;
                        openid：
                        <input name="openid_txt" type="text" class="input-text" size="15" value="<?php echo ($openid_txt); ?>"/> &nbsp;
                        下单时间：
                        <input id="time_start" style="width: 120px;" type="text" value="<?php echo ($addtime_start); ?>"
                               placeholder="选择下单起始时间"
                               onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',minDate:'2015-01-01'})"
                               name="time_start"/>-到-<input id="time_end" style="width: 120px;" type="text"
                                                            onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',minDate:'2015-01-01'})"
                                                            name="time_end" value="<?php echo ($addtime_end); ?>"
                                                            placeholder="选择下单截止时间"/>

                        &nbsp;订单状态
                        <select name="status">
                            <option value="-1">-请选择-</option>
                            <option value="0"
                            <?php if($status == 0): ?>selected="selected"<?php endif; ?>
                            >未付款</option>
                            <option value="1"
                            <?php if($status == 1): ?>selected="selected"<?php endif; ?>
                            >已付款</option>
                            <option value="2"
                            <?php if($status == 2): ?>selected="selected"<?php endif; ?>
                            >清关中</option>
                            <option value="3"
                            <?php if($status == 3): ?>selected="selected"<?php endif; ?>
                            >已发货</option>
                            <option value="4"
                            <?php if($status == 4): ?>selected="selected"<?php endif; ?>
                            >已完成</option>
                            <option value="6"
                            <?php if($status == 6): ?>selected="selected"<?php endif; ?>
                            >退换货申请中</option>
                            <option value="7"
                            <?php if($status == 7): ?>selected="selected"<?php endif; ?>
                            >退换货审核通过</option>
                            <option value="10"
                            <?php if($status == 10): ?>selected="selected"<?php endif; ?>
                            >退换货审核不通过</option>
                            <option value="8"
                            <?php if($status == 8): ?>selected="selected"<?php endif; ?>
                            >退换货物流中</option>
                            <option value="9"
                            <?php if($status == 9): ?>selected="selected"<?php endif; ?>
                            >退换货已完成</option>
                        </select>
                        &nbsp;筛选订单
                        <select name="fenlei">
                            <option value="-1">-请选择-</option>
                            <option value="0"
                            <?php if($fenlei == 0): ?>selected="selected"<?php endif; ?>
                            >普通订单</option>
                            <option value="3"
                            <?php if($fenlei == 3): ?>selected="selected"<?php endif; ?>
                            >积分兑换订单</option>
                            <option value="1"
                            <?php if($fenlei == 1): ?>selected="selected"<?php endif; ?>
                            >店铺订单</option>
                            <option value="2"
                            <?php if($fenlei == 2): ?>selected="selected"<?php endif; ?>
                            >会员</option>
                        </select>
                        &nbsp;订单流水号 :
                        <input name="keyword" type="text" class="input-text" size="15" value="<?php echo ($keyword); ?>"/> &nbsp;
                        快递单号 :
                        <input name="invoice_no" type="text" class="input-text" size="15" value="<?php echo ($invoice_no); ?>"/>
                        <input type="hidden" name="m" value="orders"/>
                        <input type="submit" name="search" class="button" value="搜索"/>
                        <input type="submit" name="daochu" class="button" value="搜索结果导出Excel"/>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <form id="myform" name="myform" method="post">
        <div class="table-list">
            <table width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th width="80">订单流水号</th>
                    <th width="80">购买人</th>
                    <th width="80">微信昵称</th>
                    <th width="50">商品总价</th>
                    <th width="50">商品类型</th>
                    <th width="50">订单状态</th>
                    <th width="80">支付方式</th>
                    <th width="80">支付订单号</th>
                    <th width=110>下单时间</th>
                    <th width=110>快递单号</th>
                    <th width=120>订单完成时间</th>
                    <th width=120>备注</th>
                    <th width=120>分成总额</th>
                    <th width="40">二维码</th>
                    <th width="40">店铺加盟审核</th>
                    <th width="40">支付方</th>
                    <th width="60">支付方审核</th>
                    <th width=60>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($items_list)): $i = 0; $__LIST__ = $items_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                        <td align="center"><?php echo ($val["order_sn"]); ?></td>
                        <td align="center"><?php echo ($val["buyer_name"]); ?></td>
                        <td align="center"><?php echo ($val["wei_nickname"]); ?></td>
                        <td align="left"><?php echo ($val["goods_amount"]); ?></td>
                        <td align="left"><?php echo ($val["product_type"]); ?></td>
                        <td align="center">
                            <?php switch($val["status"]): case "0": ?>未付款<?php break;?>
                                <?php case "1": ?>已付款<?php break;?>
                                <?php case "2": ?>清关中<?php break;?>
                                <?php case "3": ?>已发货<?php break;?>
                                <?php case "4": ?>已完成<?php break;?>
                                <?php case "6": ?><a class="blue"
                                                   href="<?php echo U('orders/tuihh_sq',array('id'=>$val['order_id']));?>">退换货申请中</a><?php break;?>
                                <?php case "7": ?><a class="blue"
                                                   href="<?php echo U('orders/tuihh_sq',array('id'=>$val['order_id']));?>">退换货审核通过</a><?php break;?>
                                <?php case "10": ?><a class="blue"
                                                    href="<?php echo U('orders/tuihh_sq',array('id'=>$val['order_id']));?>">退换货审核不通过</a><?php break;?>
                                <?php case "8": ?><a class="blue"
                                                   href="<?php echo U('orders/tuihh_wl',array('id'=>$val['order_id']));?>">退换货物流中</a><?php break;?>
                                <?php case "9": ?><a class="blue"
                                                   href="<?php echo U('orders/tuihh_wl',array('id'=>$val['order_id']));?>">退换货已完成</a><?php break;?>
                                <?php case "99": ?>已拒绝<?php break;?>
                                <?php case "98": ?>已关闭<?php break; endswitch;?>
                        </td>
                        <td align="left"><?php echo ($val["payment_name"]); ?></td>
                        <td align="left"><?php echo ($val["payment_code"]); ?><br/>
                            <?php if(!empty($val["pay_time"])): echo (date("Y-m-d H:i:s",$val["pay_time"])); endif; ?>
                        </td>
                        <td align="center">
                            <?php if(!empty($val["add_time"])): echo (date("Y-m-d H:i:s",$val["add_time"])); endif; ?>
                        </td>
                        <td align="center"><?php echo ($val["invoice_no"]); ?></td>
                        <td align="center">
                            <?php if(!empty($val["finish_time"])): echo (date("Y-m-d H:i:s",$val["finish_time"])); ?>
                                <?php else: ?>
                                --<?php endif; ?>
                        </td>
                        <td align="center"><?php echo ($val["order_notes"]); ?></td>
                        <td align="center"><?php echo (($val["fp_price"])?($val["fp_price"]):'--'); ?></td>
                        <td align="center">
                            <?php if($val["erm"] == 1 ): ?><a class="blue"
                                                              href="<?php echo U('orders/erweima',array('id'=>$val['order_id']));?>">查看</a>
                                <?php else: ?>
                                --<?php endif; ?>
                        </td>
                        <td align="center">
                            <?php if($val["jiamengshenhe"] == 0 && $val["erm"] == 1): ?><a class="blue"
                                                                                        href="<?php echo U('jiamengshenhe',array('order_id'=>$val['order_id']));?>">待审</a>
                                <?php elseif($val["jiamengshenhe"] == 1 ): ?>
                                通过
                                <?php elseif($val["jiamengshenhe"] == 2 ): ?>
                                不通过
                                <?php else: ?>
                                --<?php endif; ?>
                        </td>
                        <td align="center">
                            <?php if($val["jiezhangren"] != 0 ): ?><a class="blue"
                                                                       href="<?php echo U('orders/shenheimg',array('id'=>$val['order_id']));?>">
                                <?php if($val["jiezhangren"] == 1 ): ?>推广
                                    <?php elseif($val["jiezhangren"] == 2): ?>
                                    店家<?php endif; ?>
                            </a>
                                <?php else: ?>
                                --<?php endif; ?>
                        </td>
                        <td align="center"><span class="shenhe" style="cursor:pointer;color: #72ACE3"
                                                 data-id="<?php echo ($val["order_id"]); ?>" data-state="<?php echo ($val["shenhe_jiez"]); ?>"><?php if($val["shenhe_jiez"] == 0 ): ?>未审
                            <?php else: ?>
                            属实<?php endif; ?></span></td>
                        <td align="center">
                            <a class="blue" href="<?php echo U('orders/detail',array('id'=>$val['order_id']));?>">查看</a>&nbsp;
                            <a class="blue" href="<?php echo U('orders/edit',array('id'=>$val['order_id']));?>">修改</a>
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
    $('.shenhe').click(function () {
        var xx;
        var id = $(this).attr('data-id');
        if ($(this).attr('data-state') == 0)xx = 1;
        if ($(this).attr('data-state') != 0)xx = 0;
        var $this = $(this);
        $.post('__URL__/shenhe', {state: xx, id: id}, function (data) {
            if (data == 0) {
                $this.html('未审');
                $this.attr('data-state', data)
            } else if (data == 1) {
                $this.html('属实');
                $this.attr('data-state', data)
            } else if (data == 3) {
                alert('该订单状态不能通过审核');
            }
            ;
        });
    })

</script>
</body>
</html>