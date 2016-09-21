<?php
include '../base/condbwei.php';
include '../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
$menuArr = $com->mainMenu($do);
//$json = $com->createBaseJson($menuArr, "id,name");
$json = "";
if(count($menuArr)>0){
	$json = "[";
	for($i=0;$i<count($menuArr);$i++){
		$json .= "{\"id\":\"".$menuArr[$i]["id"]."\",";
		$json .= "\"name\":\"".$menuArr[$i]["name"]."\",";
		$json .= "\"shop\":";
		$sql = "select id,good_name,img,img2,price from sk_goods where cate_id='".$menuArr[$i]["id"]."' and recom=1 order by sort_order asc limit 0,5";
		$goodsArr = $do->selectsql($sql);
		if(count($goodsArr)>0){
			$json .= "[";
			for($j=0;$j<count($goodsArr);$j++){
				$json .= "{\"id\":\"".$goodsArr[$j]["id"]."\",";
				$json .= "\"good_name\":\"".$goodsArr[$j]["good_name"]."\",";
				$json .= "\"img\":\"".$goodsArr[$j]["img"]."\",";
				$json .= "\"img2\":\"".$goodsArr[$j]["img2"]."\",";
				$json .= "\"price\":\"".$goodsArr[$j]["price"]."\"},";
			}
			$json = substr($json, 0, strlen($json)-1)."]},";
		}else{
			$json .= "[]},";
		}
	}
	$json = substr($json, 0, strlen($json)-1)."]";
}
echo $json;
?>








<?php 
include '../base/condbwei.php';
include '../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
$menuArr = $com->mainMenu($do);
//$json = $com->createBaseJson($menuArr, "id,name");
$appStr = "";
if(count($menuArr)>0){
	for($i=0;$i<count($menuArr);$i++){
		$appStr = "<div class=\"n_layout\"><div class=\"layout_top\"><h3>".$menuArr[$i]["name"]."</h3>";
		$appStr += "<a href='main/category/categoryGoods.php?categoryid=".$menuArr[$i]["id"]."&categoryname=".$menuArr[$i]["name"]."'>更多</a></div>";
		$appStr += "<ul class=\"n_layout_list proudct_list clearfix\" data-layoutList=\"1\">";
		$shopStr = "";
		$sql = "select id,good_name,img,img2,price from sk_goods where cate_id='".$menuArr[$i]["id"]."' and recom=1 order by sort_order asc limit 0,5";
		$goodsArr = $do->selectsql($sql);
		if(count($goodsArr)>0){
			for($j=0;$j<count($goodsArr);$j++){
				$shopStr +="<li data-layout='2'><a href=\"<?php echo $cxtPath ?>/wei/main/goods/goods.php?id=".$goodsArr[$j]["id"]."\"><div class=\"product_img\">";
				if(j==0)
					$shopStr +="<img data-src=\"".$goodsArr[$j]["img2"]."\" src=\"".$goodsArr[$j]["img2"]."\">";
				else
					$shopStr +="<img data-src=\"".$goodsArr[$j]["img"]."\" src=\"".$goodsArr[$j]["img"]."\" >";
				$shopStr +="</div></a></li>";
			}
		}else{
			$shopStr += "";
		}
		echo $appStr.$shopStr."</ul></div>";
	}
}
?>
				