<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://" . $_SERVER['HTTP_HOST'];

session_start();
if (!isset($_SESSION["access_token"])) {
    header("Location:" . $cxtPath . "/wei/login.php");
    return;
}

$openid = $_SESSION["openid"];
$do = new condbwei();
$com = new commonFun();

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$imgurl = 'http://' . $_SERVER['HTTP_HOST'] . "/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$id = intval($_GET['id']);
$sql = "select lfx.biaoti,lfx.neirong,lfx.shijian,lfx.dianji,me.wei_nickname,me.headimgurl,(select count(*) from sk_lfx_dz where lfxid=lfx.id) dianzan,(select count(*) from sk_lfx_pinglun where lfx_id=lfx.id ) pinlun from sk_lfx lfx join sk_member me on me.open_id=lfx.openid where lfx.id='$id'";
$data = $do->getone($sql);
$imglist = $do->selectsql("select imgurl from sk_lfx_img where lfxid=$id limit 3");

$cookie_up = $_COOKIE["fenxiang"];
$arr = explode(",", $cookie_up);
$sql_in = "";
for ($i = 0; $i < count($arr); $i++) {
    $sql_in .= $arr[$i] . ",";
}
$sql_in = substr($sql_in, 0, strlen($sql_in) - 1);
if ($sql_in != "") {
    $sql = "select id,img,good_name,price from sk_goods where id in(" . $sql_in . ")";
    $cookieArr = $do->selectsql($sql);
}
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
            <style>
                *{margin: 0 0; padding: 0 0; font-family: 微软雅黑;}
                .dazonghe{ width:96%;margin:0 auto;padding: 0 10px; }
                .dangetop,.youwen,.totalPL,.dazonghe{
                    background-color: white;
                    font-size: 1.3em;
                }
                .shijian{ font-size:1.3em;color:#888;}
                .shijian span{margin-left:3em;}
                .imgdatu{ width:100%;margin:0 0;padding: 0 0;}
                .xinde{ font-size:1.3em;color:#333;line-height:1.5em;margin: 18px 0;}
                .xinxi{ font-size:1.3em;color:#888;line-height:2em;}
                .xinxi span{color:#BA2636}
                .zanrenshu{ height:3em;border-bottom:1px solid #c2c1be;}
                .zanrenshu img{
                    width:2em;height:2em; margin:0.5em 0.3em;
                    border-radius:50%;
                    -moz-border-radius:50%;
                    -webkit-border-radius:50%;
                    -o-border-radius:50%;
                    -ms-border-radius:50%;}
                .gerenimg{ width:15%; margin:0.5em 2% 0.5em 1%; float:left;
                           border-radius:50%;
                           -moz-border-radius:50%;
                           -webkit-border-radius:50%;
                           -o-border-radius:50%;
                           -ms-border-radius:50%;}
                .pinglun{color:#333;padding-top: 10px;}
                .touxiang{
                    width:60px; height:60px; margin-left:0.5em; margin-right:0.5em;
                    border-radius:50%;
                    -moz-border-radius:50%;
                    -webkit-border-radius:50%;
                    -o-border-radius:50%;
                    -ms-border-radius:50%;
                    float: left;
                    margin-right: 10px;
                }
                .jiazai{ font-size:1.3em;line-height:2.5em;border-bottom:1px solid #c2c1be;text-align:center;color:#888;}
                .txt{ font-size:1.4em;line-height:1.3em;color:#a7a6a2;background:#e0dfde;width:100%;height:4em;border:none;}
                .kuang{width:90%;margin:0.2em auto;background:#e0dfde;
                       border-radius:4px;
                       -moz-border-radius:4px;
                       -webkit-border-radius:4px;
                       -o-border-radius:4px;
                       -ms-border-radius:4px;}
                .fabiao{ width:7em;height:2.5em;font-size:1.3em;color:#fff;text-align:center;background:#fe7182;line-height:2.5em;margin:0 auto;
                         border-radius:4px;
                         -moz-border-radius:4px;
                         -webkit-border-radius:4px;
                         -o-border-radius:4px;
                         -ms-border-radius:4px;}
                .biaoti{
                    height:1.8em;background:#fff;line-height:1.8em;margin-top:0.7em;padding-bottom:0.2em;
                }
                .biaoti img{ width:1.8em; height:1.8em; margin-left:0.5em; margin-right:0.5em;
                             border-radius:50%;
                             -moz-border-radius:50%;
                             -webkit-border-radius:50%;
                             -o-border-radius:50%;
                             -ms-border-radius:50%;}
                .biaoti span {
                    font-size: 1.3em;
                }
                .good{
                    width: 100%;
                    height: 135px;
                    border: 1px solid red;
                    margin-top: 10px;
                    font-size: 1.2em;
                    margin: auto;  
                }
                .txt-price{
                    margin-top: 10px;
                    font-size: x-large;
                }
                .txt-goodname{
                    margin-top: 40px;
                }
                .good img{
                    float: left;
                    margin-right: 10px;
                }
                .shuxing{background:#fff; }
                .shuxing div{ float:left;line-height:2.4em; color:#666;}
                .totalPL{
                    width: 100%;
                    border-top: 18px solid #EEEEEE;
                    margin: 10px 0 0 0;
                }
                .footer{height:50px;position:fixed;bottom:0px;left:0px;background-color: grey;width: 100%;}
                .sendcontant{
                    width: 77%;
                    padding-bottom: 5px;
                    padding-left: 10px;
                    margin-top: 10px;
                    height: 23px;
                    float: left;
                    margin-left: 5px;
                }
                .sendbtn{
                    padding-bottom: 5px;
                    width: 16%;
                    margin-top: 10px;
                    height: 30px;
                    float: right;
                    font-size: 1.8em;
                    color: white;
                    background-color: #fe7182;
                    margin-right: 5px;
                }

                .pl-item{
                    padding: 14px 20px 0 20px;
                }
                .pl-time{
                    font-size: 0.8em;
                    color:gray;
                }

            </style>
    </head>
    <body>
        <div class="dazonghe" id="dazonghe">
            <!--            <div id="top_p"></div>-->
            <div class="biaoti"><img src="<?php echo $data['headimgurl']; ?>" /><span ><?php echo $data['wei_nickname']; ?></span></div>
            <?php
            //var_dump($cookieArr);
            if (!empty($cookieArr[0])) {
                ?>
                <div class="good">
                    <img src="<?php echo $cxtPath . "/" . $cookieArr[0]['img']; ?>" width="135px" height="135px;" />
                    <p class="txt-goodname"><?php echo $cookieArr[0]['good_name'] ?></p>
                    <p class="txt-price">￥ <?php echo $cookieArr[0]['price'] ?> </p>
                </div>
                <div style="clear:both;"></div>
            <?php } ?>
            <div class="xinde"><?php echo htmlspecialchars_decode($data['neirong']); ?></div>
            <?php
            foreach ($imglist as $v) {
                echo "<img class='imgdatu'  src = '" . $cxtPath . "/" . $v['imgurl'] . "' />";
            }
            ?>
            <div class="shuxing">
                <div style="width:60%;"><img src="../../css/img/time.png"/>&nbsp;<?php echo date("Y-m-d H:i:s", $data['shijian']); ?></div>
                <div style="width:18%;" class="dianzan" data-dzcs="<?php echo $data['dianzan']; ?>" data-dzid="<?php echo $data['id']; ?>"><img src="../../css/img/good.png"/>&nbsp;<span><?php echo $data['dianzan']; ?></span></div>
                <div style="width:18%;float: right;" id="sc"><img src="../../css/img/sc.png" alt="收藏"/></div>
                <div style="clear:both;float:none;"></div>
            </div>
        </div>  
        <div class="totalPL">
            <p style="line-height: 2em;font-size:1.3em;border-bottom: 2px solid #888;padding:14px 20px 12px 20px;"><img src="../../css/img/qbpl.png"/>全部评论(<?php echo $data['pinlun']; ?>)</p>
        </div>
        <div class="dangetop">
<!--            <img class="touxiang" src="../img/img.png" />
            <div class="youwen">
                <div class="namesr">
                    <p>末年工资</p> 
                    <p>2015-10-03 23:00:00</p>
                </div>
                <div class="pinglun">等会吃你哦啊生气我就从你上电脑参考继续召开航站楼你空间对比村开学准备穿内裤劳动能力耐心看卷子UN阿迪卡哪款车能看到</div>
                <div style="width:100%;height: 30px;"> 
                    <div style="width:60%;"></div>
                    <div style="width:20%;float: right;"><img src="../../css/img/pl.png"/>20</div>
                    <div style="width:20%;float: right;"><img src="../../css/img/good.png"/>40</div>
                </div>
            </div>
            <hr/>
            <div style="clear:both;"></div>-->
        </div> 
        <div style="clear:both;height:90px;"></div>
        <div class="footer">
            <input name="contant"  class="sendcontant"id="contant"type="text" id="contant" value=""/> <input type="button" id="pinglun" class="sendbtn" value="发&nbsp;&nbsp;送" />
        </div>
    </body>
    <script>
        var limit_t = 0;
        function jiazaipinglun(limit_ti) {
            $('#jiazai').hide();
            $.ajax({
                type: "POST",
                url: "lfx_pinglunget.php",
                data: {"id":<?php echo $id; ?>, "limit_t": limit_ti},
                success: function (data) {
                    var data = eval("(" + data + ")");
                    var shopStr = "";
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            shopStr += '<div class="pl-item">';
                            shopStr += '<img class="touxiang" src="' + data[i].headimgurl + '" />';
                            shopStr += '<div class="youwen">';
                            shopStr += '<div class="namesr">';
//                            shopStr += '<p>' + data[i].wei_nickname == "" ? "匿名用户" : data[i].wei_nickname + '</p>';
                            if (data[i].wei_nickname === '') {
                                shopStr += '<p>匿名用户</p>';
                            } else {
                                shopStr += '<p>' + data[i].wei_nickname + '</p>';
                            }
                            shopStr += '<p class="pl-time">' + data[i].shijian + '</p>';
                            shopStr += '</div>';
                            shopStr += '<div class="pinglun">' + data[i].neirong + '</div>';
                            shopStr += '</div><div style="clear:both;"></div>';
                            shopStr += '</div><hr/>';
                            if (i == 21)
                                $('#jiazai').show();
                        }
                    }
                    $(".dangetop").append(shopStr);
                }
            });
        }
        jiazaipinglun(limit_t);

        //添加评论
        $('#pinglun').click(function () {
            if ($('#contant').val() === "") {
                alert("评论内容不能为空");
            } else {
                $.ajax({
                    type: "POST",
                    url: "lfx_pinluninsert.php",
                    data: {"id":<?php echo $_GET['id']; ?>, "neirong": $('#contant').val().replace(/\r/gi, "<br\/>").replace(/\n/gi, "<br\/>")},
                    success: function (data) {
                        if (data == 1) {
                            alert('评论成功');
                            location.reload();
                        } else
                            alert('评论失败，请稍后重试');
                    }
                });
            }
        });
        //收藏
        $('#sc').click(function () {
            var id = <?php echo $_GET['id']; ?>;
            $.post(
                    'lfx_sc.php',
                    {id: id},
                    function (data) {
                        if (data == 1)
                            alert('收藏成功');
                        else if (data == 0)
                            alert('已经收藏了');
                        else if (data == 2)
                            alert('收藏失败,请稍后重试');
                    });
        });
        //点赞
        $('.dianzan').click(function () {
            $this = $(this);
            $.ajax({
                type: "POST",
                url: "lfx_dianzhan.php",
                data: {"id": $(this).attr('data-dzid')},
                success: function (data) {
                    if (data == 0)
                        alert('您已点赞');
                    else if (data == 1) {
                        $this.find("span").html(Number($this.attr('data-dzcs')) + 1);
                    } else if (data == 2)
                        alert('点赞失败，请稍后重试');
                }
            });
        });
    </script>
</html>