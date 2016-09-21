<?php


class feedbackAction extends  baseAction {

    public function  index(){
        $mod = D("feedback");
        $pagesize = 20;
        import("ORG.Util.Page");
        $where = " 1=1 ";
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
        if (!empty($keyword)) {
            $this->assign('keyword', $keyword);
            $where .= " and title like '%$keyword%'";
        }
        if (isset($_GET['time_start']) && trim($_GET['time_start'])) {
            $time_start =strtotime( $_GET['time_start']);
            if($time_start>0) {
                $where .= " AND add_time>='" . $time_start . "'";
                $this->assign('time_start', $_GET['time_start']);
            }
        }
        if (isset($_GET['time_end']) && trim($_GET['time_end'])) {
            $time_end =strtotime($_GET['time_end']);
            if($time_end>0) {
                $where .= " AND add_time<='" . $time_end . "'";
                $this->assign('time_end', $_GET['time_end']);
            }
        }
        $status = isset($_GET['status']) ? intval($_GET['status']) : '-1';
        $this->assign('status', $status);
        if ($status >= 0)
            $where .= " AND status=" . $status;
        $visit_status = isset($_GET['visit_status']) ? intval($_GET['visit_status']) : '-1';
        $this->assign('visit_status', $visit_status);
        if ($visit_status >= 0)
            $where .= " AND visit_status=" . $visit_status;
        $count = $mod->where($where)->count();
        $p = new Page($count, $pagesize);
        $list = $mod->where($where)->order("add_time desc,visit_time desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }
    public  function  edit(){
        if (isset($_POST['dosubmit'])) {
            $info_mod = D('feedback');
            $info_mod->create();
            $admin_info=$_SESSION['admin_info'];
            $info_mod->visit_time=time();
            $info_mod->visit_usrid=$admin_info['id'];
            $info_mod->visit_usrname=$admin_info['user_name'];
            $result_info = $info_mod->save();
            if (false !== $result_info) {
                $this->success('提交反馈信息成功');
            } else {
                $this->error('提交反馈信息失败');
            }
        }
        else {
            $info_mod = M('feedback');
            if (isset($_GET['id'])) {
                $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的链接');
            }
            $user = $info_mod->where('id=' . $id)->find();
            $this->assign('info', $user);
            $this->display();
        }
    }
} 