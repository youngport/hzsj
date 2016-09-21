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

<style>
    .placeholder{
        width: 400px;
    }
    .statusBar{
        width:400px;
    }
</style>
<script>
    var BASE_URL = '__ROOT__/statics/webuploader-0.1.5';
</script>
<link href="__ROOT__/statics/webuploader-0.1.5/xb-webuploader.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="__ROOT__/statics/webuploader-0.1.5/webuploader.min.js"></script>

<form action="<?php echo u('goods/add');?>" method="post" name="myform" id="myform"  enctype="multipart/form-data" style="margin-top:10px;">
    <div class="pad-10">
        <div class="col-tab">
            <ul class="tabBut cu-li">
                <li id="tab_setting_1" class="on" onclick="SwapTab('setting', 'on', '', 3, 1);">商品信息</li>
                <li id="tab_setting_2" onclick="SwapTab('setting', 'on', '', 3, 2);">详细介绍</li>
                <li id="tab_setting_3" onclick="SwapTab('setting', 'on', '', 3, 3);">SEO设置</li>
            </ul>
            <div id="div_setting_1" class="contentList pad-10">
                <table width="100%" cellpadding="2" cellspacing="1" class="table_form">
                    <tbody id="item_body"  >   <tr>
                            <th width="100">商品编号 :</th>
                            <td>
                                <input type="text" size="60"  class="textinput requireinput" name="pcode" id="pcode"    placeholder="请输入商品编号">

                            </td>
                        </tr> <tr>
                            <th width="100">商品名称 :</th>
                            <td>
                                <input type="text"   class="textinput requireinput" placeholder="请输入商品名称" name="good_name" id="good_name"  value="" size="60">
                            </td>
                        </tr>
                        <tr>
                            <th width="100">商品规格 :</th>
                            <td>
                                <input type="text"   class="textinput requireinput" placeholder="请输入商品规格" name="guige" id="guige" size="60">
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <th width="100">商品类型 :</th>
                            <td>
                                <input type="text"   class="textinput requireinput" placeholder="请输入商品类型：0普通 1代理 2店家3积分兑换" name="goodtype" id="goodtype" size="60">
                            </td>
                        </tr>
                        <!--新增的一级分类-->
                        <tr>
                            <th>一级分类 :</th>
                            <td>
                                <select name="pcate_id" id="pcate_id" >
                                    <option value="">--请选择一级分类--</option>
                                    <!--                                <?php if(is_array($listed)): $i = 0; $__LIST__ = $listed;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" >
                                                                            <?php echo trim($vo['name']);?>
                                                                        </option><?php endforeach; endif; else: echo "" ;endif; ?>-->
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th>商品分类 :</th>
                            <td>
                                <select name="cate_id" id="cate_id" >
                                    <option value="">--请选择分类--</option>
                                    <!--                                    <?php if(is_array($cate_list)): $i = 0; $__LIST__ = $cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" level="<?php echo ($val["level"]); ?>" >
                                                                                <?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$val['level']);?>
                                                                                <?php echo trim($val['name']);?>
                                                                            </option><?php endforeach; endif; else: echo "" ;endif; ?>-->
                                </select>
                            </td>
                        </tr>  
                        <tr>
                            <th>品牌选择 :</th>
                            <td>
                                <select name="mp_id" id="mp_id" >
                                    <option value="">--请选择品牌--</option>
                                    <!--                                    <?php if(is_array($mingp)): $i = 0; $__LIST__ = $mingp;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" level="<?php echo ($val["level"]); ?>" >
                                                                                <?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$val['level']);?>
                                                                                <?php echo trim($val['name']);?>
                                                                            </option><?php endforeach; endif; else: echo "" ;endif; ?>-->
                                </select>

                            </td>
                        </tr>
                        <tr>
                            <th>商品品类:</th>
                            <td>
                                <select name="mp_cate" id="mp_cate" >
                                    <option value="">--请选择品类--</option>
                                    <!--                                    <?php if(is_array($mp_cate_list)): $i = 0; $__LIST__ = $mp_cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" level="" >
                                                                                <?php echo trim($val['name']);?>
                                                                            </option><?php endforeach; endif; else: echo "" ;endif; ?>-->
                                </select>

                            </td>
                        </tr>
                        <tr>
                            <th>商品国籍:</th>
                            <td>
                                <select name="nation_id" id="nation_id" >
                                    <option value="">--请选择商品国籍--</option>
                                    <!--                                    <?php if(is_array($nat_list)): $i = 0; $__LIST__ = $nat_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" level="" >
                                                                                <?php echo trim($val['nationality']);?>
                                                                            </option><?php endforeach; endif; else: echo "" ;endif; ?>-->
                                </select>

                            </td>
                        </tr>
                        <tr>
                            <th>活动标签 :</th>
                            <td>
                                <select name="gx_id" id="gx_id" >
                                    <option value="">--请选择功效--</option>
                                    <?php if(is_array($gongx)): $i = 0; $__LIST__ = $gongx;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["id"]); ?>" level="<?php echo ($val["level"]); ?>" >
                                            <?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',$val['level']);?>
                                            <?php echo trim($val['name']);?>
                                        </option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>

                            </td>
                        </tr>
                    <th>发货方式 :</th>
                    <td>
                        <select name="fh_type" id="fh_type" >
                            <option value="">--请选择--</option>                    
                            <option value ="保税">保税</option>
                            <option value ="直邮" >直邮</option> 
                        </select>

                    </td>
                    </tr>
                    <tr>
                        <th width="100">商品条形码 :</th>
                        <td>    
                            <input type="text"   class="textinput requireinput" placeholder="请输入商品条形码" name="goods_tiaoxm" id="goods_tiaoxm" value="<?php echo ($items["goods_tiaoxm"]); ?>" size="60">
                        </td>
                    </tr>
                    <tr>
                        <th width="100">生产地 :</th>
                        <td>
                            <input type="text"   class="textinput requireinput" placeholder="请输入商品生产地" name="yieldly" id="yieldly" value="<?php echo ($items["yieldly"]); ?>" size="60">
                        </td>
                    </tr>
                    <tr>
                        <th width="100">保质期 :</th>
                        <td>
                            <input type="text"   class="textinput requireinput" placeholder="请输入商品保质期 格式为*个月" name="period" id="period" value="<?php echo ($items["period"]); ?>" size="60">
                        </td>
                    </tr>
                    <tr>
                        <th width="100">适应人群 :</th>
                        <td>
                            <input type="text"   class="textinput requireinput" placeholder="请输入商品适应人群" name="suit" id="suit" value="<?php echo ($items["suit"]); ?>" size="60">
                        </td>
                    </tr>
                    <tr>
                        <th width="100">功效 :</th>
                        <td>
                            <input type="text"   class="textinput requireinput" placeholder="请输入商品功效" name="effect" id="effect" value="<?php echo ($items["effect"]); ?>" size="60">
                        </td>
                    </tr>
                    <tr>
                        <th width="100">利润商品原价(元) :</th>
                        <td>
                            <input type="text" size="60"  class="textinput requireinput" name="orgprice" id="orgprice" value="<?php echo ($items["orgprice"]); ?>"   placeholder="请输入商品原价(元)">

                        </td>
                    </tr>
                    <tr>
                        <th width="100">商品售价(元) :</th>
                        <td>
                            <input type="text" size="60"  class="textinput requireinput" name="price" id="price" value="<?php echo ($items["price"]); ?>"   placeholder="请输入商品单价(元)">

                        </td>
                    </tr><tr>
                        <th width="100">活动折扣 :</th>
                        <td>
                            <input type="text" size="60"  class="textinput requireinput" name="zhekou" id="zhekou" value="<?php echo ($items["zhekou"]); ?>"   placeholder="请输入商品搞活折扣，如 5.5，9.8，7.0">

                        </td>
                    </tr><tr>
                        <th width="100">今日推荐活动价(元) :</th>
                        <td>
                            <input type="text" size="60"  class="textinput requireinput" name="huodongjia" id="huodongjia" value="<?php echo ($items["huodongjia"]); ?>"   placeholder="请输入商品搞活动时的售卖价(元)">

                        </td>
                    </tr><tr>
                        <th width="100">闪购价(元) :</th>
                        <td>
                            <input type="text" size="60"  class="textinput requireinput" name="shangoujia" id="shangoujia" value="<?php echo ($items["shangoujia"]); ?>"   placeholder="请输入商品闪购时的售卖价(元)">

                        </td>
                    </tr>

                    <tr>
                        <th width="100">利润分配价格(元) :</th>
                        <td>
                            <input type="text" size="60"  class="textinput requireinput" name="fp_price" id="fp_price" value="<?php echo ($items["fp_price"]); ?>"   placeholder="请输入利润分配价格(元)">

                        </td>
                    </tr>
                    <tr>
                        <th width="100">兑换所需积分 :</th>
                        <td>
                            <input type="text" size="60"  class="textinput requireinput" name="dhjifen" id="dhjifen" value="<?php echo ($items["dhjifen"]); ?>"   placeholder="请输入商品兑换所需积分">

                        </td>
                    </tr>
                    <tr>
                        <th width="100">库存 :</th>
                        <td>
                            <input type="text" size="60"  class="textinput requireinput" name="kucun" id="kucun" value="<?php echo ($items["kucun"]); ?>"   placeholder="请输入库存数量">

                        </td>
                    </tr>
                    <tr>
                        <th>产品详情图片 :</th>
                        <td>
                            <img id="img_show" src="" style="display:none" style="width:200px;" /><br /><br />
                            <input type="file" name="img" id="img" class="input-text" size=21 />
                        </td>
                    </tr>

                    <tr>
                        <th>视频文件:</th>
                        <td>
                        <?php echo _webuploader(array('url'=>'goods/ajax_upload','name'=>'video','word'=>'上传文件'));?>
                        </td>
                    </tr>   

                    <tr>
                        <th>产品图片1 :</th>
                        <td>
                            <img id="img_show" src="" style="display:none" style="width:200px;" /><br /><br />
                            <input type="file" name="img3" id="img3" class="input-text" size=21 />
                        </td>
                    </tr>

                    <tr>
                        <th>产品图片2 :</th>
                        <td>
                            <img id="img_show" src="" style="display:none" style="width:200px;" /><br /><br />
                            <input type="file" name="img4" id="img4" class="input-text" size=21 />
                        </td>
                    </tr>

                    <tr>
                        <th>产品图片(长图) :</th>
                        <td>
                            <img id="img_show2" src="" style="display:none" style="width:200px;" /><br /><br />
                            <input type="file" name="img2" id="img2" class="input-text" size=21 />
                        </td>
                    </tr>

                    <tr>
                        <th>是否能使用优惠券 :</th>
                        <td><input type="radio" name="is_coupon" class="radio_style" value="1" checked>&nbsp;是&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="is_coupon" class="radio_style" value="0" >&nbsp;不
                        </td>
                    </tr>
                    <tr>
                        <th>首页热门推荐状态 :</th>
                        <td><input type="radio" name="is_hots" class="radio_style" value="1" checked>&nbsp;推荐&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="is_hots" class="radio_style" value="0" >&nbsp;不推荐
                        </td>
                    </tr>
                    <tr>
                        <th>推荐状态 :</th>
                        <td><input type="radio" name="recom" class="radio_style" value="1" checked>&nbsp;推荐&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="recom" class="radio_style" value="0" >&nbsp;不推荐
                        </td>
                    </tr>
                    <th>商品状态 :</th>
                    <td><input type="radio" name="status" class="radio_style" value="1" checked>上架&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="status" class="radio_style" value="0" >下架
                    </td>
                    <tr>
                        <th>是否参与今日推荐活动:</th>
                        <td><input type="radio" name="canyuhd" class="canyuhd" value="1" <?php if($items["canyuhd"] == 1): ?>checked<?php endif; ?>>
                    &nbsp;是&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="canyuhd" class="canyuhd" value="0" <?php if($items["canyuhd"] == 0): ?>checked<?php endif; ?>>
                    &nbsp;否
                    </td>
                    </tr>
                    <tr>
                        <th>今日推荐活动是否推荐该商品:</th>
                        <td><input type="radio" name="hdtuijian" class="radio_style" value="1" <?php if($items["hdtuijian"] == 1): ?>checked<?php endif; ?>>
                    &nbsp;是&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="hdtuijian" class="radio_style" value="0" <?php if($items["hdtuijian"] == 0): ?>checked<?php endif; ?>>
                    &nbsp;否
                    </td>
                    </tr>
                    <tr>
                        <th>是否自备货:</th>
                        <td><input type="radio" name="bbshop" class="radio_style" value="1" <?php if($items["bbshop"] == 1): ?>checked<?php endif; ?>>
                    &nbsp;是&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="bbshop" class="radio_style" value="0" <?php if($items["bbshop"] == 0): ?>checked<?php endif; ?>>
                    &nbsp;否
                    </td>
                    </tr>
                    <tr>
                        <th>推荐理由 :</th>
                        <td><textarea name="hdtjtext" id="hdtjtext" cols="80" rows="8" placeholder="请输入推荐理由，最多100个字"><?php echo ($items["hdtjtext"]); ?></textarea></td>
                    </tr>
                    <tr>
                        <th>显示顺序:</th>
                        <td><input type="text" name="sort_order" size="60"  class="input-text" id="sort_order"   placeholder="请输入显示顺序，数越大越靠前"></td>
                    </tr>

                    <tr> <th width="100">相关URL :</th>
                        <td>
                            <input type="text"   class="textinput requireinput" name="url" id="url"  size="60" placeholder="请输入相关URL">
                        </td>
                    </tr>
                    <tr>
                        <th>简单描述 :</th>
                        <td><textarea name="remark" id="remark" cols="80" rows="8" placeholder="请输入简单描述，最多100个字"></textarea></td>
                    </tr>
                    <!--<tr>
                                 <th>是否关闭 :</th>
                                 <td><input type="radio" name="closed" class="radio_style" value="1">&nbsp;打开&nbsp;&nbsp;&nbsp;
                                     <input type="radio" name="closed" class="radio_style" value="0" checked="checked">&nbsp;关闭
                                 </td>
                             </tr>
         
                             <tr>
                                 <th>关闭原因 :</th>
                                 <td><textarea name="close_reason" id="close_reason" cols="80" rows="8"></textarea></td>
                             </tr>
                             <tr>
                                 <th>商品标签 :</th>
                                 <td><textarea name="tags" id="tags" cols="80" rows="8"></textarea></td>
                             </tr>-->
                    </tbody>
                </table>
            </div>

            <div id="div_setting_2" class="contentList pad-10 hidden">
                <table width="100%" cellpadding="2" cellspacing="1" class="table_form">
                    <tr>
                        <th width="100">详细信息:</th>
                        <td>	<script type="text/javascript" src="__ROOT__/includes/kindeditor/kindeditor.js"></script><script type="text/javascript" src="__ROOT__/includes/kindeditor/lang/zh_CN.js"></script><script> var editor; KindEditor.ready(function(K) { editor = K.create('#desc');});</script><textarea id="desc" style="width:70%;height:350px;" name="desc" ></textarea></td>
                    </tr>
                </table>
            </div>
            <div id="div_setting_3" class="contentList pad-10 hidden">
                <table width="100%" cellpadding="2" cellspacing="1" class="table_form">
                    <tr>
                        <th width="100">SEO标题:</th>
                        <td><input type="text" name="seo_title" id="seo_title" class="input-text" size="60"  placeholder="请输入商品名称"   value=""></td>
                    </tr>
                    <tr>
                        <th>SEO关键字 :</th>
                        <td><input type="text" name="seo_keys" id="seo_keys" class="input-text" size="60"  placeholder="请输入商品名称"  value=""></td>
                    </tr>
                    <tr>
                        <th>SEO描述:</th>
                        <td><textarea name="seo_desc" id="seo_desc" cols="80" rows="8"  placeholder="请输入商品名称" ></textarea></td>
                    </tr>
                </table>
            </div>
            <div class="bk15">

                <input type="hidden" name="simg" id="simg" value="" />
                <input type="hidden" name="bimg" id="bimg" value="" />
            </div>
            <div><input type="submit" value="<?php echo (L("submit")); ?>" name="dosubmit" class="smt " id="dosubmit"></div>

        </div>
    </div>

