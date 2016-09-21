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
$orderid = intval($_GET["orderid"]);

$subscribe = $_SESSION["subscribe"];
$openid = $_SESSION["openid"];
$wei_shouname = $_SESSION["wei_shouname"];
$phone = $_SESSION["phone"];
$address = $_SESSION["address"];
//$out_trade_no = substr($openid, 0, 5).time();

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
/*
 *  在执行此操作之前 首先判断您是否开通了 微信支付功能 审核通过后均可使用一下代码
 *  1、设置微信公众平台网页授权 域名 www.abc.com
 *  2、设置下面的 “ 微信参数 ”
 *  3、把 当前文件 index.php 放入根目录
 *  4、用微信访问http://www.abc.com/index.php 就可以了  切记一定是微信哦
 * */

//微信参数
$appId = 'wx8b17740e4ea78bf5';
$appSecret = 'bbd06a32bdefc1a00536760eddd1721d';

// //获取get参数
// $code = $_GET['code'];
// //获取 code
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$imgurl = 'http://' . $_SERVER['HTTP_HOST'] . "/";
// $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appId&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=jsapi_address&state=cft#wechat_redirect";
// if(empty($code)){
// 	header("location: $url");
// }
// //获取 access_token
// $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appId."&secret=".$appSecret."&code=".$code."&grant_type=authorization_code";
// $access_token_json = getUrl($access_token_url);
// $access_token = json_decode($access_token_json,true);
// 定义参数
$timestamp = time();
$nonceStr = rand(100000, 999999);
$Parameters = array();
//===============下面数组 生成SING 使用=====================
$Parameters['appid'] = $appId;
$Parameters['url'] = $redirect_uri;
$Parameters['timestamp'] = "$timestamp";
$Parameters['noncestr'] = "$nonceStr";
$Parameters['accesstoken'] = $_SESSION["shenaccess_token"];
// 生成 SING
$addrSign = genSha1Sign($Parameters);

function getUrl($url) {
    $opts = array(
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    );
    /* 根据请求类型设置特定参数 */
    $opts[CURLOPT_URL] = $url;
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    return $data;
}

function p($star) {
    echo '<pre>';
    print_r($star);
    echo '</pre>';
}

function logtext($content) {
    $fp = fopen("json.ini", "a");
    fwrite($fp, "\r\n" . $content);
    fclose($fp);
}

//创建签名SHA1
function genSha1Sign($Parameters) {
    $signPars = '';
    ksort($Parameters);
    foreach ($Parameters as $k => $v) {
        if ("" != $v && "sign" != $k) {
            if ($signPars == '')
                $signPars .= $k . "=" . $v;
            else
                $signPars .= "&" . $k . "=" . $v;
        }
    }
    //$signPars = http_build_query($Parameters);
    $sign = SHA1($signPars);
    $Parameters['sign'] = $sign;
    return $sign;
}

