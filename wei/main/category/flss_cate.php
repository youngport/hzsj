<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://" . $_SERVER['HTTP_HOST'];

session_start();
if (!isset($_SESSION["access_token"])) {
    header("Location:" . $cxtPath . "/wei/login.php");
    return;
}
$cartnum = $_SESSION["cartnum"];
$categoryid = intval($_GET["categoryid"]);
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];
//$dianji = $_GET["ddjm"];
//$tiaoid="";//跳转页面所带参数
$do = new condbwei();
$com = new commonFun();

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$imgurl = 'http://' . $_SERVER['HTTP_HOST'] . "/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);

// $sql = "select jointag from sk_member where open_id='$openid'";
// $shifou = $do -> selectsql($sql);
// $show_heide = false;
if ($shifou[0]['jointag'] != 0) {
    $show_heide = true;
}

//一级分类
$sql_1 = "select id,name from sk_items_cate where pid=5 and id!=167";
$directory = $do->selectsql($sql_1);
//二级
//$sql = "select id,name from sk_items_cate where pid=65";
//	$result = $do -> selectsql($sql);
//	foreach ($result as $key => $value) {
//		//三级
//		$sql_ = "select good_name,img,id,cate_id,canyuhd from sk_goods where cate_id=".$value['id']." limit 0,6";
//		$result[$key]['val'] = $do -> selectsql($sql_);
//	}
$nationArr = array();
$mp_cateArr = array();

//$sql_ = "select good_name,img,id,cate_id,canyuhd from sk_goods where cate_id=".$value['id']." limit 0,6";
//查出商品所属国籍
$sql_ = "select id, nationality,nationality_img from sk_nationality where cate_id='65'";
$nationArr = $do->selectsql($sql_);

// 查出商品所属品类
$sql_mp_cate = " select id,name,mp_img from sk_mingp_cate where cate_id='65'";
$mp_cateArr = $do->selectsql($sql_mp_cate);

