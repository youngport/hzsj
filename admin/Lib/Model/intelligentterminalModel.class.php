<?php
class intelligentterminalModel extends RelationModel
{
	public $error_info = '';
	
	protected $_validate = array(
			
    	array('good_price','require','商品价格不能为空!'),
		array('good_postage','require','邮费不能为空!'),
		array('good_detail_img','require','商品详情图片不能为空!'),
		array('good_name','require','商品名称不能为空!'),
		array('carousel1','require','轮播图1不能为空!'),
		array('carousel2','require','轮播图2不能为空!'),
		array('carousel3','require','轮播图3不能为空!'),

	);
	
	public function _before_insert(&$data, $options){
		//上传轮播图到sk_carousel表中
		$arr = array();
		if($_FILES['carousel1']['name'] != '' && $_FILES['carousel2']['name'] != '' && $_FILES['carousel3']['name'] != ''){
				
			$upload_list1 = $this->_upload($_FILES['carousel1'],"./data/intelligence/");
			$upload_list2 = $this->_upload($_FILES['carousel2'],'./data/intelligence/');
			$upload_list3 = $this->_upload($_FILES['carousel3'],'./data/intelligence/');
			//抛出异常
			if($upload_list1 === false || $upload_list2 === false || $upload_list3 === false){
				
				return false;
			}
			$str1 = 'data/intelligence/' . $upload_list1['0']['savename'];
			$str2 = 'data/intelligence/' . $upload_list2['0']['savename'];
			$str3 = 'data/intelligence/' . $upload_list3['0']['savename'];
			
			$carouselModel = M();
			$sql = 'insert into sk_carousel(`carousel1`,`carousel2`,`carousel3`) values ("' . $str1 .'","'. $str2 .'","'. $str3 .'")' ;
			$result = $carouselModel->query($sql);
			$sql = "select max(id) id from sk_carousel";
			$result = $carouselModel->query($sql);
			
		}
		
		$data['add_time'] = time();
		$data['add_user'] = $_SESSION['admin_info']['user_name'];
		$data['carousel_id'] = $result[0]['id'];
		//上传文件
		if($_FILES['good_detail_img']['name'] != ''){
			$upload_list = $this->_upload($_FILES['good_detail_img']);
			$data['good_detail_img'] = 'data/intelligence/' . $upload_list['0']['savename'];
			
		}
	
	
  }
  
  public function _before_update(&$data, $options){
  	
  	$img_path = $this->_check('good_detail_img', $data['id'],1);
	$carousel['carousel1'] = $this->_check('carousel1', $data['carousel_id']);
	$carousel['carousel2'] = $this->_check('carousel2', $data['carousel_id']);
	$carousel['carousel3'] = $this->_check('carousel3', $data['carousel_id']);
	if($img_path === false || $carousel['carousel1'] === false || $carousel['carousel2'] === false || $carousel['carousel3'] === false ){
		return false;
	}
	
	foreach ($carousel as $k=>$v){
		
		if(!$v){
			unset($carousel[$k]);
		}
	}
	if(!empty($carousel)){
	  	$carouselModel = M('carousel');
	  	$result = $carouselModel->data($carousel)->where(array('id'=>$data['carousel_id']))->save();
	  	if(!$result){
	  		
	  		return false;
	  	}
	}
	if($img_path){
		
		$data['good_detail_img'] = $img_path;
	}
	
	$data['modify_time'] = time();
  
  }
	
	private function _upload($imgage, $path = '', $isThumb = false) {
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		//设置上传文件大小
		$upload->maxSize = 3292200;
		$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
		$upload->saveRule = uniqid;
		if (!$path) {
			$upload->savePath = './data/intelligence/';
		} else {
			$upload->savePath = $path;
		}
		
		if ($isThumb === true) {
			$upload->thumb = true;
			$upload->imageClassPath = 'ORG.Util.Image';
			//设置前缀，逗号隔开
			$upload->thumbPrefix = 'm_,s_';
			//设置缩略图最大高度,多规格用，隔开
			$upload->thumbMaxWidth = '10240,227';
			$upload->thumbMaxHeight = '10240,234';
			$upload->saveRule = uniqid;
			$upload->thumbRemoveOrigin = true;
		}
		if (!$upload->uploadOne($imgage)) {
			//记录错误信息
			$this->error_info = $upload->getErrorMsg();
			return false;
		} else {
			//取得成功上传的文件信息
			$uploadList = $upload->getUploadFileInfo();
		}
		
		return $uploadList;
	}
	
	private function _check($file,$id,$type=0){
		
		if($_FILES[$file]['name'] != ''){
				
			$upload_list = $this->_upload($_FILES[$file]);
			//抛出错误信息
			if($upload_list === false){
		
				return false;
		
			}
			if($type){
				$path = $this->where(array('id'=>$id))->find();
// 				echo $this->getLastSql();
			}else{
				$path = M('carousel')->where('id ='. $id)->find();
			}
			$path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $path[$file] ;
			//删除原来的文件
			@unlink($path);
				
			return 'data/intelligence/' . $upload_list['0']['savename'];
				
		}else{
			
			return '';
		}
		
	}
	
}