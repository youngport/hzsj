<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://" . $_SERVER['HTTP_HOST'];

session_start();
if (!isset($_SESSION["access_token"])) {
    header("Location:" . $cxtPath . "/wei/login.php");
    return;
}

$do = new condbwei();
$com = new commonFun();

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$imgurl = 'http://' . $_SERVER['HTTP_HOST'] . "/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$searchTxt = isset($_POST['searchTxt']) ? addslashes($_POST['searchTxt']) : '';
$openid = $_SESSION["openid"];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta content="telephone=no" name="format-detection" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
            <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
            <title>乐分享</title>
            <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
            <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
            <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/home.css"/>
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
            </script>
            <style>
                html{background:#edeaea;font-family: 微软雅黑;font-size: 1.4em;}
                *{margin: 0px 0px; padding: 0px 0px;}
                .laizi{ height:2.2em;background:#fff;line-height:2.2em;margin-top:0.7em;font-size:1.8em;padding-bottom:0.2em;}
                .laizi img{ width:1.8em; height:1.8em; margin-left:0.5em; margin-right:0.5em;
                            border-radius:50%;
                            -moz-border-radius:50%;
                            -webkit-border-radius:50%;
                            -o-border-radius:50%;
                            -ms-border-radius:50%;}
                .imgdatu{ width:20px;}
                .biaoti{ font-size:1.5em; line-height:1.3em;background:#fff;border-bottom:1px solid #c2bebe;padding:0.5em 1em;}
                .shuxing{background:#fff;}
                .shuxing div{ float:left;font-size:1.3em;line-height:2.4em; color:#666;}
                .fabiao{ width:7em;height:2.5em;font-size:1.5em;color:#fff;text-align:center;background:#fe7182;line-height:2.5em;margin:0.5em auto;
                         border-radius:4px;
                         -moz-border-radius:4px;
                         -webkit-border-radius:4px;
                         -o-border-radius:4px;
                         -ms-border-radius:4px;}
                .wofenxiang{ color:#fff;background:#fe7182;font-size:1.5em;width:1.5em;padding:0.5em 1em 0.5em 0.5em;line-height:1.2em; position:fixed; top:5em;right:-1em;
                             border-radius:4px;
                             -moz-border-radius:4px;
                             -webkit-border-radius:4px;
                             -o-border-radius:4px;
                             -ms-border-radius:4px;}

                .imgList{
                    width: 100%;
                    margin: 0 auto;
                    background-color: white;
                }
                .imgItem{
                    margin: 0px 0px;
                    padding: 0 0;
                    float: left;
                    width: 33%;
                }
                .imgItemg{
                    margin: 0px 0px;
                    padding: 0 0;
                    /* float: left; */
                    width: 100%;
                }
                .imgclass{
                    width: 100%;
                }
                .img_class{
                    width: 90%;
                    height: 124px;
                    margin-left: 5%;
                }
                #search_box {
                    margin-top: 15px;
                    margin-left: 10px;
                    margin-right: 10px;
                    width: 94%;
                    height: 24px;
                    background-color: white;
                    -moz-border-radius: 15px; 
                    -webkit-border-radius: 15px; 
                    border-radius:15px;
                    padding-left: 10px;
                }
                #search_box #s {
                    height: 24px;
                    padding: 0;
                    border: 0;
                    width: 93%;
                    background: none;
                    font-size: 1.8em;
                }
                #search_box #go {
                    float: left;
                }
                #search_box input{
                    padding: 0px 0px;
                }
                .searchTxt{
                    width: 90%;
                }
            </style>
    </head>
    <body style="background:#edeaea">
        <a href="lfx_bianji.php"><div class="wofenxiang">我要分享</div></a>
        <div id="search_box">
            <form action="lfx_index1.php" method="post">
                <input type="image" id="btnimg" src="../../css/img/lsearch.png" height="24" id="go" alt="Search" title="Search" />
                <input type="search" value="<?php echo $searchTxt; ?>" class="searchTxt" name="searchTxt" id="searchTxt" placeholder="搜索" />
            </form>
        </div>
        <div style="clear:both;"></div>
        <div id="content">
        </div>
        <div class='fabiao' id='jiazaigengduo'>加载更多</div>
        <script>
            var limit_i = 0;
            var lfxcate = '';
            var searchTxt = "<?php echo $searchTxt; ?>";
            function jiazai(limit_it, searchTxt) {
                $('#jiazaigengduo').hide();
                $.ajax({
                    type: "POST",
                    url: "lfx_indexget.php",
                    data: {"limit": limit_it, "searchTxt": searchTxt},
                    success: function (data) {
                        var data = eval("(" + data + ")");
                        console.log(data);
                        var shopStr = "";
                        $("#content").empty();
                        console.log(data);
                        if (data.length > 0) {
                            for (var i = 0; i < data.length; i++) {
                                shopStr += '<div id="content_item" class="content_item">';
                                if (data[i].openid == '0') {
                                    shopStr += '    <div class="laizi"><img src="../../img/admintouxiang.png" />管理员</div>';
                                } else if (data[i].openid == '') {
                                    shopStr += '    <div class="laizi"><img src="' + data[i].headimgurl + '" /> 匿名用户 </div>';
                                } else {
                                    shopStr += '    <div class="laizi"><img src="' + data[i].headimgurl + '" />' + data[i].wei_nickname + '</div>';
                                }

                                if (data[i].lfx_cate === '1') {
                                    lfxcate = '最优惠';
                                } else if (data[i].lfx_cate === '2') {
                                    lfxcate = '新入手';
                                } else if (data[i].lfx_cate === '3') {
                                    lfxcate = '爆款';
                                } else if (data[i].lfx_cate === '4') {
                                    lfxcate = '热推';
                                } else if (data[i].lfx_cate === '5') {
                                    lfxcate = '喜爱';
                                }
                                shopStr += '    <div class="biaoti"><img src="../../css/img/tui.png" />&nbsp;&nbsp;<span style="color:red;">' + lfxcate + '</span>&nbsp;&nbsp;' + data[i].neirong + '</div>';
                                shopStr += " <a href='lfx_xiangqin.php?spid=" + data[i].id + "'>";
                                shopStr += '    <div class="imgList">';
                                //console.log(data[i].imgurl);
                                $.each(data[i].imgurl, function (img, item) {
                                    var imgurl = data[i].imgurl[img].s_imgurl;
                                    if (imgurl == '') {
                                        imgurl = data[i].imgurl[img].imgurl;
                                    }
                                     if (data[i].openid == '0' ) {
                                    shopStr += '        <div class="imgItemg"><img class="img_class" src="<?php echo $cxtPath; ?>/' + imgurl + '"/ ></div>';
                                 }else{
                                    shopStr += '        <div class="imgItem"><img class="imgclass" src="<?php echo $cxtPath; ?>/' + imgurl + '"/></div>';                                
                                 }
                            });
//                              shopStr += '        <div class="imgItem"><img class="imgclass" src="<?php //echo $cxtPath;      ?>//"'+data[i].imgUrl[img]+'/></div>';
//                                shopStr += '        <div class="imgItem"><img class="imgclass" src="../img/img.png"/></div>';
//                                shopStr += '        <div class="imgItem">';
//                                shopStr += '            <img class="imgclass" src="../img/img.png"/>';
//                                shopStr += '            <div style="position:relative;height:20px; width: 70px;float: right;border:1px solid #585858;bottom:30px;display: block;background-color:black; filter: alpha(opacity=45); opacity:0.7; color: white;text-align: center;"><span style="font-size:18px;" >￥100</span></div>';
//                                shopStr += '        </div>';
                                shopStr += ' 	<div style=\'clear:both;\'></div>';
                                shopStr += '    </div>';
                                shopStr += '    </a>';
                                shopStr += ' <div class="shuxing">';
                                shopStr += '   <div style="width:50%;text-align: left;">&nbsp;<img src="../../css/img/time.png"/>' + data[i].shijian + ' </div>';
                                shopStr += '    <div style="width:20%;text-align: right;">&nbsp;<img src="../../css/img/pl.png"/>&nbsp;' + data[i].pinglun + '</div>';
                                console.log(data[i].dz[0]['dz_count']);
                                if (data[i].dz[0]['dz_count'] == 0) {
                                    shopStr += '    <div style="width:20%;text-align: right;" class="dianzan" data-dzcs="' + data[i].dianzan + '" data-dzid="' + data[i].id + '"><img data-dzid="' + data[i].id + '" src="../../css/img/good.png"/>&nbsp;<span>' + data[i].dianzan + '</span>&nbsp;</div>';
                                } else if(data[i].dz[0]['dz_count']==1) {
                                    shopStr += '    <div style="width:20%;text-align: right;" class="dianzan" data-dzcs="' + data[i].dianzan + '" data-dzid="' + data[i].id + '"><img  data-dzid="' + data[i].id + '" src="../../css/img/gooded.png"/>&nbsp;<span>' + data[i].dianzan + '</span>&nbsp;</div>';
                                }
                                shopStr += '    <div style="clear:both;float:none;"></div>';
                                shopStr += '  </div>   ';
                                shopStr += '</div>';
                                $("#content").append(shopStr);
                                shopStr = "";
                                $(".imgclass").each(function () {
                                    $(this).height($(this).width());
                                });
                                if (i == 19)
                                    $('#jiazaigengduo').show();
                            }

//                            $('.zan').unbind('click');
                            $('.dianzan').click(function () {
                                $this = $(this);
                                $.ajax({
                                    type: "POST",
                                    url: "lfx_dianzhan.php",
                                    data: {"id": $(this).attr('data-dzid')},
                                    success: function (data) {
                                        if (data == 0) {
                                            alert('您已点赞');
                                        } else if (data == 1) {
                                            $this.find("span").html(Number($this.attr('data-dzcs')) + 1);
                                            $this.find("img").attr("src", "../../css/img/gooded.png");
                                        } else if (data == 2)
                                            alert('点赞失败，请稍后重试');
                                    }
                                });
                            });
//                            $('.sc').click(function () {
//                                var id = $(this).attr('data-dzid');
//                                $.post(
//                                        'lfx_sc.php',
//                                        {id: id},
//                                        function (data) {
//                                            if (data == 1)
//                                                alert('收藏成功');
//                                            else if (data == 0)
//                                                alert('已经收藏了');
//                                            else if (data == 2)
//                                                alert('收藏失败,请稍后重试');
//                                        });
//                            });
                        } else {
                            $("#content").append("<div style='font-size:1.5em;color:#888;text-align:center;line-height:3em;'>暂无分享</div>");
                        }
                    }
                });
            }
            jiazai(0, searchTxt);
            $('#jiazaigengduo').click(function () {
                limit_i += 20;
                jiazai(limit_i, searchTxt);
            })
        </script>

        <?php include '../gony/nav.php'; ?>
        <?php include '../gony/guanzhu.php'; ?>
        <?php include '../gony/returntop.php'; ?>
        <div style="clear:both;height:90px;"></div>
    </body>
</html>