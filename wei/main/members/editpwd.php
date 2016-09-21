<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
session_start();
$cxtPath = "http://" . $_SERVER['HTTP_HOST'];


if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}

if(!isset($_SESSION['confirm'])) {
    echo "<script>window.history.back(-1);</script>";
    exit;
}
$openid = $_SESSION["openid"];

//$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
//$result=$do->getone("select phone_tel from sk_member where open_id='$openid'");

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta content="telephone=no" name="format-detection" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <title>设置密码</title>
        <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
        <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
        <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="//cdn.bootcss.com/jquery-validate/1.14.0/jquery.validate.min.js"></script>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
        <style>
            .confirm input{background:#F2F2F2;border:0;}
            .confirm button{width:60%;}
            .mob{border:0;background:#F2F2F2 url(../../img/mobile.png) no-repeat scroll right;background-size:60%;padding-left:2em;}
            .ver{border:0;background:#F2F2F2 url(../../img/ver.png) no-repeat scroll right;background-size:60%;padding-left:2em;}
            .suo{border:0;background:#F2F2F2 url(../../img/suo.png) no-repeat scroll right;background-size:60%;padding-left:2em;}
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
                border-top: none; 
            }
        </style>
    </head>
    <body style="background:#F2F2F2">
        <div class="container-fluid" style="padding:0;">
            <div class="container-fluid" style="padding: 0;">
                <form class="confirm">
                    <table class="table">
                        <tr style="background:#fff;">
                            <td colspan="2" style="border-top:18px solid #f0f0f0;">
                                <div class="input-group" style="width:100%;background-color: white;">
                                    <input type="password" class="form-control" id="password" style="background-color: white;box-shadow:none;" name="password" placeholder="新密码" value=""/>
                                </div>
                            </td>
                        </tr>
                        <tr style="background:#fff">
                            <td colspan="2" style="border-top:18px solid #f0f0f0;">
                                <div class="input-group" style="width:100%">
                                    <input type="password" class="form-control" id="repassword" style="background-color: white;border: 0;box-shadow:none;" name="repassword" placeholder="确认密码"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <button name="submit" class="btn btn-danger btn-lg center-block">确认</button>
                </form>
            </div>
        </div>
    </body>
    <script>
        $("td").css("border", 0);
        jQuery.validator.addMethod("isPassword", function (value, element) {
            var password = /^(\w){6,12}$/;
            return this.optional(element) || (password.test(value));
        }, "密码格式错误，只允许包含字母、数字、下划线 6到12位");
        $(".confirm").validate({
            //debug:true,
            rules: {
                password: {
                    required: true,
                    isPassword: true
                },
                repassword: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                password: {
                    required: "请输入密码"
                },
                repassword: {
                    required: "请输入确认密码",
                    equalTo: "两次输入密码不一致"
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    type: "POST",
                    url: "checkpwd.php",
                    data: $(form).serialize(),
                    success: function (data) {
                        console.log(data);
                        if (data == 1) {
                            alert("设置完成");
                            location.href = "../../index.php";
                        } else if (data == 6)
                            msgError("密码格式错误");
                        else if (data == 7)
                            msgError("两次输入密码不一致");
                    }
                });
            }
        });
    </script>
</html>