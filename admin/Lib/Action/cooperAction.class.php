<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/22
 * Time: 15:37
 */

class cooperAction extends  baseAction{

    public function  index(){
        $mod = D("cooper");
        $pagesize = 20;
        import("ORG.Util.Page");
        $where = " 1=1 ";
        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
        if (!empty($keyword)) {
            $this->assign('keyword', $keyword);
            $where .= " and usrname like '%$keyword%'";
        }
        $status = isset($_GET['status']) ? intval($_GET['status']) : '-1';
        $this->assign('status', $status);
        if ($status >= 0)
            $where .= " AND status=" . $status;
        $flag = isset($_GET['flag']) ? intval($_GET['flag']) : '-1';
        $this->assign('flag', $flag);
        if ($flag >= 0)
            $where .= " AND flag=" . $flag;
        $count = $mod->where($where)->count();
        $p = new Page($count, $pagesize);
        $list = $mod->where($where)->order("add_time desc")->limit($p->firstRow . ',' . $p->listRows)->select();
        $page = $p->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    public  function  detail(){
        $info_mod = M('cooper');
        if (isset($_GET['id'])) {
            $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的链接');
        }
        $cooper = $info_mod->where('id=' . $id)->find();
        $this->assign('cooper', $cooper);
        $this->display();
    }
    function delete(){
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            $this->error('请选择要删除的数据！');
        }
        $user_mod = D('cooper');
        if (isset($_POST['id']) && is_array($_POST['id'])) {
            foreach ($_POST['id'] as $val) {
                $user_mod->delete($val);
            }
        } else {
            $id = intval($_POST['id']);
            if (!empty($id)) {
                $user_mod->delete($id);
            }
        }
        $this->success(L('operation_success'));
    }
    public function  status()
    {
        $id = intval($_REQUEST['id']);
        $type = trim($_REQUEST['type']);
        $items_mod = D('cooper');
        $res = $items_mod->where('id=' . $id)->setField($type, array('exp', "(" . $type . "+1)%2"));
        $values = $items_mod->where('id=' . $id)->getField($type);
        $this->ajaxReturn($values[$type]);
    }
} 