</form>
<script type="text/javascript">
    function SwapTab(name, cls_show, cls_hide, cnt, cur) {
        for (i = 1; i <= cnt; i++) {
            if (i == cur) {
                $('#div_' + name + '_' + i).show();
                $('#tab_' + name + '_' + i).attr('class', cls_show);
            } else {
                $('#div_' + name + '_' + i).hide();
                $('#tab_' + name + '_' + i).attr('class', cls_hide);
            }
        }
    }
    $(function () {
        $("#price").blur(function () {
            var str = $(this).val();
            str = $.trim(str);
            var fp_price = $("#fp_price").val();
            if (str != '' && fp_price == '' && parseFloat(str) > 0) {
                var money = parseFloat(str) * 0.4;//
                $('#fp_price').val(money);
            }
        });
        $.formValidator.initConfig({
            formid: "myform",
            autotip: true,
            onerror: function (msg, obj) {
                window.top.art.dialog({
                    content: msg,
                    lock: true,
                    width: '200',
                    height: '50'},
                        function ()
                        {
                            this.close();
                            $(obj).focus();
                        })
            }});

        $("#pcode").formValidator({
            onshow: "不能为空", onfocus: "商品编号不能为空哦"
        }).inputValidator({
            min: 4, max: 20, onerror: " 商品编号不能少于4位"
        });
        $("#good_name").formValidator({
            onshow: "不能为空", onfocus: "商品名称不能为空哦"
        }).inputValidator({
            min: 4, onerror: "请输入商品名称"
        });

        $("#guige").formValidator({
            onshow: "不能为空", onfocus: "商品规格不能为空哦"
        }).inputValidator({
            min: 1, onerror: "请输入商品规格"
        });

        $("#goodtype").formValidator({
            onshow: "不能为空", onfocus: "商品类型不能为空哦"
        }).inputValidator({
            min: 1, max: 1, onerror: "请输入一位数的商品类型"
        });

        $("#pd_id").formValidator({
            onshow: "商品分类必选", onfocus: "选择商品分类哦"
        }).inputValidator({
            min: 1, onerror: "选择商品分类哦"
        });


        $("#cate_id").formValidator({
            onshow: "商品分类必选", onfocus: "选择商品分类哦"
        }).inputValidator({
            min: 0, onerror: "选择商品分类哦"
        });
        $("#mp_id").formValidator({
            onshow: "商品品牌必选", onfocus: "选择商品品牌哦"
        }).inputValidator({
            min: 0, onerror: "选择商品品牌哦"
        });
        $("#mp_cate").formValidator({
            onshow: "商品品类必选", onfocus: "商品品类必选哦"
        }).inputValidator({
            min: 0, onerror: "商品品类必选哦"
        });
        $("#nation_id").formValidator({
            onshow: "商品所属国籍必选", onfocus: "商品所属国籍必选哦"
        }).inputValidator({
            min: 0, onerror: "商品所属国籍必选哦"
        });
        $("#gx_id").formValidator({
            onshow: "不能为空", onfocus: "请选择活动标签哦"
        }).inputValidator({
            min: 0, onerror: "活动标签必选哦"
        });
//        $("#gx_id").formValidator({
//            onshow: "商品功效必选", onfocus: "选择商品功效哦"
//        }).inputValidator({
//            min: 1, onerror: "选择商品功效哦"
//        });
        $("#fh_type").formValidator({
            onshow: "不能为空", onfocus: "发货不能为空哦"
        }).inputValidator({
            min: 1, onerror: "请选择发货方式"
        });
        $("#sort_order").formValidator({onshow: "请输入显示顺序，数越大越靠前（1-99）（注意：1-5是商品界面图，从6开始是售卖商品）", onfocus: "只能输入（1-99）之间的整数哦（注意：1-5是商品界面图，从6开始是售卖商品）"});

        $("#orgprice").formValidator({
            onshow: "不能为空", onfocus: "利润商品原价不能为空哦"
        }).inputValidator({
            min: 1,onerror: "请输入利润商品原价"
        });
       
        $("#price").formValidator({
            onshow: "不能为空", onfocus: "商品售价不能为空哦"
        }).inputValidator({
            min: 1,onerror: "请输入商品售价"
        }).compareValidator({
            desid:"orgprice",operateor:"<",datatype:"number",onerror: "商品售价要小于利润商品原价"
        });
        
        $("#fp_price").formValidator({
            onshow: "不能为空", onfocus: "利润分配价格不能为空哦"
        }).inputValidator({
            min: 1,onerror: "请输入利润分配价格"
        }).compareValidator({
            desid:"price",operateor:"<",datatype:"number",onerror: "利润分配价格要小于商品售价"
        });
       
        $("#kucun").formValidator({
            onshow: "不能为空", onfocus: "库存不能为空哦"
        }).inputValidator({
            min: 1,onerror: "请输入库存"
        });

    });


