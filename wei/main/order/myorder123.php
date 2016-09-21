<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://" . $_SERVER['HTTP_HOST'];
session_start();
if (!isset($_SESSION["access_token"])) {
    header("Location:" . $cxtPath . "/wei/login.php");
    return;
}
$cartnum = 0;
if (isset($_SESSION["cartnum"])) {
    $cartnum = $_SESSION["cartnum"];
}
$type = intval($_GET["type"]);
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];
$dianpu_huiyuan = 0;
if ($_GET["dianpu_huiyuan"] == 1)
    $dianpu_huiyuan = 1;

$do = new condbwei();
$com = new commonFun();

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$imgurl = 'http://' . $_SERVER['HTTP_HOST'] . "/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta content="telephone=no" name="format-detection" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
            <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
            <title>我的订单</title>
            <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
            <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v2.0" />
            <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
            <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
            <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
            <script type="text/javascript">
                wx.config({
                    appId: '<?php echo $shareCheckArr["appid"]; ?>',
                    timestamp: '<?php echo $shareCheckArr["timestamp"]; ?>',
                    nonceStr: '<?php echo $shareCheckArr["noncestr"]; ?>',
                    signature: '<?php echo $shareCheckArr["signature"]; ?>',
                    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'hideMenuItems'] // 功能列表，我们要使用JS-SDK的什么功能
                });
                wx.ready(function () {
                    var shareData = {
                        title: '洋仆淘跨境商城——源自于深圳自贸区的专业跨境电商平台',
                        desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
                        link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
                        imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/logo.png',
                        success: function (res) {
                            msgSuccess("分享成功");
                        },
                        cancel: function (res) {
                        }
                    };
                    wx.onMenuShareTimeline(shareData);
                    wx.onMenuShareAppMessage(shareData);
                    wx.onMenuShareQQ(shareData);
                    wx.onMenuShareWeibo(shareData);
                    wx.hideMenuItems({
                        menuList: [
                            'menuItem:readMode',
                            'menuItem:openWithSafari',
                            'menuItem:openWithQQBrowser',
                            'menuItem:copyUrl'
                        ]
                    });
                });
                $(window).bind('resize load', function () {
                    //$("body").css("zoom", $(window).width() / 480);
                    //$("body").css("display" , "block");
                });
                var type = "<?php echo $type ?>";
                var cartnum = "<?php echo $cartnum ?>";
                $(function () {
                    if (parseInt(cartnum) > 0) {
                        $("#ShoppingCartNum").css("display", "block").text(cartnum);
                    }
                    if (type == "0") {
                        $("#type1").append("<div id='titleact' style='background:#FFC43A;width:100%;height:3px;margin-top:3px;'></div>");
                    } else if (type == "1") {
                        $("#type2").append("<div id='titleact' style='background:#FFC43A;width:100%;height:3px;margin-top:3px;'></div>");
                    } else {
                        $("#typen").append("<div id='titleact' style='background:#FFC43A;width:100%;height:3px;margin-top:3px;'></div>");
                    }
                    //loadData(type);
                    $("#type1").click(function () {
                        loadData("0");
                        removeActive("1");
                    });
                    $("#type2").click(function () {
                        loadData("1");
                        removeActive("2");
                    });
                    $("#typen").click(function () {
                        loadData("");
                        removeActive("n");
                    });
                });
                function loadData(typesh) {
                    $.ajax({
                        type: "POST",
                        url: "myorderSearch123.php",
                        data: "dianpu_huiyuan=<?php echo $dianpu_huiyuan; ?>&type=" + typesh,
                        success: function (data) {
                            $(".orderdiv").each(function () {
                                $(this).remove();
                            });
                            data = eval("(" + data + ")");
                            var total = 0;
                            for (var i = 0; i < data.length; i++) {
                                var buynum = data[i].buynum;
                                var price = data[i].price;
                                var appStr = "<div class='orderdiv' onclick=\"orderdet('" + data[i].order_id + "')\" style='margin-top:2%;width:100%;height:150px;background:white;'>";
                                appStr += "<div class='userxiao' style='height:30px;line-height:30px;background:#F3F3F3'>";
                                appStr += "<font style='font-size:16px;color:#535758;'><strong>下单时间：" + data[i].order_time + "</strong></font>";
                                appStr += "</div>";
                                appStr += "<img style='width:100px;height:100px;margin-top:10px;margin-left:10px;float:left;' src='<?php echo $imgurl ?>" + data[i].img + "' />";
                                appStr += "<div style='width:auto;height:100px;margin-top:10px;margin-left:10px;float:left;'>";
                                appStr += "<p style='font-size:14px;'>订单编号：" + data[i].order_sn + "</p>";
                                appStr += "<p style='font-size:14px;'>支付编号：" + data[i].payment_code + "</p>";
                                appStr += "<p style='font-size:14px;'>订单金额：<font style='color:#FF0000'>￥" + data[i].price + "</font></p>";
                                appStr += "<p style='font-size:14px;'>商品数量：" + data[i].quantity + "</p>";
                                appStr += "<p style='font-size:14px;'>运&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;费：<font style='color:#FF0000'>包邮</font></p>";
                                var status = "已支付";
                                if (data[i].status == "0")
                                    status = "未支付"
                                appStr += "<p style='font-size:14px;'>支付状态：" + status + "</p>";
                                appStr += "</div>";
                                appStr += "<img src='../../images/rightjian.png' style='margin-top:40px;margin-right:10px;float:right;'></img></div>";
                                $("body").append(appStr);
                            }
                        }
                    });
                }
                function removeActive(tagid) {
                    $("#titleact").remove();
                    $("#type" + tagid).append("<div id='titleact' style='background:#FFC43A;width:100%;height:3px;margin-top:3px;'></div>");
                }
                function orderdet(id) {
                    location.replace("orderdet.php?order_id=" + id);
                }
                function tuihuan(id, bianhao) {
                    location.replace("thhuo_tianxie.php?orderid=" + id + "&bianhao=" + bianhao);
                }
                $(function () {
                    xuan(<?php echo $type; ?>);
                })
                var yem = 0;
                var dql = <?php echo $type; ?>;
                var type = 0;
                function xuan(lea) {
                    type = lea;
                    $('#shenmegui td').css('color', '#666');
                    if (lea == 0) {
                        $('#qb').css('color', '#fe7182');
                        dql = lea;
                    } else if (lea == 1) {
                        $('#dfk').css('color', '#fe7182');
                        dql = lea;
                    } else if (lea == 2) {
                        $('#dsh').css('color', '#fe7182');
                        dql = lea;
                    } else if (lea == 3) {
                        $('#thh').css('color', '#fe7182');
                        dql = lea;
                    }
                    if (lea == 99) {
                        lea = dql;
                        yem += 20;
                    } else {
                        $("#dd_clb").empty();
                        yem = 0;
                    }
                    $.ajax({
                        type: "POST",
                        url: "myorderSearch123.php",
                        data: {xuanzheb: lea, postyem: yem, dianpu_huiyuan:<?php echo $dianpu_huiyuan; ?>},
                        success: function (data) {
                            if (data == "" || data == null) {
                                $('#jzgd').css('display', 'none');
                                $("#dd_clb").append("<div style=\"text-align:center; height:200px; line-height:200px; font-size:14px; color:#999;\">暂无订单</div>");
                                return;
                            }
                            //alert(data);
                            data = eval("(" + data + ")");
                            var appStr = "";
                            if (data.length > 0) {
                                if (data.length < 20)
                                    $('#jzgd').css('display', 'none');
                                else
                                    $('#jzgd').css('display', 'block');
                                for (var i = 0; i < data.length; i++) {
                                    appStr += "<div class=\"dd_ddk\">";
                                    appStr += "<table class=\"dd_tab\" onclick=\"orderdet('" + data[i].order_id + "')\" >";
                                    var jifendh = data[i].jifendh;
                                    var shop = data[i].sangping;
                                    if (shop.length > 0) {
                                        for (var j = 0; j < shop.length; j++) {
                                            appStr += "<tr align=\"center\">";
                                            appStr += "<td class=\"imag\"><img src='<?php echo $imgurl; ?>" + shop[j].goods_images + "' /></td>";
                                            appStr += "<td class=\"text\" valign=\"top\" align=\"left\" style='position:relative;'>";
                                            appStr += "<div class=\"ti\">" + shop[j].goods_name + "</div>";
                                            appStr += "<div class=\"guig\">" + shop[j].specification + "</div>";
                                            if (jifendh == 0) {
                                                appStr += "<div class=\"jiag\"><span  style='float:left'>￥" + shop[j].price + "*" + shop[j].quantity + "</span>";
                                                if (data[i].status == 0)
                                                    appStr += "<span class='zt'>未付款</span>";
                                                else if (data[i].status == 1)
                                                    appStr += "<span class='zt'>已付款</span>";
                                                else if (data[i].status == 4)
                                                    appStr += "<span class='zt'>已完成</span>";
                                                else if (data[i].status == 2 && shop[j].kd == 0)
                                                    appStr += "<span class='zt'>清关中</span>";
                                                else if (data[i].status < 4 && shop[j].kd == 1)
                                                    appStr += "<span class='zt'>已发货</span>";
                                                appStr += "</div>";
                                            } else
                                                appStr += "<div class=\"jiag\">积分：" + shop[j].price + "</div>";
                                            if (shop[j].status == 0 && data[i].status == 0) {
                                                appStr += "<div style='width:70px;position:absolute; top:21px; right:30px;height:28px;line-height:28px;font-size:14px; border:1px solid #fe7182;text-align:center;color:#fe7182;'>已过期</div>";
                                            }
                                            appStr += "</td>";
                                            appStr += "</tr>";
                                        }
                                    } else {
                                        shopStr = "";
                                    }

                                    appStr += "</table>";
                                    appStr += "<div class=\"dd_jiezhangla\">";
                                    appStr += "<div class=\"dd_jz_left\">";
                                    appStr += "<div>订单号：" + data[i].order_sn + "</div>";
                                    appStr += "<div>" + data[i].order_time + "</div>";
                                    appStr += "</div>";
                                    if (data[i].status == 0) {
                                        if (data[i].erweima == 3 || data[i].erweima == 1) {
                                            appStr += "<div class=\"dd_jz_right\" ><span onclick=\"gotord(" + data[i].order_id + ")\">去付款</span></div>";
                                        } else if (data[i].erweima == 0) {
                                            appStr += "<div class=\"dd_jz_right\"><span>等待审核中...</span></div>";
                                        } else if (data[i].erweima == 2) {
                                            appStr += "<div class=\"dd_jz_right\"><span>审核不通过</span></div>";
                                        }
                                    } else if (data[i].status == 1 || data[i].status == 2 || data[i].status == 3) {
                                        appStr += "<div class=\"dd_jz_right\">";
                                        if (data[i].status == 2) {
                                            appStr += "<img onclick=\"qgz('" + data[i].order_id + "','qgz')\" src='<?php echo $imgurl; ?>wei/img/dd_wuliu.png' />";
                                        } else {
                                            appStr += "<img onclick=\"kanwl('" + data[i].order_id + "')\" src='<?php echo $imgurl; ?>wei/img/dd_wuliu.png' />";
                                        }

                                        if (data[i].status == 3)
                                            appStr += "<img onclick=\"affirm('" + data[i].order_id + "')\" src='<?php echo $imgurl; ?>wei/img/dd_shouhuo.png' />";
                                        //appStr+="<span onclick=\"confirm('" + data[i].order_id + "','" + data[i].order_sn + "')\">确认收货</span>";
                                        appStr += "</div>";
                                    } else if (data[i].status == 4) {
                                        if (data[i].fb_plun != 0)
                                            appStr += "<div class=\"dd_jz_right\"><span>已评价</span></div>";
                                        else
                                            appStr += "<div class=\"dd_jz_right\"><span onclick=\"pj('" + data[i].order_id + "')\">去评价</span></div>";
                                    } else if (data[i].status == 6)
                                        appStr += "<div class=\"dd_jz_right\"><span>退换货申请中..</span></div>";
                                    else if (data[i].status == 7)
                                        appStr += "<div class=\"dd_jz_right\" onclick=\"tuihuan('" + data[i].order_id + "','" + data[i].order_sn + "')\" ><span>可退换货(需填写)</span></div>";
                                    else if (data[i].status == 8)
                                        appStr += "<div class=\"dd_jz_right\"><span>退货物流</span></div>";
                                    else if (data[i].status == 9)
                                        appStr += "<div class=\"dd_jz_right\"><span>已完成</span></div>";
                                    else if (data[i].status == 10)
                                        appStr += "<div class=\"dd_jz_right\"><span>审核不通过</span></div>";
                                    appStr += "<div style=\"clear:both;height:5px;\"></div>";
                                    appStr += "</div>";
                                    if (data[i].status == 0)
                                        appStr += "<div class=\"dd_dele\"><span data-b=\"" + data[i].order_id + "\">删除该订单<span></div>";
                                    appStr += "</div>";
                                    $("#dd_clb").append(appStr);
                                    appStr = "";

                                }
                            } else {
                                $('#jzgd').css('display', 'none');
                                $("#dd_clb").append("<div style=\"text-align:center; height:200px; line-height:200px; font-size:14px; color:#999;\">暂无订单</div>");
                            }

                            $('.dd_dele span').unbind("click");
                            $('.dd_dele span').click(function () {
                                var $this = $(this);
                                $.ajax({
                                    type: "POST",
                                    url: "deleteorder.php",
                                    data: {dia: $(this).attr('data-b')},
                                    success: function (data) {
                                        if (data == 1) {
                                            $this.parent().parent('.dd_ddk').remove();
                                        }
                                    }
                                });
                            })

                        }
                    });
                }
                function gotord(ord) {
                    window.open("../order/order.php?orderid=" + ord);
                }
                function kanwl(ord) {
                    window.open("../order/wuliu.php?order_id=" + ord);
                }
                function qgz(ord,qgz) {
                    window.open("../order/wuliu.php?order_id=" + ord+"&status="+qgz);
                }

                function affirm(order_id) {
                    $.post("orderkd.php", {order_id: order_id}, function (data) {
                        if (data == 2)
                            if (!window.confirm("目前还有商品没有签收,确认收货吗?"))
                                return;
                            else if (data == 3) {
                                alert("订单不存在!");
                                return;
                            }
                        $.post("orderConfirm.php", {order_id: order_id}, function (data) {
                            data = eval("(" + data + ")");
                            if (data.success == 1)
                                alert("确认收货成功");
                            location.reload(true);
                        });
                    });
                }
                function pj(order_id) {
                    location.href = "http://m.hz41319.com/wei/main/order/sppl.php?sppl=" + order_id;
                }
            </script>
    </head>
    <style>
        .dd_ti{ width:100%; margin:5px 0;}
        .dd_ti tr{ width:100%; height:25px;}
        .dd_ti tr td{ width:25%; font-size:12px; border-right:1px solid #f4f4f4; color:#666;}
        .dd_ddk{ width:98%; border:1px solid #ebebeb; margin:5px auto;}

        .dd_tab{ width:98%; margin:5px auto;}
        .dd_tab tr{ width:100%; margin-bottom:5px;}
        .dd_tab .imag{ width:30%;}
        .dd_tab .imag img{ width:100%;}
        .dd_tab .text{ width:70%;padding:10px;}
        .dd_tab .text .ti{ font-size:14px; color:#555;}
        .dd_tab .text .guig{ font-size:14px; color:#888; margin-top:10px;}
        .dd_tab .text .jiag{ font-size:14px; color:#fe7182; text-align:right; margin-top:20px;}
        .dd_jiezhangla{ width:100%; border-top:1px solid #ebebeb;}
        .dd_jiezhangla .dd_jz_left{ float:left; padding:5px}
        .dd_jiezhangla .dd_jz_left div{ font-size:12px;color:#888; heoght:16px; line-height:16px;}
        .dd_jiezhangla .dd_jz_right{ float:right;}
        .dd_jiezhangla .dd_jz_right span{ width:8em;margin:0.5em 0.5em 0 0;font-size:1.3em;display:inline-block;color:#fff;background:#fe7182;text-align:center;height:2.4em;line-height:2.5em;
                                          border-radius:4px;
                                          -moz-border-radius:4px;
                                          -webkit-border-radius:4px;
                                          -o-border-radius:4px;
                                          -ms-border-radius:4px;}
        .dd_jiezhangla .dd_jz_right img{ width:80px; margin: 8px 20px 0 0;}

        .jzgd{ height:35px; width:98%; line-height:35px; font-size:14px; color:#fff; background:#fe7182; margin:5px auto; text-align:center;display:none;}

        .dd_dele{ font-size:12px;height:25px;line-height:25px;border-top:1px solid #ebebeb;color:#888;}
        .dd_dele span{ padding-left:10px;}

        .zt{font-size:1.2em;padding-right:0.7em}
    </style>
    <body style="background:#fff;padding:0;margin:0;">
        <table class="dd_ti" align="center">
            <tr align="center" valign="middle" id="shenmegui">
                <td id="qb" onclick="xuan(0)">全部</td>
                <td id="dfk" onclick="xuan(1)">待付款</td>
                <td id="dsh" onclick="xuan(2)">待收货</td>
                <td id="thh" onclick="xuan(3)">退换货</td>
            </tr>
        </table>
        <div style="border-top:1px solid #ebebeb;height:0;"></div>
        <div id="dd_clb"></div>
        <div id="jzgd" class="jzgd" onclick="xuan(99)">加载更多</div>
        <div style="height:80px;"></div>

        <?php include '../gony/guanzhu.php'; ?>
        <?php include '../gony/returntop.php'; ?>
        <?php include '../gony/nav.php'; ?>
    </body>
</html>