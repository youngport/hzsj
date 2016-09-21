<?php 
session_start();
/* $gouwu_int = $_SESSION["gouwu_int"];
if($gouwu_int ==""){ */
    //include './base/condbwei.php';
   // $doq = new condbwei();
    $sql = "select sum(buynum) gwint from wei_cart where openid='".$_SESSION["openid"]."'";
    $gouwu_int_Arr = $do->selectsql($sql);
    //$_SESSION["gouwu_int"] = $gouwu_int_Arr[0]['gwint'];
    $gouwu_int = $gouwu_int_Arr[0]['gwint'];
/* } */
?>
<div class="bottom" style="font-family:'黑体';font-size:10px;font-weight:normal;background:#181818;border:none;">
	<div style="width:20%;height:100%;text-align:center;float:left;" class="waizhi">
		<a href="<?php echo $cxtPath ?>/wei/index.php">
			<img data-src="<?php echo $cxtPath ?>/wei/images/home.png" src="<?php echo $cxtPath ?>/wei/images/home.png" style="width:2.2em;height:2em; margin:0.2em 0 0.4em 0;"><br/>
			<span>首页</span>
		</a>
	</div>
	<div style="width:20%;height:100%;text-align:center;float:left;" class="waizhi">
		<a href="<?php echo $cxtPath ?>/wei/main/cart/cart.php">
			<img data-src="<?php echo $cxtPath ?>/wei/images/cart.png" src="<?php echo $cxtPath ?>/wei/images/cart.png" style="width:2.2em;height:2em; margin:0.2em 0 0.4em 0;"><br/>
			<span>购物车<?php if($gouwu_int != ""){echo "（".$gouwu_int."）";}?></span>
		</a>
	</div>
	<div style="width:20%;height:100%;text-align:center;float:left;" class="waizhi">
		<a href="<?php echo $cxtPath ?>/wei/main/lefenxiang/lfx_index.php">
			<img data-src="<?php echo $cxtPath ?>/wei/images/fenxiang.png" src="<?php echo $cxtPath ?>/wei/images/fenxiang.png" style="width:2.2em;height:2em; margin:0.2em 0 0.4em 0;"><br/>
			<span>乐分享</span>
		</a>
	</div>
	<div style="width:20%;height:100%;text-align:center;float:left;" class="dianpud" id="hyzx">
		<a href="<?php echo $cxtPath ?>/wei/main/members/members_hy.php">
			<img data-src="<?php echo $cxtPath ?>/wei/images/category.png" src="<?php echo $cxtPath ?>/wei/images/category.png" style="width:2.2em;height:2em; margin:0.2em 0 0.4em 0;"><br/>
			<span>会员中心</span>
		</a>
	</div>
	<div style="width:20%;height:100%;text-align:center;float:left;" class="waizhi">
		<a href="<?php echo $cxtPath ?>/wei/main/user/user.php">
			<img data-src="<?php echo $cxtPath ?>/wei/images/person.png" src="<?php echo $cxtPath ?>/wei/images/person.png" style="width:2.2em;height:2em; margin:0.2em 0 0.4em 0;"><br/>
			<span>我</span>
		</a>
	</div>
</div>
<style>
.bottom a:link{ color:#868383;}
.bottom a:hover{ color:#868383;}
.bottom a:visited{ color:#868383;}
.cont a:link{ color:#fe7182;}
.cont a:hover{ color:#fe7182;}
.cont a:visited{ color:#fe7182;}
</style>
<script>
var urlstrt = location.href;

urlstr=urlstrt.split('/');
urlstr = urlstr[urlstr.length-1];
if(urlstr == 'members_hy.php' || urlstr == 'members_dp.php'){
	$('.dianpud').find('img').attr("src","<?php echo $cxtPath ?>/wei/images/categorya.png");
    $('#hyzx').addClass('cont');
}else{

$(".bottom a").each(function () {
    if ((urlstrt + '/').indexOf($(this).attr('href')) > -1&&$(this).attr('href')!='') {
        $(this).parent('div').addClass('cont');

        var url = new Array();
        url = $(this).attr('href').split("/");

        if(url[url.length-1]=='cart.php')
        	$(this).find('img').attr("src","<?php echo $cxtPath ?>/wei/images/carta.png");
        else if(url[url.length-1]=='index.php')
        	$(this).find('img').attr("src","<?php echo $cxtPath ?>/wei/images/homea.png");
        else if(url[url.length-1]=='lfx_index.php')
        	$(this).find('img').attr("src","<?php echo $cxtPath ?>/wei/images/fenxianga.png");
        else if(url[url.length-1]=='members_hy.php')
        	$(this).find('img').attr("src","<?php echo $cxtPath ?>/wei/images/categorya.png");
        else if(url[url.length-1]=='user.php')
        	$(this).find('img').attr("src","<?php echo $cxtPath ?>/wei/images/persona.png");
    }
  });
}
//alert(urlstr);
</script>