//查询品牌
$sql_mp = " select id,name,mp_img from sk_mingp where cate_id='65'";
$mpArr = $do->selectsql($sql_mp);
//var_dump($mpArr);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta content="telephone=no" name="format-detection" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
        <title>分类搜索</title>
        <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/home.css"/>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/fenlei.css">
        <style type="text/css">
        * {
            color: #868383; 
        }
        </style>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/fenlei.js">
        </script>
        <script>
            $(function () {
                $(".zuo ul li").click(function () {
                    $(this).addClass("on").siblings().removeClass("on");
                    $(".qie .you").hide().eq($(".zuo ul li").index(this)).show();
                    var id = $(this).attr("data-id");
                    var url = "<?php echo $cxtPath . '/'; ?>";
                    $.ajax({
                        type: "POST",
                        url: "flss_cate_post.php",
                        data: {id: id},
                        dataType: "json",
                        success: function (data) {
                            /* var data = eval("("+data+")");*/
                           /* console.log(data.a);*/
                            /*alert(show);*/
                            var one = "";
                            var show = data.a;
                            //alert(show.length);
                            $(".you").empty();
                            one += "<div class=\"lei\">";
                            one += "<h2>品类</h2>";
                            one += "<ul class=\"clearfix u1\">";
                            if (show == null) {
                               one += "<li style=\"margin: 1.5% 1.5%;float: left;display: inline-block;width: 30.3%;background: #fff;\">无</li>";
                            } else {
                                for (var i = 0; i < show.length; i++) {
                                    one += "<li style=\"margin: 1.5% 1.5%;float: left;display: inline-block;width: 30.3%;background: #fff;\"><a style=\"display:block\" href='flss_cate_list.php?cate_id=" + id + "&mp_cate_id=" + show[i].id + "'><img src=\"" + url + show[i].mp_img + "\" style=\"width:78%;display:block;margin:0 auto;\"><span style=\"display:block;text-align:center;\">" + show[i].name + "</span></a></li>";
                                }
                            }
                            one += "</ul>";
                            one += "</div>";
                            one += "<div class=\"lei\" >";
                            one += "<h2>国家</h2>";
                            one += "<ul class=\"clearfix u2\">";
                            var guo = data.b;
                           if (guo == null) {
                               one += "<li style=\"margin: 1.5% 1.5%;float: left;display: inline-block;width: 30.3%;background: #fff;\">无</li>";
                            } else {
                                for (var i = 0; i < guo.length; i++) {
                                    one += "<li style=\"padding:14px 0\"><a style=\"display:block\" href='flss_cate_list.php?cate_id=" + id + "&nation_id="+guo[i].id+"'><img src=\"" + url + guo[i].nationality_img + "\" style=\"width:78%;display:block;margin:0 auto;\"><span style=\"display:block;text-align:center;\">" + guo[i].nationality + "</span></a></li>";
                                }
                            }
                            one += "</ul>";
                            one += "</div>";
                            one += "<div class=\"lei\">";
                            one += "<h2>品牌</h2>";
                            one += "<ul class=\"clearfix u3\">";
                            var mp = data.c;
                            if (mp == null) {
                              one += "<li style=\"margin: 1.5% 1.5%;float: left;display: inline-block;width: 30.3%;background: #fff;\"><img src=\"//m.360buyimg.com/mobilecms/s220x220_jfs/t2446/77/2398542210/101191/977558cb/57053977N7ef977a0.jpg!q70.jpg\"/></li>";
                            
                            } else {
                            for (var i = 0; i < mp.length; i++) {
                                one += "<li><a style=\"display:block\" href='flss_cate_list.php?cate_id=" + id + "&mp_id="+mp[i].id+"'><img src=\"" + url + mp[i].mp_img + "\" style=\"width:78%;display:block;margin:0 auto;\"><span style=\"display:block;text-align:center;\">" + mp[i].name + "</span></a></li>";
                                }
                            }
                            one += "</ul>";
                            one += "</div>";
                            one += "</div>";


                            $(".you").append(one);
                        }
                    })
                })
            })
        </script>		
    </head>
    <body>
        <div class="bigbox">
            <form action="flss_xq.php" method="post">
                <div class="head"><input type="text"  id='productName' name='productName' placeholder="搜索"></div>
            </form>
            <div class="botbox clearfix">
                <div class="zuo">
                    <ul>
                        <?php foreach ($directory as $key => $value) { ?>
                            <li data-id="<?php echo $value['id'] ?>" <?php if($value['id']==65){ echo " class ='on'"; }   ?> ><?php echo $value['name'] ?></li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="qie clearfix">
                    <div class="you">
                        <div class="lei">
                            <h2>品类</h2>
                            <ul class="clearfix u1">
                                <?php foreach ($mp_cateArr as $k => $v) { ?>
                                    <li><a style="display:block" href="flss_cate_list.php?cate_id=65&mp_cate_id=<?php echo $v['id'] ?>"><img src="<?php echo $cxtPath . $v['mp_img']; ?>" style="width:78%;display:block;margin:0 auto;"><span style="display:block;text-align:center;"><?php echo $v['name']; ?></span></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="lei" style="margin-left:2%;">
                            <h2>国家</h2>
                            <ul class="clearfix u2">
                                <?php foreach ($nationArr as $k => $v) { ?>
                                    <li style="padding:14px 0"><a style="display:block" href="flss_cate_list.php?cate_id=65&nation_id=<?php echo $v['id']; ?>"><img src="<?php echo $cxtPath . $v['nationality_img']; ?>" style="width:78%;display:block;margin:0 auto;"><span style="display:block;text-align:center;"><?php echo $v['nationality']; ?></span></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="lei" style="margin-left:2%;">
                            <h2>品牌</h2>
                            <ul class="clearfix u3">
                                <?php foreach ($mpArr as $k => $v) { ?>
                                    <li><a style="display:block" href="flss_cate_list.php?cate_id=65&mp_id=<?php echo $v['id']; ?>"><img src="<?php echo $cxtPath . $v['mp_img']; ?>" style="width:78%;display:block;margin:0 auto;"><span style="display:block;text-align:center;"><?php echo $v['name']; ?></span></a></li>
                                <?php } ?>
                            </ul>
                        </div>

                    </div>
                    <div class="you" style="display:none"></div>
                    <div class="you" style="display:none"></div>
                    <div class="you" style="display:none"></div>
                </div>
            </div>
        </div>
        <?php include '../gony/nav.php'; ?>
        <?php include '../gony/guanzhu.php'; ?>
        <?php include '../gony/returntop.php'; ?>	
        <div style="clear:both;height:55px;"></div>
    </body>
</html>












