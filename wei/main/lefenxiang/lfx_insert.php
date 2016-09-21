<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
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
$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];


$arr = explode(",",quotes($_POST['imgname']));
$biaoti = quotes($_POST['biaoti']);
$fenlei = intval($_POST['fenlei']);
$xinde = quotes($_POST['xinde']);
//$xinde = $_POST['xinde'];


$lfx_id = $do->sqlTableId("sk_lfx", "id");
	$sql = "insert into sk_lfx (id,biaoti,neirong,shijian,openid,fenlei)values(".$lfx_id.",'$biaoti','$xinde',".time().",'$openid',".$fenlei.")";
	if($do->dealsql($sql)){
	    $sql = "insert into sk_lfx_img ( lfxid, imgurl,s_imgurl ,shunxu) values ";
	    for($i=0;$i<count($arr)-1;$i++){
	    	$src_path =$arr[$i];
        	$dst_w = 227;
        	$dst_h = 231;
        	//创建原图的实例
        	$src = imagecreatefromjpeg($cxtPath."/".$src_path);
			list($src_w,$src_h)=getimagesize('../../../'.$src_path); // 获取原图尺寸 
			//var_dump($src);exit;
			$dst_scale = $dst_h/$dst_w; //目标图像长宽比 
			$src_scale = $src_h/$src_w; // 原图长宽比 
			if($src_scale>=$dst_scale) 
			{ 
			// 过高 
			$w = intval($src_w); 
			$h = intval($dst_scale*$w); 
			$x = 0; 
			$y = ($src_h - $h)/3; 
			} 
			else 
			{ 
			// 过宽 
			$h = intval($src_h); 
			$w = intval($h/$dst_scale); 
			$x = ($src_w - $w)/2; 
			$y = 0; 
			} 
			// 剪裁 
			$source=imagecreatefromjpeg('../../../'.$src_path); 
			$croped=imagecreatetruecolor($w, $h); 
			imagecopy($croped,$source,0,0,$x,$y,$src_w,$src_h);
			// 缩放 
			$scale = $dst_w/$w; 
			$target = imagecreatetruecolor($dst_w, $dst_h); 
			$final_w = intval($w*$scale); 
			$final_h = intval($h*$scale); 
			imagecopyresampled($target,$croped,0,0,0,0,$final_w,$final_h,$w,$h);
			// 保存 
			//var_dump($source);exit;
			$s_imgurl = str_replace(".","_s.",$src_path);
			imagejpeg($target, '../../../'.$s_imgurl);        				
	        $sql .= "( '$lfx_id', '".$arr[$i]."','".$s_imgurl."',".$i."),";
	    }
	    $do->dealsql(substr($sql,0,-1));
	    setcookie();
        $cookie_up = $_COOKIE["fenxiang"] ;
        $arr = explode(",",$cookie_up);
        $sql = "insert into sk_lfx_goods ( goodsid, lfxid, sunxu) values ";
        for($i=0;$i<count($arr);$i++){
            $sql .= "( '$arr[$i]', '".$lfx_id."',".$i."),";
        }
        $do->dealsql(substr($sql,0,-1));

        setcookie("fenxiang","", 3600);
	    echo 1;
	}
	else
	    echo 0;
?>