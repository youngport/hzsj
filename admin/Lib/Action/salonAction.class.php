<?php
class salonAction extends baseAction {
    public function index(){
        $salon=M("salon");
        $where=array();
        if(isset($_GET['name'])&&$_GET['name']!=''){
            $where['name']=array("like","%".trim($this->_get("name"))."%");
        }
        if(isset($_GET['mobile'])&&$_GET['mobile']!=''){
            $where['mobile']=array("like","%".trim($this->_get("mobile"))."%");
        }
        if(isset($_GET['status'])&&$_GET['status']!=''){
            $where['status']=$this->_get("status",0,"intval");
        }
        if(isset($_GET['start_time'])&&$_GET['start_time']!=''){
            $where['addtime'][]=array("EGT",strtotime($this->_get("start_time")));
        }
        if(isset($_GET['end_time'])&&$_GET['end_time']!=''){
            $where['addtime'][]=array("ELT",strtotime($this->_get("end_time")));
        }
        $list=$salon->where($where)->select();
        $this->assign("list",$list);
        $this->assign("get",$_GET);
        $this->display();
    }
    public function delete()
    {
        $salon=M("salon");
        if(isset($_POST['id'])&&is_array($_POST['id'])&&!empty($_POST['id'])){
            $ids = implode(',',$_POST['id']);
            $salon->delete($ids);
            $this->success("删除成功");
        }else{
            $this->error('请选择要删除的信息！');
        }
    }
    public function status(){
        $salon=M("salon");
        if($_POST){
            $data['id']=intval($_POST['id']);
            $data['status']=intval($_POST['status']);
            if($data['id']!=0){
                $result=$salon->save($data);
                if($result!==false)
                    echo 1;
            }else{
                $this->error('ID非法');
            }
        }else{
            $this->error('请选择要删除的信息！');
        }
    }
}