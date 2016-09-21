<?php

class startbannerAction extends baseAction
{
	function index()
	{
		$bannerModel = M('start_banner');
		
		import("ORG.Util.Page");
		$condition = array();
		$con = array();
		if (!empty($_POST['banner_name'])) {
			$condition['banner_name'] = array('like', '%' . $_POST['banner_name'] . '%');
			$con['banner_name'] = $_POST['banner_name'];
		}
		if (!empty($_POST['addtime_start'])) {
			$condition['add_time'] = array('egt', strtotime($_POST['addtime_start']));
			$con['addtime_start'] = $_POST['addtime_start'];
		}
		
		if (!empty($_POST['addtime_end'])) {
			$condition['add_time'] = array('elt', strtotime($_POST['addtime_end']));
			$con['addtime_end'] = $_POST['addtime_end'];
		}
		$count = $bannerModel->where($condition)->count();
		$p = new Page($count, 20);
		$data = $bannerModel->where($condition)->limit($p->firstRow . ',' . $p->listRows)->order(" add_time desc")->select();
// 		echo $bannerModel->getLastSql();exit();
		$page = $p->show();
		$this->assign('con',$con);
		$this->assign('page',$page);
		$this->assign('info',$data);
		$this->display();
	}
	

	function add()
	{
		if(!empty($_POST)){
			
		 	$bannerModel = D('startbanner');
// 		 	dump($_POST);
// 		 	dump($bannerModel->create());exit();
		 	if($bannerModel->create()){
		 		
		 		if($bannerModel->add()){
		 			
		 			$this->success('添加成功!',U('startbanner/index'));
		 			exit();
		 		}else{
		 			
		 			$this->error('添加失败了!' . $bannerModel->error_info);
		 		}
		 		
		 	}else{
		 		
		 		$this->error('添加失败!');
		 	}
			
		}
		
		$this->display();
	}
	

	function editor()
	{
		$id = (int)$_REQUEST['id'];
		$bannerModel = D('startbanner');
		if(!$id){
			$this->error('编辑的内容不存在!');
		}
		if(!empty($_POST)){
			if($bannerModel->create()){
				
				if($bannerModel->where(array('id'=>$id))->save() !== false){
					
					$this->success('修改成功!',U('startbanner/index'));
					exit();
				}else{
					
					$this->error('修改失败!' . $bannerModel->error_info);
				}
			}else{
				
				$this->error('修改失败!请重新操作!' . $bannerModel->error());
			}
			
		}
		
		$data = $bannerModel->where(array('id'=>$id))->find();
		$this->assign('info',$data);
		$this->display();
		
	}
	
	function del() {
		 
		if (!empty($_REQUEST)) {
			$bannerModel = M('start_banner');
			$ids = $_REQUEST['id'];
	
			//批量删除
			if(is_array($ids)){
				
				$ids = implode(',', $ids);
			}
			
			$data=$bannerModel->delete($ids);
			if($data){
				$this->success("删除成功", U("startbanner/index"));
				exit();
			}else{
				$this->error("删除失败");
			}
		}
	
	}

	
}
