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
</head>
<body class="J_scroll_fixed">
	<div class="wrap J_check_wrap">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('Store/index');?>" target="_self">所有终端</a></li>
			<li class="active"><a href="javascript:;">订单统计</a></li>
		</ul>
		<form class="well form-search" method="post" action="<?php echo U('Store/order_count');?>">
			<div class="search_type cc mb10">
				<div class="mb10">
					<span class="mr20">
						&nbsp;日期：
						<input type="text" name="start_time" class="J_date" value="<?php echo ($formget["start_time"]); ?>" style="width: 100px;" autocomplete="off">-
						<input type="text" class="J_date" name="end_time" value="<?php echo ($formget["end_time"]); ?>" style="width: 100px;" autocomplete="off">
						<input type="hidden" name="sid" value="<?php echo ($sid); ?>">
						<input type="submit" class="btn btn-primary" value="搜索" />
						<input type="reset" class="btn btn-primary" value="重置" />
					</span>
				</div>
			</div>
		</form>
		<table class="table table-bordered table-striped">
			<tr>
				<th>下单数</th>
				<th>付款单</th>
				<th>下单金额</th>
				<th>付款金额</th>
			</tr>
			<tr>
				<td><?php echo ($info["xd"]); ?></td>
				<td><?php echo ($info["fd"]); ?></td>
				<td>￥<?php echo ($info["xdmoney"]); ?></td>
				<td>￥<?php echo ($info["fdmoney"]); ?></td>
			</tr>
		</table>
		<div id="chart" style="width:100%;height:400px;">
		</div>
	</div>
	<script src="/store/statics/js/common.js"></script>
	<script src="/store/statics/js/echarts.min.js"></script>
	<script>
		var chart=<?php echo ($chart); ?>;
		$("input[type='reset']").click(function(){
			$(this).siblings("input").not("input[type='submit']").attr("value","");
			$("select").find("option").attr("selected",false);
		});

		var myChart = echarts.init(document.getElementById('chart'));

		// 指定图表的配置项和数据
		option = {
			title : {
				text: '订单统计表'
			},
			tooltip : {
				trigger: 'axis'
			},
			legend: {
				data:['下单数','付款单数']
			},
			toolbox: {
				show : true,
				feature : {
					dataZoom: {},
					dataView: {readOnly: false},
					magicType: {type: ['line', 'bar']},
					restore: {},
					saveAsImage: {}
				}
			},
			xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data :chart.key
				}
			],
			yAxis : [
				{
					type : 'value',
					axisLabel : {
						formatter: '{value}'
					}
				}
			],
			series : [
				{
					name:'下单数',
					type:'line',
					data:chart.xd,
					markPoint : {
						data : [
							{type : 'max', name: '最大值'}
						]
					}
				},
				{
					name:'付款单数',
					type:'line',
					data:chart.fd,
					markPoint : {
						data : [
							{type : 'max', name: '最大值'}
						]
					}
				}
			]
		};


		// 使用刚指定的配置项和数据显示图表。
		myChart.setOption(option);
	</script>
</body>
</html>