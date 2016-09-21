<?php
namespace ApiAdmin\Controller;
use Common\Controller\ApiAdminbaseController;
class GoodsController extends ApiAdminbaseController {
	protected $goods_model;
	
	function _initialize() {
		parent::_initialize();
		$this->goods_model = M("goods","sk_");
	}
	function goods_list(){
		$moder_goods = M("goods","sk_");
		$data = $moder_goods->field("id as gid,good_name,img")->limit(0,10)->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'商品搜索结果','value'=>$data));
	}
	function goods_search(){
		$good_name=I("good_name");
		if($good_name==''){
			$this->ajaxReturn(array('status'=>2,'message'=>'请输入商品名称'));
		}
		$formget['good_name']=array("like","%".$good_name."%");
		$formget['status']=1;
		$goodlist=M("goods","sk_")->field("id as gid,good_name,img")->where($formget)->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'商品搜索结果','value'=>$goodlist));
	}
	function goodlabel(){
		header("content-type:text/html;charset=utf8");
		Vendor("phpqrcode.phpqrcode");
		$action=I("action","shangp");
		$aid=I("aid");
		$good_name=M("goods","sk_")->getFieldById($aid,"good_name");
		if(empty($good_name)){
			$this->ajaxReturn(array('status'=>2,'message'=>'不存在'));
		}
		$link = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=".$this->open_id."|$action|$aid&connect_redirect=1#wechat_redirect";
		$erweima=\QRcode::png ( $link,false,"L",6, 3);
		$font='./statics/ttf/simhei.ttf';
		$fontsize=30;
		$dst = imagecreatefromjpeg('./statics/images/erweima.jpg');
		$dstwidth=imagesx($dst);
		$black = imagecolorallocate($dst, 255, 255, 255);
		$len=utf8_strlen($good_name);
		$a=16;
		$b=270;
		for($i=0;$i<=$len;){
			$box = imagettfbbox($fontsize,0,$font,mb_substr($good_name,$i,$a,'utf8'));
			$box_width = max(abs($box[2] - $box[0]),abs($box[4] - $box[6]));
			$x=ceil(($dstwidth-$box_width)/2);
			$tempstr=mb_substr($good_name,$i,$a,'utf8');
			imagettftext($dst,$fontsize, 0, $x,$b, $black,$font,$tempstr);
			if(utf8_strlen($tempstr)==$a) {
				$i += $a;
				$b += 50;
			}else{
				break;
			}
		}
		imagecopyresized($dst,$erweima,125,485,0,0,390,390,366,366);
		header("content-type:image/png");
		imagejpeg($dst);
		imagedestroy($dst);
		imagedestroy($erweima);
	}
}