<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ajax无刷新返回上次浏览位置</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<div id="all"></div>
<!--   引入jQuery -->
<script type="text/javascript" src="http://m.hz41319.com/statics/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="http://m.hz41319.com/data/wei/buystore/js/cookie.js"></script>
<script type="text/javascript" src="http://127.0.0.1/hz/statics/js/jquery/plugins/jquery.histroy.js"></script>
<!--   引入jQuery -->
<script>
    var s='12 34 5 6';
    alert(s.length);
    var str = window.location.href;
    str = str.substring(str.lastIndexOf("/") + 1);
    if ($.cookie('back')) {
        $.cookie('back', null);
    } else {
        $.cookie(str, null, {path: "/"});
    }
    /*返回上次浏览位置*/
    $(function () {
        if ($.cookie(str)) {
            var length = $.cookie(str) - $.cookie('page') * $(window).height();
            $("html,body").animate({scrollTop: $.cookie(str)}, 1000);
        }
    })
    $(window).scroll(function () {
        //var top = $(document).scrollTop();
        var top = $(window).scrollTop();
        $.cookie(str, top, {path: '/'});
        return $.cookie(str);
    })
    /*返回上次浏览位置*/
</script>
<script type="text/javascript">
    $(function () {
        var page = 1;
        if ($.cookie('page')) {
            page = parseInt($.cookie('page'));
        }
        getData(page);
        $(window).bind('scroll', function () {
            if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                if ($('#tip').length < 1)$('#all').append('<span id="tip" align="center" style="color:red;"><p>亲，没有更多内容啦！</p></span>');
                return false;
                page += 1;
                if ($.cookie('page')) {
                    $.cookie('page', null);
                }
                if (page > $.cookie(str + 'pageTotal')) {
                    if ($('#tip').length < 1)$('#all').append('<span id="tip"><p>亲，没有更多内容啦！</p></span>');
                    return false;
                } else {
                    getData(page);
                }
                //console.log($(window).scrollTop());console.log($(window).height());console.log($(document).height());
            } else {
//                if($.cookie('page') && $.cookie('page')>1){
//                    page=parseInt($.cookie('page'));
//                    for(var i=page-1;i>0;i--){
//                        getData(i,'desc');
//                    }
                $.cookie('page', null);
                //}

            }
        });
        function getData(page, sort) {
            var pic = 'https://img.alicdn.com/imgextra/i3/2531831252/TB2GccdtXXXXXccXXXXXXXXXXXX_!!0-ifashion.jpg_670x670q90.jpg';
            $.ajax({
                type: "GET",
                url: "<?php echo u('goods/test_post');?>",
                data: {"p": page},
                dataType: "json",
                success: function (json) {
                    if (json) {
                        var data = json.data;
                        var html = '';
                        for (var i = 0; i < data.data.length; i++) {
                            html += '<div style="width:300px;height:220px;border:2px solid red;">';
                            html += '<a href="http://m.hz41319.com/hz/index.php?id=' + data.data[i].id + '" target="_blank">' + data.data[i].id;
                            html += '<img style="width:300px;height:220px;"src="' + pic + '"  title="">';
                            html += '</a>';
                            html += '</div><br/>';
                        }
                        if (sort == 'desc') {
                            //$("#all").prepend(html);
                        } else {
                            $('#all').append(html);
                            console.log(data.data[1].id);
                            $.cookie(str + 'pageTotal', parseInt(data.page_total));
                        }

                    }

                }
            });
            $("#all").click(function () {
                $.cookie('page', page);
                $.cookie('back', true);
            });
        }

    })
</script>
</body>
</html>