/////////$orderid
$jointag = $do->getone("select jointag from sk_member where open_id='$openid'");
$arr = $do->getone("select post_code from sk_order_extm where order_id='$orderid'");
$post_code = $arr['post_code'];
$sql = "SELECT sk_order_goods.rec_id FROM sk_order_goods join sk_goods on sk_order_goods.goods_id=sk_goods.id WHERE sk_order_goods.order_id = '$orderid' and sk_goods.cate_id = 88";
$coulist = $do->getone("select mcouponid,couponid from sk_orders where order_id=$orderid");
$naifen_youfei = $do->selectsql($sql);
$time = time();
$coupon = $do->getone("select xz,js from sk_coupon where id=" . $coulist['couponid'] . " and rec='$openid' and status in (1,3) and start_time<=$time and end_time>=$time");
$mcoupon = $do->getone("select money from sk_mcoupon where id=" . $coulist['mcouponid'] . " and open_id='$openid' and status=1 and start_time<=$time and end_time>=$time");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>结算</title>
        <meta name="viewport" content="width=100%; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
        <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/new_css.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/css.css" />
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
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

            var wei_shouname = "<?php echo $wei_shouname ?>";
            var jifen = 0;
            var orid = 0;
            var huiyuan = 0;
            $(function () {
                if (wei_shouname != "") {
                    $("#addDiv1").css("display", "block");
                    $("#addDiv2").css("display", "none");
                }
                $.ajax({
                    type: "POST",
                    url: "orderSearch.php",
                    data: "orderid=<?php echo $orderid ?>",
                    success: function (data) {
                        data = eval("(" + data + ")");
                        //console.log(data);
                        if (data.success == 0) {
                            alert('该闪购商品支付时间已过！下次要快点哦');
                            location.replace("./myorder.php?type=0");
                            return;
                        } else if (data.success == 1) {
                            location.replace("./dpgou_zl.php?orderid=<?php echo $orderid ?>");
                        }
                        var total = 0;

                        var zquantity = 0;
                        for (var i = 0; i < data.length; i++) {
                            //alert(data[i].status);
                            if (data[i].status == 0) {
                                alert('该订单商品已下架，请重新下单!');
                                location.replace("./myorder.php?type=0");
                                return;
                            }
                            var quantity = data[i].quantity;
                            var price = data[i].price;
                            jifen = data[i].jifendh;
                            var appStr = "<li>";
                            appStr += "<img src=\"<?php echo $imgurl ?>" + data[i].img + "\">";
                            if (jifen == 1)
                                appStr = "<div class=\"hg\"><p class='my-p'>" + data[i].good_name + "</p><div class=\"jia clearfix\"><font>积分</font><b>" + price + "</b><font>.00</font><span>" + quantity + "</span></div>";
                            else
                                appStr += "<div class=\"hg\"><p class='my-p'>" + data[i].good_name + "</p><div class=\"jia clearfix\"><font>¥&nbsp;</font><b>" + price + "</b><font>.00</font><span>" + quantity + "</span></div>";
                            appStr += "</li>";

                            $("#order").append(appStr);
                            total += parseInt(quantity) * parseFloat(price);
                            huiyuan = data[i].goodtype;
                            zquantity += quantity;
                        }
                        if (jifen == 1) {
                            $("#totalPrice").text("积分" + toDecimal2(total));
                            $('#hanyunfei').html('');
                            $('#zfdh').html('积分兑换');
                        } else {
                            if (huiyuan == 1) {
                                $('#addDiv').hide();
                            }
                            if (huiyuan == 1 || huiyuan == 2) {
                                $('.coupon').hide();
                            }
                            var naifen_youfei =<?php echo count($naifen_youfei); ?>;
                            if (zquantity >= 2 || total >= 299 || huiyuan == 1 || huiyuan == 2 || naifen_youfei > 0 ||<?php echo empty($mcoupon) ? 0 : 1; ?> > 0) {
                                $('#hanyunfei').html(' ');
                            } else {
                                total += 10;
                            }
                            if (total >=<?php echo $coupon['xz'] > 0 ? $coupon['xz'] : 0; ?>) {
                                total -=<?php echo $coupon['js'] > 0 ? $coupon['js'] : 0; ?>;
                            }
                            if ("<?php echo empty($mcoupon) ? 0 : 1; ?>" > 0) {
                                total -= "<?php echo $mcoupon['money']; ?>";
                                if (total <= 0)
                                    total = 0.1;
                            }
                            $("#totalPrice").text("￥" + toDecimal2(total));
                        }
                    }
                });
                $("#addDiv1").click(function () {
                    getAddress();
                });
                $("#addDiv2").click(function () {
                    getAddress();
                });
                $('.coupon').click(function () {
                    location.href = "coupon.php?orderid=<?php echo $orderid; ?>";
                });
                $('.mcoupon').click(function () {
                    location.href = "mcoupon.php?orderid=<?php echo $orderid; ?>";
                });
            });
            function getAddress() {
                WeixinJSBridge.invoke('editAddress', {
                    "appId": "<?php echo $appId; ?>", //公众号名称，由商户传入
                    "timeStamp": "<?php echo (string) $timestamp; ?>", //时间戳 这里随意使用了一个值
                    "nonceStr": "<?php echo $nonceStr; ?>", //随机串
                    "signType": "SHA1", //微信签名方式:sha1
                    "addrSign": "<?php echo $addrSign; ?>", //微信签名
                    "scope": "jsapi_address"
                }, function (res) {
                    if (res.err_msg == 'edit_address:ok') {
                        $("#addDiv1").css("display", "block");
                        $("#addDiv2").css("display", "none");
                        $("#shouname").text(res.userName);
                        $("#phone").text(res.telNumber);
                        $("#post_code").text(res.addressPostalCode);
                        $("#address").text(res.proviceFirstStageName + res.addressCitySecondStageName + res.addressCountiesThirdStageName + res.addressDetailInfo);
                    } else {
                        alert("获取地址失败，请重新点击");
                    }
                });
            }
            function pay() {
                var shouname = $("#shouname").val();
                var phone = $("#phone").text();
                var address = $("#address").text();
                var post_code = $("#post_code").text();
                var postscript = $("#postscript").val();
                var shenfenz = $("#shenfenzhneg").val();
                if (huiyuan != 1 && shouname == "") {
                    msgError("请填写收货地址");
                    return;
                }
                if (shenfenz == "") {
                    alert("请填写身份证号");
                    return;
                }
                if (jifen == 1) {
                    $.ajax({
                        type: "POST",
                        url: "jifenjsget.php",
                        data: "orderid=<?php echo $orderid ?>&shouname=" + shouname + "&phone=" + phone + "&address=" + address + "&post_code=" + post_code + "&postscript=" + postscript,
                        success: function (data) {
                            data = eval("(" + data + ")");
                            if (data.success == '1') {
                                alert("兑换成功")
                                window.location.href = 'shenfenz.php?order=' + data.order_id;
                            } else {
                                msgError("您的积分尚未够兑换该商品");
                            }
                        }
                    });
                } else {
                    $.ajax({
                        type: "POST",
                        url: "orderPay.php",
                        data: "orderid=<?php echo $orderid ?>&shouname=" + shouname + "&phone=" + phone + "&address=" + address + "&post_code=" + post_code + "&postscript=" + postscript,
                        success: function (data) {
                            data = eval("(" + data + ")");
                            if (data.success == "1") {
                                location.replace("../weipay/demo/js_api_call.php?order=" + data.order_id);
                            } else {
                                msgError("购买失败，请稍后重试");
                            }
                        }
                    });
                }
            }
            function toDecimal2(x) {
                var f = parseFloat(x);
                if (isNaN(f)) {
                    return false;
                }
                var f = Math.round(x * 100) / 100;
                var s = f.toString();
                var rs = s.indexOf('.');
                if (rs < 0) {
                    rs = s.length;
                    s += '.';
                }
                while (s.length <= rs + 2) {
                    s += '0';
                }
                return s;
            }
        </script>
    </head>
    <body>
        <div class="bigbox clearfix">
            <ul class="u1 clearfix" id="order">
