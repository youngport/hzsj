<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$orderid = intval($_POST["orderid"]);
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}
function quotes($content){
	if (is_array($content))	{
		foreach ($content as $key=>$value){
			$content[$key] = addslashes($value);
		}
	}else{
		$content=addslashes($content);
	}
	return htmlspecialchars($content);
}

//----------------------获取本机IP
function getIPaddress()
{
    $IPaddress='';
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $IPaddress = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $IPaddress = getenv("HTTP_CLIENT_IP");
        } else {
            $IPaddress = getenv("REMOTE_ADDR");
        }
    }
    return $IPaddress;
}
function upload($img){
    $hzs=array('jpg','png','jpeg');
    $size=5;
    $hz=ltrim(strrchr($img['name'],'.'),'.');
    if($img['error']!=0){
        echo "{\"success\":\"5\"}";
        exit;
    }
    if(!in_array($hz,$hzs)){
        echo "{\"success\":\"3\"}";
        exit;
    }
    if($img['size']>$size*1024*1024){
        echo "{\"success\":\"4\"}";
        exit;
    }
    $url="data/business/".date('Ymd',time()).mt_rand(44444,99999).'.'.$hz;
    if(file_exists("../../../".$url)){
        $url="data/business/".date('Ymd',time()).mt_rand(44444,99999).'.'.$hz;
    }
    if(move_uploaded_file($img['tmp_name'],"../../../".$url)){
        return $url;
    }
}
//--------------------获取地址
function getip(){
    $ip=file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.getIPaddress());
    $ip=json_decode($ip,true);
    return $ip['data']['region'].$ip['data']['city'];
}
$shifoudingji = $do -> selectsql("select pid from sk_member where open_id ='$openid'");
if($shifoudingji[0]['pid'] == "" || $shifoudingji[0]['pid'] == null){
    $order_id = $do->sqlTableId("sk_orders", "order_id");//订单id
    $dpgou_id = $do->sqlTableId("sk_dpgou", "dpgou_id");//资料id
    $curtime = date('YmdHis',time());//时间编号
    if($_POST['goodsid'] && $_POST["name"] && $_POST["shouji"] && $_POST["weixinqq"] && $_POST["dizhi"]&&$_FILES['business']&&$_FILES['dianzhao']){//&& $_POST["dailihaoma"] && $_POST["dailiren"]
        $business=upload($_FILES['business']);
        $dianzhao=upload($_FILES['dianzhao']);
        $sql = "insert into sk_dpgou (dpgou_id,name, hsouji, weixinqq, jiameng_dizhi, daili_name, daili_haoma,orderid,business,dianzhao) values (".$dpgou_id.",'".quotes($_POST["name"])."','".quotes($_POST["shouji"])."','".quotes($_POST["weixinqq"])."','".quotes($_POST["dizhi"])."','".quotes($_POST["dailiren"])."','".quotes($_POST["dailihaoma"])."',".$order_id.",'$business','$dianzhao')";
        if($do -> dealsql($sql)){
            if($_POST['goodsid']){
                //——————————————————————————————————————————————————————————————————————————————————————————————————————
                $sql = "select * from sk_goods where id=".intval($_POST['goodsid']);
                $resultArr = $do ->selectsql($sql);
                $dingdzj = $resultArr[0]["price"];
                
                $sql = "insert into sk_order_goods (order_id,goods_id,goods_name,specification,price,quantity,goods_images,goods_code) values ('$order_id','".$resultArr[0]["id"]."',\"".$do -> gohtml($resultArr[0]["good_name"])."\",'".$resultArr[0]["guige"]."','".$dingdzj."','1','".$resultArr[0]["img"]."','".$resultArr[0]['pcode']."')";//插入订单商品表
                $do->dealsql($sql);
                /*$sql = "update sk_goods set kucun = kucun - 1 where id='".$resultArr[0]["id"]."'";//商品库存减去
                $do->dealsql($sql);*/
                $sql = "update sk_goods set xiaoliang = xiaoliang + 1 where id='".$resultArr[0]["id"]."'";//商品销量增加
                $do->dealsql($sql);
                
                if($do->dealsql("insert into sk_orders (order_id,order_sn,buyer_id,status,order_time,add_time,ipdz,erm,goods_amount) values ('$order_id','$curtime','$openid','0','".date('Y-m-d H:i:s',time())."',".time().",'".getip()."',1,".$dingdzj.")")){//插入订单表
                    //$do -> weixintishi($openid,"恭喜 ".date('Y-m-d H:i:s',time())." 在和众世纪下单成功，订单总价 ".$dingdzj." 元，邮费 0.00 元");
                    $name = $do -> selectsql("select wei_nickname from sk_member where open_id = '$openid'");
                    $weixintxt="亲爱的 ".$name[0]['wei_nickname']."，恭喜您于 ".date('Y-m-d H:i:s',time())." 在洋仆淘下单成功，订单总价 ".$dingdzj." 元，邮费 0.00 元。该来的最终还是会来的，我就是您的一见钟情，感谢您对洋仆淘的支持，快递小哥即将把我送到您的手中。";
                    $do -> weixintishi($openid,$weixintxt);
                    $do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$openid."',2)");
                    echo "{\"success\":\"1\"}";
                }else {
                    $do -> dealsql("delete from sk_dpgou where dpgou_id=".$dpgou_id);
                    echo "{\"success\":\"0\"}";
                }
                //——————————————————————————————————————————————————————————————————————————————————————————————————————
                //二维码生成
                include '../../base/phpqrcode/phpqrcode.php';
                
                $errorCorrectionLevel = 'L'; // 容错级别
                $matrixPointSize = 6; // 生成图片大小
                
                $i_shu = mt_rand(100,999);//___唯一编号↓
                $i_shu2 = time();
                $i_sql = "select * from sk_erweima where bianhao='".$i_shu2.$i_shu."'";//检查该编号是否存在
                $i_resuArr = $do->selectsql($i_sql);
                if(count($i_resuArr)>0){
                    $i_congfu = $i_shu;
                    while($i_congfu == $i_shu){
                        $i_shu = mt_rand(100,999);
                    }
                }//___唯一编号↑ $i_shu2.$i_shu
                $access_token = $_SESSION["access_token"];
                $headimgurl = "";//$_SESSION["headimgurl"];//二维码中间的小图标(带图片报错 )
                $link = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state='.$openid.'|2|'.$resultArr[0]["id"].'&connect_redirect=1#wechat_redirect';
                $QR = '../../images/erweima/' . $i_shu2.$i_shu . '.png';//存放二维码地址
                QRcode::png ( $link, $QR, $errorCorrectionLevel, $matrixPointSize, 2 );
                	
                $logo = $headimgurl;
                if ($logo !== FALSE) {
                    $QR = imagecreatefromstring ( file_get_contents ( $QR ) );
                    $logo = imagecreatefromstring ( file_get_contents ( $logo ) );
                    $QR_width = imagesx ( $QR ); // 二维码图片宽度
                    $QR_height = imagesy ( $QR ); // 二维码图片高度
                    $logo_width = imagesx ( $logo ); // logo图片宽度
                    $logo_height = imagesy ( $logo ); // logo图片高度
                    $logo_qr_width = $QR_width / 5;
                    $scale = $logo_width / $logo_qr_width;
                    $logo_qr_height = $logo_height / $scale;
                    $from_width = ($QR_width - $logo_qr_width) / 2;
                    // 重新组合图片并调整大小
                    imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
                }
                imagepng($QR, '../../images/erweima/' . $i_shu2.$i_shu . '.png');//存放二维码地址
                //每个商品对应的二维码插入数据库
                $do->selectsql("insert into sk_erweima (bianhao,dingdan,openid,shangpin,dizhi) values ('".$i_shu2.$i_shu."','".$order_id."','$openid',".$resultArr[0]["id"].",'wei/images/erweima/" . $i_shu2.$i_shu . ".png')");
                //——————————————————————————————————————————————————————————————————————————————————————————————————————
            }else {
                $do -> dealsql("delete from sk_dpgou where dpgou_id=".$dpgou_id);
                echo "{\"success\":\"0\"}";
            }
        }
    }else
        echo "{\"success\":\"0\"}";
}else{
    echo "{\"success\":\"2\"}";
}
?>