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
<script></script>
</head>
<body class="J_scroll_fixed">
    <div class="wrap J_check_wrap">
        <ul class="nav nav-tabs">
            <li class="active"><a href="javascript:;">商品搜索</a></li>
        </ul>
        <form class="well form-search" method="post" action="<?php echo U('goods/index');?>">
            <div class="search_type cc mb10">
                <div class="mb10">
                    <span class="mr20">
                        &nbsp;商品ID：
                        <input type="text" name="id" style="width: 200px;" value="<?php echo ($formget["id"]); ?>" placeholder="请输入商品ID...">
                        &nbsp;商品名称：
                        <input type="text" name="good_name" style="width: 200px;" value="<?php echo ($formget["good_name"]); ?>" placeholder="请输入名称...">
                        <input type="hidden" name="sid" value="<?php echo ($sid); ?>" />
                        <input type="submit" class="btn btn-primary" value="搜索" />
                        <input type="reset" class="btn btn-primary" value="重置" />
                    </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span style="color: red">温馨提示：单个台签尺寸为5.5cmx8.7cm，一张a4纸上做多可容纳9张台签。</span>
                </div>
            </div>
        </form>
        <form id="form2" method="post" action="<?php echo U('goods/createlable');?>" target="_blank" />
        <input type="hidden" value="<?php echo ($sid); ?>" name="sid"  />
        <table class="table table-hover table-bordered table-list">
            <thead>
                <tr>
                    <td></td>
                    <th>ID</th>
                    <th>分类</th>
                    <th>名称</th>
                    <th>图片</th>
                    <th>规格</th>
                    <th>价格</th>
                    <th>库存</th>
                    <th>人气</th>
                    <th>操作</th>
                </tr>
            </thead>
            <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
                    <td><input type="checkbox" value="<?php echo ($vo["id"]); ?>" name="ids[]" /></td>
                    <td><?php echo ($vo["id"]); ?></td>
                    <td><?php echo ($vo["cate_name"]); ?></td>
                    <td><?php echo ($vo["good_name"]); ?></td>
                    <td><img src="../<?php echo ($vo["simg"]); ?>" style="max-width:200px;max-height:200px;"/></td>
                    <td><?php echo ($vo["guige"]); ?></td>
                    <td><?php echo ($vo["price"]); ?></td>
                    <td><?php echo ($vo["kucun"]); ?></td>
                    <td><?php echo ($vo["hits"]); ?></td>
                    <td>
                        <a href="<?php echo U('goods/goodlabel',array('id'=>$vo['id'],'sid'=>$sid));?>" >生成台签</a>
                    </td>
                </tr><?php endforeach; endif; ?>
            <tfoot>
            <td></td>
            <th>ID</th>
            <th>分类</th>
            <th>名称</th>
            <th>图片</th>
            <th>规格</th>
            <th>价格</th>
            <th>库存</th>
            <th>人气</th>
            <th>操作</th>
            </tfoot>
        </table>
        <div style="line-height: 30px; background: #f6f6f6">
            <input id="Button1" type="button" value="全选" onclick="allSelect();" />&nbsp;&nbsp;
            <input id="Button2" type="button" value="反选" onclick="otherSelect();" />&nbsp;&nbsp;
            <input id="Button3" type="submit" value="批量生成台签"  />&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             <span style="color: red">温馨提示：单个台签尺寸为5.5cmx8.7cm，一张a4纸上做多可容纳9张台签。</span>
        </div>
        </form>
        <div class="pagination pull-right"><span href="#" class="btn btn-primary disabled"><?php echo ($count); ?>条记录</span><?php echo ($Page); ?></div>
    </div>
    <script src="/store/statics/js/common.js"></script>
    <script>
                $("input[type='reset']").click(function () {
                    $(this).siblings("input").not("input[type='submit']").attr("value", "");
                    $("select").find("option").attr("selected", false);
                });

                //全选，全不选
                function allSelect() {
                    if ($(":checkbox").attr("checked") != "checked") {
                        $(":checkbox").attr("checked", "checked");
                    } else {
                        $(":checkbox").removeAttr("checked");
                    }
                }
                //反选
                function otherSelect() {
                    $(":checkbox").each(function () {
                        if ($(this).attr("checked") == "checked") {
                            $(this).removeAttr("checked");
                        } else {
                            $(this).attr("checked", "checked");
                        }
                    });
                }              

    </script>
</body>
</html>