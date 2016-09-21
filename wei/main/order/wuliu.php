<?php
include '../../base/condbwei.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}
$order_id=intval($_GET['order_id']);
$status= addslashes($_GET['status']) ;
$openid=$_SESSION['openid'];
$do = new condbwei();
$sql="select a.shipping_name,a.invoice_no,c.good_name,c.img,c.price from sk_order_goods a left join sk_orders b on a.order_id=b.order_id left join sk_goods c on a.goods_id=c.id where a.order_id=$order_id and buyer_id='$openid'";
$kd=$do->selectsql($sql);
if(empty($kd)){
    header("content-type:text/html;charset=utf8");
    echo "<script>alert('信息非法');window.history.back(-1);</script>";
    exit;
}
$sql = "SELECT shipping_name name,invoice_no danhao,xinxi,zhuangtai FROM `sk_order_goods` a left join sk_kuaidi b on a.invoice_no=b.danhao WHERE order_id=$order_id  group by invoice_no order by xinxi desc";
$info=$do->selectsql($sql);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>物流查询</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style>
        .gview{width:50%;border-left:1px solid #E3E3E3;border-bottom:1px solid #E3E3E3;}
        .gimg{width:100%;max-height:20%;min-height:20%}
        .xian{padding-left:1.5em;padding-bottom:0.7em;border-left:2px solid #AFAFAF;width:90%;position: relative;}
        .dian{width:0.7em;height:0.7em;background:#6D6D6D;border-radius: 100%;position: absolute;left:-0.4em;}
        .xinxi{color:#AFAFAF;border-bottom:1px solid #AFAFAF;width:100%;}
    </style>
</head>
<body>
<div>
    <div class="container-fluid" style="background:#707375;">
        <h4 style="color:#fff"><strong>物流查询</strong></h4>
    </div>
    <?php
        foreach($info as $v){
            if($v['name'] == "ems")
                $v['sname'] = "EMS";
            else if($v['name'] == "pcaexpress")
                $v['sname'] = "PCA Express";
            else if($v['name'] == "kingfreight")
                $v['sname'] = "貨運皇";
            else if($v['name'] == "youzhengguoji")
                $v['sname'] = "邮政小包（国际）";
            else if($v['name'] == "youzhengguonei")
                $v['sname'] = "邮政小包（国内）";
            else if($v['name'] == "yunda")
                $v['sname'] = "韵达快运";
            else if($v['name'] == "shunfeng")
                $v['sname'] = "顺丰速递";
            else if($v['name'] == "zhongtong")
                $v['sname'] = "中通速递";
            else if($v['name'] == "yuantong")
                $v['sname'] = "圆通速递";
            else if($v['name'] == "shentong")
                $v['sname'] = "申通快递";
            else
                $v['sname'] = "";
    ?>
    <div class="info">
        <div class="container-fluid" style="background:#F5F5F5;border-top:1px solid #AFAFAF;">

            
            
            <?php 
                if( $v['danhao']==''){
            ?>
                <h5 class="pull-right">清关中</h5>
            <?php }else{ ?>
                <h5 class="pull-left"><?php echo $v['sname']; ?> &nbsp;&nbsp;快递单号:<?php echo $v['danhao']; ?></h5>
                <h5 class="pull-right"><?php if($v['xinxi']){?>已发货<?php }else{?>暂未发货<?php }?></h5>
            <?php } ?>
        </div>
        <div>
            <?php
            foreach($kd as $k=>$g) {
                if($g['invoice_no']==$v['danhao']&&$g['shipping_name']==$v['name']){
                    unset($kd[$k]);
            ?>
            <div class="pull-left gview">
                <img src="<?php echo $cxtPath . '/' . $g['img']; ?>" alt="" class="gimg">

                <div class="container-fluid">
                    <h6 class="text-center">￥<?php echo $g['price']; ?></h6>
                    <h5 class="text-center"><?php echo $g['good_name']; ?></h5>
                </div>
            </div>
            <?php
                }
            }
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="container-fluid" style="border-top:1px solid #E3E3E3;margin-top:-1px">
            <?php if($v['danhao']==''){  ?>
                 <h4 class="pull-right xq" style="color:#84B5E1;"><strong>海关放行后有物流信息</strong></h4>
            <?php }else { 
                if($v['xinxi']!=''){?>
                <h4 class="pull-right xq" style="color:#84B5E1;"><strong>物流详情</strong></h4>
                <?php }else{?>
                <h4 class="pull-right" style="color:#84B5E1;"><strong>暂无快递信息</strong></h4>
            <?php }}?>
        </div>
        <div style="display:none">
            <?php
                $xinxi = json_decode($v['xinxi']);
                foreach ($xinxi as $k) {
                    echo "<div class='center-block xian'>";
                    echo "<div class='dian'></div>";
                    echo "<div class='xinxi'>";
                    echo "<h5 style='margin:0;'><strong>" . $k->context . "</strong></h5>";
                    echo "<small><strong>" . $k->ftime . "</strong></small>";
                    echo "</div></div>";
                }
            ?>
        </div>
    </div>
    <?php
        }
    ?>
</div>
</body>
<script>
    $('.xq').click(function(){
        var info=$(this).parent().next();
        info.toggle();
        info.find('.xian:last').css("border",0);
        info.find('.xinxi:last').css("border",0);
        info.find('.dian:first').css('background','#709F0C');
        info.find('.dian:last').css('left','-0.3em');
        info.find('h5:first').css('color','#709F0C');
        $("html,body").animate({scrollTop:$(this).offset().top},800);
    });
</script>
</html>