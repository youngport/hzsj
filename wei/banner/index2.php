<?php 
include '../base/condbwei.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST']."/";

$do = new condbwei();
$sql = "select gg_imgurl from sk_guanggao where gg_youxiao=1 order by gg_paixu asc limit 0,5";
$resultArr = $do->selectsql($sql);


?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>轮换</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="stylesheet" href="images/css/style.css" />
<link rel="stylesheet" href="//at.alicdn.com/t/font_1451210213_8925328.css" />

<script type='text/javascript' src='images/js/modernizr.min.js?ver=2.6.1'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var CSSettings = {"pluginPath":"images"};
/* ]]> */
</script>
<script type='text/javascript' src='images/js/cute.slider.js?ver=2.0.0'></script>
<script type='text/javascript' src='images/js/cute.transitions.all.js?ver=2.0.0'></script>
</head>
<style>
@font-face {
  font-family: 'iconfont';
  src: url('//at.alicdn.com/t/font_1451210213_770814.eot'); /* IE9*/
  src: url('//at.alicdn.com/t/font_1451210213_770814.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
  url('//at.alicdn.com/t/font_1451210213_770814.woff') format('woff'), /* chrome、firefox */
  url('//at.alicdn.com/t/font_1451210213_770814.ttf') format('truetype'), /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
  url('//at.alicdn.com/t/font_1451210213_770814.svg#iconfont') format('svg'); /* iOS 4.1- */
}
                    
.da{ font-size:200px;color:#900;}
</style>
<body>
<i class="iconfont da">&#xe600;</i>
<i class="iconfont da">&#xe601;</i>
<div class="c-860 c-demoslider">
  <div id="cuteslider_3_wrapper" class="cs-circleslight">
    <div id="cuteslider_3" class="cute-slider" data-width="1920" data-height="1080" data-overpause="true">
      <ul data-type="slides">
      <?php 
      for($i = 0;$i < count($resultArr);$i++){
          if($i==0)
            echo '<li data-delay="5" data-src="5" data-trans3d="tr6,tr17,tr22,tr23,tr29,tr27,tr32,tr34,tr35,tr53,tr54,tr62,tr63,tr4,tr13,tr45" data-trans2d="tr3,tr8,tr12,tr19,tr22,tr25,tr27,tr29,tr31,tr34,tr35,tr38,tr39,tr41"><img  src="'.$cxtPath.$resultArr[$i]['gg_imgurl'].'" data-thumb="'.$cxtPath.$resultArr[$i]['gg_imgurl'].'"><a data-type="link" href="http://html6game.com" target="_blank"></a></li>';
          else 
              echo '<li data-delay="5" data-src="5" data-trans3d="tr6,tr17,tr22,tr23,tr26,tr27,tr29,tr32,tr34,tr35,tr53,tr54,tr62,tr63,tr4,tr13" data-trans2d="tr3,tr8,tr12,tr19,tr22,tr25,tr27,tr29,tr31,tr34,tr35,tr38,tr39,tr41"><img  src="images/bg/blank.png" data-src="'.$cxtPath.$resultArr[$i]['gg_imgurl'].'" data-thumb="'.$cxtPath.$resultArr[$i]['gg_imgurl'].'"><a data-type="link" href="http://html6game.com" target="_blank"></a></li>';
      }
      ?>
       
        <!-- <li data-delay="5" data-src="5" data-trans3d="tr6,tr17,tr22,tr23,tr26,tr27,tr29,tr32,tr34,tr35,tr53,tr54,tr62,tr63,tr4,tr13" data-trans2d="tr3,tr8,tr12,tr19,tr22,tr25,tr27,tr29,tr31,tr34,tr35,tr38,tr39,tr41"><img  src="images/bg/blank.png" data-src="images/001/002.jpg" data-thumb="images/001/002a.png"><a data-type="link" href="http://html6game.com" target="_blank"></a>
        </li>
        <li data-delay="5"  data-src="5" data-trans3d="tr6,tr17,tr22,tr23,tr26,tr27,tr29,tr32,tr34,tr35,tr53,tr54,tr62,tr63,tr4,tr13" data-trans2d="tr3,tr8,tr12,tr19,tr22,tr25,tr27,tr29,tr31,tr34,tr35,tr38,tr41"><img  src="images/bg/blank.png" data-src="images/001/003.jpg" data-thumb="images/001/003a.png"><a data-type="link" href="http://html6game.com" target="_blank"></a>
        
        </li>
        <li data-delay="5" data-src="5" data-trans3d="tr6,tr17,tr22,tr23,tr26,tr27,tr29,tr32,tr34,tr35,tr53,tr54,tr62,tr63,tr4,tr13" data-trans2d="tr3,tr8,tr12,tr19,tr22,tr25,tr27,tr29,tr31,tr34,tr35,tr38,tr39,tr41"><img  src="images/bg/blank.png" data-src="images/001/004.jpg" data-thumb="images/001/004a.png"><a data-type="link" href="http://html6game.com" target="_blank"></a>
        </li>
        <li data-delay="5" data-src="5" data-trans3d="tr6,tr17,tr22,tr23,tr26,tr27,tr29,tr32,tr34,tr35,tr53,tr54,tr62,tr63,tr4,tr13" data-trans2d="tr3,tr8,tr12,tr19,tr22,tr25,tr27,tr29,tr31,tr34,tr35,tr38,tr39,tr41"><img  src="images/bg/blank.png" data-src="images/001/006.jpg" data-thumb="images/001/006a.png"><a data-type="link" href="http://html6game.com" target="_blank"></a>
        </li> -->
      </ul>
      <ul data-type="controls">
        <li data-type="captions"></li>
        <li data-type="link"></li>
        <li data-type="slideinfo"></li>
        <li data-type="circletimer"></li>
        <li data-type="bartimer"></li>
      </ul>
    </div>
  </div>
  <p>
    <script type="text/javascript">
		var cuteslider3 = new Cute.Slider();
		cuteslider3.setup("cuteslider_3", "cuteslider_3_wrapper", "images/css/slider-style.css");
		cuteslider3.api.addEventListener(Cute.SliderEvent.CHANGE_START,
		function(event) {});
		cuteslider3.api.addEventListener(Cute.SliderEvent.CHANGE_END,
		function(event) {});
		cuteslider3.api.addEventListener(Cute.SliderEvent.WATING,
		function(event) {});
		cuteslider3.api.addEventListener(Cute.SliderEvent.CHANGE_NEXT_SLIDE,
		function(event) {});
		cuteslider3.api.addEventListener(Cute.SliderEvent.WATING_FOR_NEXT,
		function(event) {});
	</script>
</div>
</body>
</html>
