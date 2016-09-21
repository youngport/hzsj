<?php
namespace ApiAdmin\Controller;
use Think\Controller;
class CeshiController extends Controller {
	protected $tasset_model;

	function ceshi()
	{
		if (!empty($_FILES)) {
			$this->upload();
		}
	}

	private function upload($config=array()){
		$configs=array(
			'size'=>4*1024*1024,
			'exts'=>array('jpg', 'gif', 'png', 'jpeg'),
			'savepath'=>"./data/upload/",
			'autoSub'=>true
		);
		$configs=array_merge($configs,$config);
		if($configs['autoSub']===true){
			$configs['savepath'].=date("Y-m-d")."/";
		}
		if(!file_exists($configs['savepath'])){
			mkdir($configs['savepath']);
		}
		foreach($_FILES as $k=>$v){
			$ext=trim(strrchr($v['name'],"."),".");
			if(!in_array($ext,$configs['exts'])){
				$this->ajaxReturn(array('status'=>2,'message'=>'后缀不合法'));
			}
			if($v['size']>$configs['size']){
				$this->ajaxReturn(array('status'=>3,'message'=>'文件太大'));
			}
			$savename=substr(md5("hz".time().mt_rand(1000,9999)),0,9);
			$savename=$configs['savepath'].$savename.".".$ext;
			if(move_uploaded_file($v['tmp_name'],$savename)){
				echo 'ok';
			}
		}
	}

}