<?php

class appealAction extends baseAction {
    //申诉列表
    function index() {
        $appeal = M("appeal");
        //var_dump($appeal);
        import("ORG.Util.Page");
        $where = " 1=1 ";
        
        if ($_POST) {
            $wei_id = $_POST['wei_id'];
            if (!empty($wei_id)) {
                $where .= " and wei_id like '%$wei_id%'";
            }
            $status = isset($_POST['status']) ? $_POST['status'] : '-1';
            if ($status != '-1') {
                $where .= " and  shenhe='$status' ";
            }
        }
        //var_dump($_POST['status']); die;
        //$sql_count = "select count(*) as count from sk_appeal";
        //$count = $appeal->query($sql_count);
        $count = $appeal->where($where)->count();
        $p = new Page($count, 20);
        //$sql = "select * from sk_appeal order by create_time desc ";
        //$data = $appeal->query($sql . " limit " . $p->firstRow . ',' . $p->listRows);
        $data = $appeal->where($where)->order("create_time desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('list', $data);
        
        $this->assign('wei_id', $wei_id);
        $this->assign('status', $status);
        $this->assign('page', $p->show());
        $this->display();
    }

    //修改申诉
    function edit() {
        if (!empty($_GET)) {
            $appeal = M("appeal");
            $id = $_GET['id'];
            $data = $appeal->where(' id =' . $id)->find();
            
            $img=M("appeal_img");
            $imgList=$img->where(" appeal_id= '$id'")->limit('4')->select();
            $this->assign('imglist',$imgList);
//            var_dump($imgList);
//            echo $img->getLastSql();
            $this->assign('data', $data);
        } else {
            $this->error('错误操作');
        }
        $this->display();
    }

    //删除申诉
    function del() {
        if (!isset($_POST['ids']) || empty($_POST['ids'])) {
            $this->error('请选择要删除的数据！');
        }
        $appeal =M('appeal');
        if (isset($_POST['ids']) && is_array($_POST['ids'])) {
            foreach ($_POST['ids'] as $val) {
                $appeal->delete($val);
            }
        } else {
            $id = intval($_POST['ids']);
            if (!empty($id)) {
                $appeal->delete($id);
            }
        }
        $this->success('删除成功！',U("appeal/index"));
    }

    //审核
    function shenhe() {
        if (!empty($_POST)) {
            $appeal = M("appeal");
            $id = $_POST['id'];
            $data['shenhe'] = $_POST['shenhe'];
            $data['modify_time'] = time();
            $result = $appeal->where('id=' . $id)->save($data);
            $this->ajaxReturn($result);
        }
    }
    
   // 修改盟友关系
    function editMemberRerationship(){
        if(!empty($_POST)){
            //修改之前先判断盟友关系
            $id=$_POST['id'];
            $pid=$_POST['pid'];
            $member=M("member");
            $result_p=$member->field('pid')->where("open_id ='$id'")->find();// 查出该openid 的上级
            //echo $member->getLastSql();
            $result_c=$member->field('open_id')->where("pid='$id'")->find();// 查出该上级openid的下级
            //echo $member->getLastSql();
            //exit;
//            var_dump($result_c);
//            var_dump($result_p);
            //var_dump($result_p['pid']);
            //var_dump($result_c['open_id']);exit;
            if(!empty($result_p['pid'])&& !empty($result_c['open_id']) &&$result_p['pid']==$result_c['open_id']){
                $this->error('盟友关系错误');
            }else{
                $data=array();
                $data["pid"]=$pid;
                $result=$member->where("open_id='$id'")->save($data);
                if($result){
                    $this->success('修改成功');
                }else{
                    $this->error("修改失败");
                }
            }
        }
        $this->display();
    }

}
