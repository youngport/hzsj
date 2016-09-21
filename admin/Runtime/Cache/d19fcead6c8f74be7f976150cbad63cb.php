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
<script src="__ROOT__/statics/js/common.js"></script>
<script src="__ROOT__/statics/js/echarts.min.js"></script>
<div class="pad-10" >
    <form method="get" >
        <input type="hidden" name="m" value="orders"/>
        <input type="hidden" name="a" value="order_count"/>
        <table width="100%" cellspacing="0" class="search-form">
            <tbody>
            <tr>
                <td>
                    <div class="explain-col">                                         
                        时间：
                        <input id="time_start" style="width: 120px;" type="text"  value="<?php echo ($start_time); ?>" placeholder="选择下单起始时间" onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',minDate:'2015-01-01'})"
                               name="start_time" />-到-<input id="time_end" style="width: 120px;" type="text"
                                                           onclick="WdatePicker({skin:'whyGreen',readOnly:true,dateFmt:'yyyy-MM-dd',minDate:'2015-01-01'})"
                                                           name="end_time"  value="<?php echo ($end_time); ?>" placeholder="选择下单截止时间" />                       
                        <input type="submit" name="search" class="button" value="搜索" />
                      <!--   <input type="submit" name="daochu" class="button" value="搜索结果导出Excel" /> -->
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    
        <div class="table-list">
            <table width="100%" cellspacing="0">
                <thead>
                <tr>                   
                    <th>下单数量</th>
                    <th>付款数量 </th>
                    <th>下单总额</th>
                    <th>付款总额</th>                   
                </tr>
                </thead>
                <tbody>
                
                    <tr>
                       
                        <td align="center"><?php echo ($count); ?></td>
                        <td align="center"><?php echo ($order_count); ?></td>
                        <td align="center"><?php echo ($amount); ?> </td>
                        <td align="center"><?php echo ($order_amount); ?></td>                       
                </tr>
                </tbody>
            </table>
        <div style="char:both;margin-top:30px;"></div>
        <div id="chart" style="width:100%;height:400px;">
        </div>
    </div>
    <script>
        var chart=<?php echo ($chart); ?>;
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