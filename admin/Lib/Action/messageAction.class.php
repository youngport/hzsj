<?php

class messageAction extends baseAction
{
	public function index()
	{
		$type=array('message','sms');
		$table=isset($_REQUEST['table'])?trim($_REQUEST['table']):'message';
		if(!in_array($table,$type)){
			$this->error('操作错误');
		}
		$type = M('message');
		if (isset($_GET['keyword']) && trim($_GET['keyword'])) {
			$zd=$table=='message'?'title|abst|intro':'intro';
			$where[$zd]=array("like","%".$this->_get('keyword')."%");
		    $this->assign('keyword',$this->_get('keyword'));
		}
		if(isset($_GET['rec'])&&$_GET['rec']!=''){
			$where['rec']=$this->_get("rec");
			$this->assign('rec',$this->_get("rec"));
		}
		if (isset($_GET['time_start']) && trim($_GET['time_start'])) {
		    $where['create_time'][] =array("EGT",strtotime($this->_get('time_start')));
            $this->assign('time_start',$this->_get('time_start'));
		}
		if (isset($_GET['time_end']) && trim($_GET['time_end'])) {
			$where['create_time'][] =array("ELT",strtotime($this->_get('time_end')));
			$this->assign('time_end',$this->_get('time_end'));
		}
		if($table=='message'){
			$where['type']=1;
			$field='id,title,abst,img,create_time,update_time,rec,status,type';
		}else{
			$where['type']=2;
			$field='id,intro,create_time,update_time,rec,status,type';
		}
		import("ORG.Util.Page");
		$count = $type->where($where)->count();
		$p = new Page($count,20);
		$list = $type->where($where)->limit($p->firstRow.','.$p->listRows)->order('create_time DESC')->field($field)->select();
		$page = $p->show();
		$this->assign('page',$page);
		$this->assign('list',$list);
		$this->assign('table',$table);
		$this->display();
	}

	function edit()
	{
		$type=array('message','sms');
		$table=isset($_REQUEST['table'])?trim($_REQUEST['table']):'message';
		if(!in_array($table,$type)){
			$this->error($table);
		}
		$type = M('message');
		if($this->isPost()){
			$data=$type->create();
			if($table=='message'){
				if($_POST['title']=='')
					$this->error('请填写标题');
				if($_POST['abst']=='')
					$this->error('请填写简介');
				if ($_FILES['img']['name']!='') {
					$upload_list = $this->_upload();
					$data['img']='/data/message/'.$upload_list['0']['savename'];
				}
				$data['title']=htmlspecialchars($data['title']);
				$data['abst']=htmlspecialchars($data['abst']);
			}
			if($_POST['intro']=='')
				$this->error('请填写内容');
			$data['intro']=htmlspecialchars($data['intro']);
			$data['update_time']=time();
			$result = $type->save($data);
			if(false !== $result){
				$this->success(L('operation_success'),U('message/index',array('table'=>$table)));
			}else{
				$this->error(L('operation_failure'));
			}
		}else{
			if(isset($_GET['id']) ){
				$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error(L('please_select'));
			}
			$info = $type->where('id='.$id)->find();
			$this->assign('info',$info);
			$this->assign('table',$table);
			$this->display();
		}


	}

	function add()
	{
		$type=array('message','sms');
		$table=isset($_REQUEST['table'])?trim($_REQUEST['table']):'message';
		if(isset($_POST['dosubmit'])&&in_array($table,$type)){
			$type = M('message');
			$data=$type->create();
			if($table=='message'){
				if($_POST['title']=='')
					$this->error('请填写标题');
				if($_POST['abst']=='')
					$this->error('请填写简介');
				$data['title']=htmlspecialchars($data['title']);
				$data['abst']=htmlspecialchars($data['abst']);
			}
			if($_POST['intro']=='')
				$this->error('请填写内容');
			$data['intro']=htmlspecialchars($data['intro']);
			if ($table=='message'&&$_FILES['img']['name']!='') {
				$upload_list = $this->_upload();
				$data['img'] = '/data/message/'.$upload_list['0']['savename'];
			}
			$data['type']=$table=='message'?1:2;
			$data['create_time']=time();//date('Y-m-d H:i:s',time());
			if($type->add($data)){
				$this->success('添加成功',U('message/index',array('table'=>$table)));
			}else{
				$this->error('添加失败');
			}
		}
			$this->assign('table',$table);
	    	$this->display();
	}

	function delete()
    {
		$type = M('message');
		if((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的资讯！');
		}
		if(isset($_POST['id'])&&is_array($_POST['id'])){
			$ids = implode(',',$_POST['id']);
			$type->delete($ids);
			$this->success(L('operation_success'));
		}
    }

    public function _upload()
    {
    	import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 3292200;
        //$upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
        $upload->savePath = './data/message/';

        $upload->saveRule = uniqid;
        if (!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
        }
        return $uploadList;
    }

    //修改状态
	public function status()
	{
		$type = M('message');
		$id = intval($_REQUEST['id']);
		if(($status = $type->where('id='.$id)->getField('status'))!==false){
			$status=$status==1?0:1;
			$type->where('id='.$id)->setField('status',$status);
			$this->ajaxReturn($status);
		}
	}

}
?>