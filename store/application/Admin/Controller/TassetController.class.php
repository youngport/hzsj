<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class TassetController extends AdminbaseController {
	protected $tasset_model;
	
	function _initialize() {
		parent::_initialize();
		$this->tasset_model = D("Common/tasset");
	}
	function index(){
		$formget=array();
		$order=array("time"=>'desc');
		if(isset($_POST['cate'])&&is_numeric($_POST['cate'])){
			$formget['cate']=I("cate");
		}
		if(isset($_POST['name'])&&$_POST['cate']!=''){
			$formget['name']=array("like","%".I("name")."%");
		}
		$count=$this->tasset_model->where($formget)->count();
		$page = $this->page($count, 20);
		$list=$this->tasset_model->limit($page->firstRow . ',' . $page->listRows)->where($formget)->order($order)->select();
		$this->assign("formget",I("post."));
		$this->assign("list",$list);
		$this->assign("Page", $page->show("Admin"));
		$this->assign("count",$count);
		$this->display();
	}

	function add(){
		cookie("savename",null);
		$this->display();
	}

	function add_post(){
		if(IS_POST){
			if($data=$this->tasset_model->create()){
				$assetinfo=cookie("assetinfo");
				cookie("assetinfo",null);
				if(empty($assetinfo)){
					$this->error("请上传附件!");
				}
				$data=array_merge($data,$assetinfo);
				$data['time']=time();
				if(!empty($_FILES['img'])){
					$upload=new \Think\Upload();
					$upload->maxSize=4194304;
					$upload->rootPath="./";
					$upload->savePath="data/upload/tasset/img/";
					$info=$upload->upload();
					if(!$info) {
						$this->error($upload->getError());
					}else{
						$data['img']="http://".$_SERVER['HTTP_HOST']."/store/".$info['img']['savepath'].$info['img']['savename'];
					}
				}
				if($this->tasset_model->add($data))
					$this->success("添加成功");
				else
					$this->error("添加失败");
			}else
				$this->error($this->tasset_model->getError());
		}
	}
	function cutfile(){
		$post=I("post.");
		if($post['total']<$post['index']){
			echo 'ok';
			cookie("savename",null);
			return;
		}
		$path="./data/upload/tasset/asset/".date("Y-m-d")."/";
		$ext=strrchr($post['name'],".");
		$savename=cookie("savename");
		if(empty($savename)) {
			$savename= "hz" . time() . mt_rand(1, 99999);
			cookie("savename",$savename);
		}
		if(!file_exists($path)){
			mkdir($path);
		}
		$path=$path.$savename.$ext;
		$status=1;
		if(!file_exists($path)) {
			if(!move_uploaded_file($_FILES['data']['tmp_name'], $path)){
				$status=2;
			}
		}else{
			if(file_put_contents($path,file_get_contents($_FILES['data']['tmp_name']),FILE_APPEND)===false){
				$status=2;
			}
		}
		if($post['total']==$post['index']){
			$data=array('name'=>substr($post['name'],0,strrpos($post['name'],".")),'suffix'=>trim($ext,"."),'size'=>$post['size'],"path"=>"http://".$_SERVER['HTTP_HOST']."/store".trim($path,"."));
			cookie('assetinfo',$data);
			cookie("savename",null);
		}
		echo $status==1?$post['index']:'no';
	}

	function delete(){
		$id=I("id");
		$result=$this->tasset_model->delete($id);
		if($result!==false) {
			$this->success("删除成功");
		}else
			$this->error("删除失败");
	}
}