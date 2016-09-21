<?php
class startbannerModel extends RelationModel
{	
	public $error_info = '';
	
	protected $tableName = 'start_banner';
	
	protected $_validate = array(
			
    	array('banner_name','require','启动页名称不能为空!'),
		array('type','require','使用范围不能为空!'),
		array('path','require','启动页图片不能为空!'),

	);
	
	public function _before_insert(&$data, $options){
		
		//上传文件
		if($_FILES['path']['name'] != ''){
			$upload_list = $this->_upload($_FILES['path']);
			//抛出错误信息
		  	if(is_string($upload_list)){
			
				$this->error_info = $upload_list;
				return false;
			
			}
			
			$data['path'] = 'data/startbanner/' . $upload_list['0']['savename'];
			
		}
		
		$data['add_time'] = time();
		$data['add_user'] = $data['add_user'] = $_SESSION['admin_info']['user_name'];
	
  }
  
  	public function _before_update(&$data, $options){
  		
  		if($_FILES['path']['name'] != ''){
  			
  			$upload_list = $this->_upload($_FILES['path']);
  			//如果为string代表是错误信息,正确信息应该是个数组
  			if(is_string($upload_list)){
			
				$this->error_info = $upload_list;
				return false;
			
			}
			
			$path = $this->where('id ='. $data['id'])->find();
			$path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $path['path'] ;
			//删除原来的文件
			@unlink($path);
			
  			$data['path'] = 'data/startbanner/' . $upload_list['0']['savename'];
  			
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
			$upload->savePath = './data/startbanner/';
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
// 			dump($upload->getErrorMsg());exit;
			//捕获上传异常
			return $upload->getErrorMsg();
		} else {
			//取得成功上传的文件信息
			$uploadList = $upload->getUploadFileInfo();
		}
		
		return $uploadList;
	}
	
}