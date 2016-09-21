<?php

include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$buynum = addslashes($_POST["buynum"]);
$good_name = addslashes($_POST["good_name"]);
$price = addslashes($_POST["price"]);
$jiezhangren = intval($_POST["jiez"]);
$cart_idtext = addslashes($_POST["cart_idtext"]);

if ($jiezhangren != 2 && $jiezhangren != 1)
    $jiezhangren = 0; //0是自己付款 1推广付款 2是店家付款

$goodsimg = $_POST["goodsimg"];
$buynumArr = explode('☆', $buynum);
$good_nameArr = explode('☆', $good_name);
$priceArr = explode('☆', $price);
$goodsimgArr = explode('☆', $goodsimg);
$cart_idArr = explode('☆', $cart_idtext);
$cart_idsql = "";
if (count($cart_idArr) > 0) {
    for ($i = 0; $i < count($cart_idArr); $i++) {
        if ($cart_idArr[$i] != "")
            $cart_idsql .= $cart_idArr[$i] . ",";
    }
    $cart_idsql = substr($cart_idsql, 0, strlen($cart_idsql) - 1);
}
if ($cart_idsql == "") {
    echo "{\"success\":\"3\",\"orderid\":\"0\"}";
    return;
    die;
}
$sql = "SELECT wei_cart.cart_id FROM wei_cart join sk_goods on wei_cart.goodsid=sk_goods.id where sk_goods.cate_id = 88 and wei_cart.openid='$openid'";
$naifen_youfei = $do->selectsql($sql);
$naifen_true = false;
if (count($naifen_youfei) > 0) {
    $naifen_true = true;
}

//团购,默认为false
$tuangou = false;

$ruturn_i = 1;
$dingdzj = 0; //订单总价
$cart_iddelete = "";

$curtime = date('YmdHis', time());
$sql = "select g.good_name,g.img,w.cart_id,w.goodsid,g.fp_price,w.tax_rate,w.guige,w.buynum,w.danjia,g.goodtype,g.pcode,g.kucun,g.price,g.huodongjia,gs.price tuangoujia,gs.price shangoujia from wei_cart w join sk_goods g on g.id=w.goodsid left join sk_gs gs on gs.gid=w.goodsid where openid='$openid' and g.status=1 and w.cart_id in (" . $cart_idsql . ") order by w.cart_id desc";
$resultArr = $do->selectsql($sql); //查询购物车中的数据
$gotype = 0;
for ($got = 0; $got < count($resultArr); $got++) {
    $gotype = $resultArr[$got]['goodtype'];
}
if ($gotype == 2)
    $gotype = 1;
else
    $gotype = 0;
if (count($resultArr) > 0) {
    $order_id = $do->sqlTableId("sk_orders", "order_id");

//----------------------获取本机IP
    function getIPaddress() {
        $IPaddress = '';
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $IPaddress = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $IPaddress = getenv("HTTP_CLIENT_IP");
            } else {
                $IPaddress = getenv("REMOTE_ADDR");
            }
        }
        return $IPaddress;
    }

//--------------------获取地址
    function getip() {
        $ip = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . getIPaddress());
        $ip = json_decode($ip, true);
        return $ip['data']['region'] . $ip['data']['city'];
    }