</script>
<script type="text/javascript">
    $(function () {
        $.ajax({
            url: "<?php echo U('goods/get_pcate');?>",
            type: 'POST',
            dataType: 'JSON',
            timeout: 5000,
            error: function () {
                alert('Error loading data!');
            },
            success: function (msg) {
                $("#pcate_id").empty();
                $.each(eval(msg), function (i, item) {
                    $("<option value='" + item.id + "'>" + item.name + "</option>").appendTo($("#pcate_id"));
                });
                //  loadCity($("#pcate").val());
            }
        });
        $("#pcate_id").change(function () {
            loadCate($("#pcate_id").val());
            loadMp($("#pcate_id").val());
            loadMpCate($("#pcate_id").val());
            loadNatList($("#pcate_id").val());
        });

        function loadCate(parentid) {
            $.ajax({
                url: '<?php echo U("goods/get_cate");?>',
                type: 'POST',
                data: {id: parentid},
                dataType: 'JSON',
                timeout: 5000,
                error: function () {
                    alert('Error loading data!');
                },
                success: function (msg) {
                    $("#cate_id").empty();
                    $.each(eval(msg), function (i, item) {
                        $("<option value='" + item.id + "'>" + item.name + "</option>").appendTo($("#cate_id"));
                    });
                }
            });
        }

        function loadMp(parentid) {
            $.ajax({
                url: '<?php echo U("goods/get_mp");?>',
                type: 'POST',
                data: {id: parentid},
                dataType: 'JSON',
                timeout: 5000,
                error: function () {
                    alert('Error loading data!');
                },
                success: function (msg) {
                    $("#mp_id").empty();
                    $.each(eval(msg), function (i, item) {
                        $("<option value='" + item.id + "'>" + item.name + "</option>").appendTo($("#mp_id"));
                    });
                }
            });
        }

        function loadMpCate(parentid) {
            $.ajax({
                url: '<?php echo U("goods/get_mp_cate");?>',
                type: 'POST',
                data: {id: parentid},
                dataType: 'JSON',
                timeout: 5000,
                error: function () {
                    alert('Error loading data!');
                },
                success: function (msg) {
                    $("#mp_cate").empty();
                    $.each(eval(msg), function (i, item) {
                        $("<option value='" + item.id + "'>" + item.name + "</option>").appendTo($("#mp_cate"));
                    });
                }
            });
        }

        function loadNatList(parentid) {
            $.ajax({
                url: '<?php echo U("goods/nat_list");?>',
                type: 'POST',
                data: {id: parentid},
                dataType: 'JSON',
                timeout: 5000,
                error: function () {
                    alert('Error loading data!');
                },
                success: function (msg) {
                    console.log(msg);
                    $("#nation_id").empty();
                    $.each(eval(msg), function (i, item) {
                        $("<option value='" + item.id + "'>" + item.name + "</option>").appendTo($("#nation_id"));
                    });
                }
            });
        }
    })
</script>
</body></html>