<!--                <li><img src="img/luhui.jpg">
                    <div class="hg">
                        <p>韩国Nature&nbsp;Republic自然乐园&nbsp;芦荟胶<br/>
                            200ml</p>
                        <div class="jia clearfix">
                            <font>¥&nbsp;</font>
                            <b>88</b>
                            <font>.00</font>
                            <span>2</span>
                        </div>
                </li>-->

            </ul>
            <ul class="u2 clearfix" >
                <li class="li-first">
                    <a id="addDiv2" >
                        请选择收货地址
                    </a>
                </li>
                <li>
                    <a id="addDiv1" style="display:none;" >
                        <p style="line-height: 2.8em;">收货人：<font id="shouname"><?php echo $wei_shouname ?></font></span>&nbsp;&nbsp;&nbsp;<span id="phone"><?php echo $phone ?></p>
                        <p style="line-height: 2.8em;">收货地址：<font id="address"><?php echo $address ?></font></p>
                        <p style="line-height: 2.8em;">邮编：<font id="post_code"><?php echo $post_code ?></font></p>
                    </a>
                </li>
                <li><span>姓名：<input type="text" class="text-input" value="<?php echo $wei_shouname ?>"/></span></li>
                <li><span>身份证：<input type="text" name="shenfenzhneg" class="text-input" placeholder="请填写身份证号码"/></span></li>
                <li><span>境外商品清关需购买人的<b>身份证号码</b>,如有疑问，请联<br/>系客服：<font>400-888-3658</font></span></li>
            </ul>
            <div class="mj"><p>买家留言：<input type="text" name="postscript" id="postscript" class="text-input" placeholder="给卖家留言"/></p></div>
            <div class="dk">
                <a><p class="coupon">优惠券</p>
                    <p class="mcoupon">
                        <?php if($jointag['jointag']==2) { ?>
                            选择现金券
                        <?php } ?>
                    </p>
                </a>
<!--                <a>
                    <p>收益抵扣</p>
                </a>-->
            </div>
            <div class="you clearfix"><p><font id="totalPrice">00.00</font><b id="hanyunfei">邮费10元&nbsp;合计：</b></p></div>
            <div class="zhifu" ><span onclick="pay()">微信支付</span></div>
        </div>	
    </body>
</html>