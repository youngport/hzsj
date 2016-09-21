<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}

$openid = $_SESSION["openid"];


$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);

$d=intval($_GET['d']);
$sql = "select * from sk_huodong where huodong_id =$d";
$arrfen = $do -> selectsql($sql);

$sql = "select * from sk_huodong where huodong_id =$d";
$arrfen = $do -> getone($sql);
if(empty($arrfen))
    echo "<script>alert('没有这个活动!');history.back(-1);</script>";
$sql="select id,good_name,huodongjia,orgprice,zhekou,img,hdtjtext from sk_goods where (mp_id in(select canyuid from sk_huodong_bang where canyulei = 2 and huodongid = '$d') or gx_id in(select canyuid from sk_huodong_bang where canyulei = 3 and huodongid = '$d') or cate_id in(select canyuid from sk_huodong_bang where canyulei = 1 and huodongid = '$d')) and canyuhd = 1 and goodtype=0 and status=1 and sort_order>5 order by hdtuijian desc,sort_order desc";
$list=$do->selectsql($sql);
$top=$list[0];
unset($list[0]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="http://libs.baidu.com/jquery/1.11.3/jquery.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <title>今日推荐</title>
    <style>
        div{background:#fff}
        .top-img {width:40%;float:left;}
        .top-img img{width:100%;}
        .top-info{width:60%;float:left;padding:0.8em;}
        .top-info h4{margin:0.9em 0 0 0;font-size:1.5rem;}
        .top-info li{font-size:1.3rem;}
        .zhekou{background:url(../../img/dz.jpg) center no-repeat;background-size:100%;padding:1em;padding-top:1.2em;color:#fff}
        .gview{width:50%;float:left;padding:0.7em;border-bottom:1px solid #ededed;border-right:1px solid #ededed;}
        .gview a{color:#000;text-decoration: none;}
        .gview img{width:100%;}
        .gview h4{margin:0.6em 0;font-size:1rem;}
        .gview h5{margin:0;color:#FA6464}
        .gview span{color:#909090}
    </style>
    <script type="text/javascript">
        wx.config({
            appId: '<?php echo $shareCheckArr["appid"];?>',
            timestamp: '<?php echo $shareCheckArr["timestamp"];?>',
            nonceStr: '<?php echo $shareCheckArr["noncestr"];?>',
            signature: '<?php echo $shareCheckArr["signature"];?>',
            jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','hideMenuItems'] // 功能列表，我们要使用JS-SDK的什么功能
        });
        wx.ready(function(){
            var shareData = {
                title: '<?php echo $arrfen['hd_name'];?>',
                desc: '<?php echo $arrfen['hd_jianjie']?>', // 分享描述
                link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>|jinrihuodong|<?php echo $d;?>&connect_redirect=1#wechat_redirect',
                imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'].'/'.$arrfen['hd_img']; ?>',
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
</head>
<body>
<div class="container-fluid text-center" style="padding:0;background:#F3F3F3">
    <div style="margin-bottom:0.6em">
        <div class="top-img"><a href="../goods/goods.php?id=<?echo $top['id'];?>&ms=1"><img src="<?echo $cxtPath.'/'.$top['img'];?>"/></a></div>
        <div class="top-info">
            <h4 class="text-left"><?echo $top['good_name'];?></h4>
            <ul class="list-inline text-left">
                <li class="zhekou"><?echo $top['zhekou'];?>折</li>
                <li style="color:#FA6464;padding:0;font-size:1.8rem">￥<?echo mb_substr($top['huodongjia'], 0, mb_strlen($top['huodongjia']) - 3);?></li>
                <li style="color:#909090;padding:0 0.4em;">市场价:<?echo  mb_substr($top['orgprice'], 0, mb_strlen($top['orgprice']) - 3); ?></li>
            </ul>
            <div class="clearfix"></div>
            <div>
                <p style="background:#F3F3F3;height:100%;"><?echo $top['hdtjtext'] ;?></p>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="goods">
        <? foreach($list as $v){?>
            <div class="gview">
                <a href="../goods/goods.php?id=<?echo $v['id'];?>&ms=1">
                    <img src="<? echo  $cxtPath.'/'.$v['img'];?>"/>
                    <h4><? echo $v['good_name'];?></h4>
                    <h5>￥<? echo mb_substr($v['huodongjia'], 0, mb_strlen($v['huodongjia']) - 3) ; ?>&nbsp;&nbsp;&nbsp;<span>市场价:<? echo mb_substr($v['orgprice'], 0, mb_strlen($v['orgprice']) - 3);?></span></h5>
                </a>
            </div>
        <?}?>
    </div>
</div>
</body>
<script>
    if($(document).width()>470){
        $(".top-img img").css("width","45%");
        $(".top-info h4").css("font-size",$(document).width()*0.02);
        $(".top-info li").css({"font-size":$(document).width()*0.02,"padding":"1em"});
        $(".top-info p").css({"font-size":$(document).width()*0.02});
    }
</script>
</html>