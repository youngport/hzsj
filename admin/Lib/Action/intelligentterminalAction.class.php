<?php

class intelligentterminalAction extends baseAction
{
	function index()
	{
		$intelligenceModel = D('intelligentterminal');
		
		import("ORG.Util.Page");
		$condition = array();
		$con = array();

		if (!empty($_POST['addtime_start'])) {
			$condition['add_time'] = array('egt', strtotime($_POST['addtime_start']));
			$con['addtime_start'] = $_POST['addtime_start'];
		}
		
		if (!empty($_POST['addtime_end'])) {
			$condition['add_time'] = array('elt', strtotime($_POST['addtime_end']));
			$con['addtime_end'] = $_POST['addtime_end'];
		}
		
		if (!empty($_POST['good_name'])) {
			$condition['good_name'] = array('like','%' .$_POST['good_name'].'%');
			$con['good_name'] = $_POST['good_name'];
		}
		
		$count = $intelligenceModel->where($condition)->count();
		$p = new Page($count, 20);
		$data = $intelligenceModel->alias('ba')->join('left join sk_carousel ca on ba.carousel_id =ca.id')->where($condition)->field('ba.*,ca.carousel1 carousel1,ca.carousel2 carousel2,ca.carousel3 carousel3')->limit($p->firstRow . ',' . $p->listRows)->order(" add_time desc")->select();
		$page = $p->show();
		
		$this->assign('page', $page);
		$this->assign('info',$data);
		$this->assign('con',$con);
		$this->display();
	}
	

	function add()
	{
		if(!empty($_POST)){
			
		 	$intelligenceModel = D('intelligentterminal');
		 	if($intelligenceModel->create()){
		 		
		 		if($intelligenceModel->add()){
		 			
		 			$this->success('添加成功!',U('intelligentterminal/index'));
		 			exit();
		 		}else{
		 			
		 			$this->error('添加失败了!' . $intelligenceModel->error_info);
		 		}
		 		
		 	}else{
		 		
		 		$this->error('添加失败!' . $intelligenceModel->error_info);
		 	}
			
		}
		
		$this->display();
	}
	

	function editor()
	{
		$id = (int)$_REQUEST['id'];
		$intelligenceModel = D('intelligentterminal');
		if(!$id){
			$this->error('编辑的内容不存在!');
		}
		if(!empty($_POST)){
			if($intelligenceModel->create()){
				
				if($intelligenceModel->where(array('id'=>$id))->save() !== false){
					
					$this->success('修改成功!',U('intelligentterminal/index'));
					exit();
				}else{
					
					$this->error('修改失败!'  . $intelligenceModel->error_info);
				}
			}else{
				
				$this->error('修改失败!请重新操作!' . $intelligenceModel->error_info);
			}
			
		}
		
		$data = $intelligenceModel->alias('ba')->join('left join sk_carousel ca on ba.carousel_id =ca.id')->where(array('ba.id'=>$id))->field('ba.*,ca.carousel1 carousel1,ca.carousel2 carousel2,ca.carousel3 carousel3')->find();
		$this->assign('info',$data);
		$this->display();
		
	}

	
	function del() {
		 
		if (!empty($_REQUEST)) {
			
			$intelligenceModel = M('intelligentterminal');
			$ids = $_REQUEST['id'];
	
			//批量删除
			if(is_array($ids)){
				
				$ids = implode(',', $ids);
			}
			
			$data=$intelligenceModel->delete($ids);
			
			if($data){
				$this->success("删除成功", U("intelligentterminal/index"));
				exit();
			}else{
				$this->error("删除失败");
			}
		}
	
	}
    
}