//$do->dealsql("insert into sk_orders (order_id,order_sn,buyer_id,status,add_time,jiezhangren,ipdz) values ('$order_id','$curtime','$openid','0','".time()."','".$jiezhangren."','".getip()."')");//插入订单表
    //print_r($resultArr);exit;
    for ($i = 0; $i < count($resultArr); $i++) {
        if ($resultArr[$i]["kucun"] >= $buynumArr[$i]) {//判断商品库存是否充足
            $jiage = 0;
            if ($resultArr[$i]["danjia"] == 0)
                $jiage = $resultArr[$i]["price"];
            else if ($resultArr[$i]["danjia"] == 1)//活动价
                $jiage = $resultArr[$i]["huodongjia"];
            else if ($resultArr[$i]["danjia"] == 2)//团购价
                $jiage = $resultArr[$i]["tuangoujia"];
            else if ($resultArr[$i]["danjia"] == 3)//闪购价
                $jiage = $resultArr[$i]["shangoujia"];

            if ($resultArr[$i]["danjia"] == 1) {//活动产品
                $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '" . date('Y-m-d H:i:s', time()) . "' and hd_jieshu > '" . date('Y-m-d H:i:s', time()) . "' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 1 and canyuid=(select cate_id from sk_goods where id=" . $resultArr[$i]["goodsid"] . "))";
                $LArr = $do->selectsql($sql);
                $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '" . date('Y-m-d H:i:s', time()) . "' and hd_jieshu > '" . date('Y-m-d H:i:s', time()) . "' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 2 and canyuid=(select mp_id from sk_goods where id=" . $resultArr[$i]["goodsid"] . "))";
                $MArr = $do->selectsql($sql);
                $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '" . date('Y-m-d H:i:s', time()) . "' and hd_jieshu > '" . date('Y-m-d H:i:s', time()) . "' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 3 and canyuid=(select gx_id from sk_goods where id=" . $resultArr[$i]["goodsid"] . "))";
                $GArr = $do->selectsql($sql);

                if ($LArr[0]['cou'] > 0 || $MArr[0]['cou'] > 0 || $GArr[0]['cou'] > 0) {//商品还在有效期内 可购买
                    /* $jiage = 0;
                      if($resultArr[$i]["danjia"]==0)
                      $jiage = $resultArr[$i]["price"];
                      else if($resultArr[$i]["danjia"]==1)//活动价
                      $jiage = $resultArr[$i]["huodongjia"];
                      else if($resultArr[$i]["danjia"]==2)//团购价
                      $jiage = $resultArr[$i]["tuangoujia"];
                      else if($resultArr[$i]["danjia"]==3)//闪购价
                      $jiage = $resultArr[$i]["shangoujia"]; */
                    $html_cardid = "";
                    $html_i = 0;
                    while ($resultArr[$i]["cart_id"] != $html_cardid) {
                        $html_cardid = $cart_idArr[$html_i];
                        $sl = $buynumArr[$html_i];
                        $html_i++;
                    }
                    $sql = "insert into sk_order_goods (order_id,goods_id,goods_name,specification,price,tax_rate,quantity,goods_images,goods_code,goods_expired) values ('$order_id','" . $resultArr[$i]["goodsid"] . "',\"" . $do->gohtml($resultArr[$i]["good_name"]) . "\",'" . $resultArr[$i]["guige"] . "','" . $jiage . "','" . $resultArr[$i]["tax_rate"] . "','" . $sl . "','" . $resultArr[$i]["img"] . "','" . $resultArr[$i]['pcode'] . "'," . $resultArr[$i]["danjia"] . ")"; //插入订单商品表
                    $do->dealsql($sql);
                    /* $sql = "update sk_goods set kucun = kucun - ".$buynumArr[$i]." where id='".$resultArr[$i]["goodsid"]."'";//商品库存减去
                      $do->dealsql($sql); */
                    $sql = "update sk_goods set xiaoliang = xiaoliang + " . $buynumArr[$i] . " where id='" . $resultArr[$i]["goodsid"] . "'"; //商品销量增加
                    $do->dealsql($sql);
                    $dingdzj += ($jiage * $buynumArr[$i]); //加入订单总价
                    $cart_iddelete .= $resultArr[$i]["cart_id"] . ",";
                }
            } elseif ($resultArr[$i]["danjia"] == 2) {
                $tuangou = TRUE;
                $sql = "select count(*) count from sk_gs where gid='" . $resultArr[$i]['goodsid'] . "' and start_time<" . time() . " and end_time>" . time() . " and status=1";
                $gs = $do->getone($sql);
                if ($gs['count'] > 0) {
                    $html_cardid = "";
                    $html_i = 0;
                    while ($resultArr[$i]["cart_id"] != $html_cardid) {
                        $html_cardid = $cart_idArr[$html_i];
                        $sl = $buynumArr[$html_i];
                        $html_i++;
                    }
                    $sql = "insert into sk_order_goods (order_id,goods_id,goods_name,specification,price,tax_rate,quantity,goods_images,goods_code,goods_expired) values ('$order_id','" . $resultArr[$i]["goodsid"] . "',\"" . $do->gohtml($resultArr[$i]["good_name"]) . "\",'" . $resultArr[$i]["guige"] . "','" . $jiage . "','" . $resultArr[$i]["tax_rate"] . "','1','" . $resultArr[$i]["img"] . "','" . $resultArr[$i]['pcode'] . "'," . $resultArr[$i]["danjia"] . ")"; //插入订单商品表
                    $do->dealsql($sql);
                    /* $sql = "update sk_goods set kucun = kucun - ".$buynumArr[$i]." where id='".$resultArr[$i]["goodsid"]."'";//商品库存减去
                      $do->dealsql($sql); */
                    $sql = "update sk_goods set xiaoliang = xiaoliang + " . $buynumArr[$i] . " where id='" . $resultArr[$i]["goodsid"] . "'"; //商品销量增加
                    $do->dealsql($sql);
                    $dingdzj += ($jiage * $buynumArr[$i]); //加入订单总价
                    $cart_iddelete .= $resultArr[$i]["cart_id"] . ",";
                }
            } else {//普通商品
                $html_cardid = "";
                $html_i = 0;
                while ($resultArr[$i]["cart_id"] != $html_cardid) {
                    $html_cardid = $cart_idArr[$html_i];
                    $sl = $buynumArr[$html_i];
                    $html_i++;
                }
                $sql = "insert into sk_order_goods (order_id,goods_id,goods_name,specification,price,tax_rate,quantity,goods_images,goods_code) values ('$order_id','" . $resultArr[$i]["goodsid"] . "',\"" . $do->gohtml($resultArr[$i]["good_name"]) . "\",'" . $resultArr[$i]["guige"] . "','" . $jiage . "','" . $resultArr[$i]["tax_rate"] . "','" . $sl . "','" . $resultArr[$i]["img"] . "','" . $resultArr[$i]['pcode'] . "')"; //插入订单商品表
                $do->dealsql($sql);
                /* $sql = "update sk_goods set kucun = kucun - ".$buynumArr[$i]." where id='".$resultArr[$i]["goodsid"]."'";//商品库存减去
                  $do->dealsql($sql); */
                $sql = "update sk_goods set xiaoliang = xiaoliang + " . $buynumArr[$i] . " where id='" . $resultArr[$i]["goodsid"] . "'"; //商品销量增加
                $do->dealsql($sql);
                $dingdzj += ($jiage * $buynumArr[$i]); //加入订单总价 
                //是否店铺会员
                $dp_user = $do->selectsql("select jointag from sk_member where open_id ='$openid'");
                $jointag = $dp_user[0]['jointag'];
                //获取店铺会员售价比例
                if ($jointag == '2') {
                    $dp_price = $do->selectsql("select dp_price from sk_dp_price where id = 1");
                    $dp_price = $dp_price[0]['dp_price'];
                    //店铺会员价格为：商品价格-利润分出*店铺会员售价比例
                    $fp_price = $resultArr[$i]["fp_price"] * $dp_price;
                    $dingdzj-=$fp_price;
                }
                $tishi_youfei += $buynumArr[$i];
                $cart_iddelete .= $resultArr[$i]["cart_id"] . ",";
            }
        } else
            $ruturn_i = 2; //库存不足
    }

    if (count($resultArr) > 0) {//购物车数据
        if ($do->dealsql("insert into sk_orders (order_id,order_sn,buyer_id,status,order_time,add_time,jiezhangren,ipdz,erm,goods_amount,order_amount) values ('$order_id','$curtime','$openid','0','" . date('Y-m-d H:i:s', time()) . "'," . time() . ",'" . $jiezhangren . "','" . getip() . "'," . $gotype . "," . $dingdzj . "," . $dingdzj . ")")) {//插入订单表
            if ($dingdzj >= 299 || $tishi_youfei >= 2 || $naifen_true == true || $tuangou==TRUE) {
                $youfei = "0.00";
            } else {
                $youfei = "10.00";
            }
            $name = $do->selectsql("select wei_nickname from sk_member where open_id = '$openid'");
            $weixintxt = "亲爱的 " . addslashes($name[0]['wei_nickname']) . "，恭喜您于 " . date('Y-m-d H:i:s', time()) . " 在洋仆淘下单成功，订单总价 " . $dingdzj . " 元，邮费 " . $youfei . " 元。为了您能尽快的收到商品，请您尽快支付订单。";
            $do->weixintishi($openid, $weixintxt);
            $do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','" . time() . "','" . $openid . "',2)");
            //$do -> weixintishi($openid,"恭喜 ".date('Y-m-d H:i:s',time())." 在和众世纪下单成功，订单总价 ".$dingdzj." 元，邮费 ".$youfei." 元");
        }
    }
    $do->dealsql("delete from wei_cart where openid='$openid' and cart_id in(" . substr($cart_iddelete, 0, strlen($cart_iddelete) - 1) . ")"); //删除购物车中该订单的数据
    $_SESSION["cartnum"] = "0";




    //——————————————————————————————————————————————————————————————————————————————————————————————————————
    //二维码生成
    //include '../../base/commonFun.php';
    include '../../base/phpqrcode/phpqrcode.php';
    //$com = new commonFun();

    $errorCorrectionLevel = 'L'; // 容错级别
    $matrixPointSize = 6; // 生成图片大小
    //订单号 $out_trade_no
    //购买人id 在订单号里 buyer_id
    //得出该订单里有多少个商品跟该订单的购买人	
    //$dingdanhao = $curtime;
    $i_i_sql = "select o.buyer_id as kehu,g.goods_id as shangpin,go.goodtype as type, g.quantity as quantity from sk_orders o join sk_order_goods g on o.order_id = g.order_id join sk_goods go on g.goods_id = go.id where o.order_id = '" . $order_id . "'";
    $i_i_resuArr = $do->selectsql($i_i_sql);
    for ($k = 0; $k < count($i_i_resuArr); $k++) {//每种商品
        if ($i_i_resuArr[$k]['type'] == 2) {//判断该产品是店家商品(LED)才生成二维码
            for ($qui = 0; $qui < $i_i_resuArr[$k]['quantity']; $qui++) {//每个商品生成二维码
                $i_shu = mt_rand(100, 999); //___唯一编号↓
                $i_shu2 = time();
                $i_sql = "select * from sk_erweima where bianhao='" . $i_shu2 . $i_shu . "'"; //检查该编号是否存在
                $i_resuArr = $do->selectsql($i_sql);
                if (count($i_resuArr) > 0) {
                    $i_congfu = $i_shu;
                    while ($i_congfu == $i_shu) {
                        $i_shu = mt_rand(100, 999);
                    }
                }//___唯一编号↑ $i_shu2.$i_shu
                //session_start();
                $access_token = $_SESSION["access_token"];
                $headimgurl = ""; //$_SESSION["headimgurl"];//二维码中间的小图标(带图片报错 )
                $link = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=' . $i_i_resuArr[0]['kehu'] . '|2|' . $i_i_resuArr[0]['shangpin'] . '&connect_redirect=1#wechat_redirect';
                //$link = 'https://m.hz41319.com/wei/login.php?state='.$i_i_resuArr[0]['kehu'].'&erweima_shangpin='.$i_i_resuArr[0]['shangpin'].'&typeo=2';
                $QR = '../../images/erweima/' . $i_shu2 . $i_shu . '.png'; //存放二维码地址
                QRcode::png($link, $QR, $errorCorrectionLevel, $matrixPointSize, 2);

                $logo = $headimgurl;
                if ($logo !== FALSE) {
                    $QR = imagecreatefromstring(file_get_contents($QR));
                    $logo = imagecreatefromstring(file_get_contents($logo));
                    $QR_width = imagesx($QR); // 二维码图片宽度
                    $QR_height = imagesy($QR); // 二维码图片高度
                    $logo_width = imagesx($logo); // logo图片宽度
                    $logo_height = imagesy($logo); // logo图片高度
                    $logo_qr_width = $QR_width / 5;
                    $scale = $logo_width / $logo_qr_width;
                    $logo_qr_height = $logo_height / $scale;
                    $from_width = ($QR_width - $logo_qr_width) / 2;
                    // 重新组合图片并调整大小
                    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
                }
                imagepng($QR, '../../images/erweima/' . $i_shu2 . $i_shu . '.png'); //存放二维码地址
                //每个商品对应的二维码插入数据库
                $do->selectsql("insert into sk_erweima (bianhao,dingdan,openid,shangpin,dizhi) values ('" . $i_shu2 . $i_shu . "','" . $order_id . "','" . $i_i_resuArr[0]['kehu'] . "'," . $i_i_resuArr[$k]['shangpin'] . ",'wei/images/erweima/" . $i_shu2 . $i_shu . ".png')");
                //$com->sendImg($access_token ,$openid, $curtime . '.png');//貌似是信息推送，估计是把二维码或者链接推送到该微信号什么的
            }
        }
    }
    //——————————————————————————————————————————————————————————————————————————————————————————————————————
    echo "{\"success\":\"" . $ruturn_i . "\",\"orderid\":\"$order_id\"}";
}
?>