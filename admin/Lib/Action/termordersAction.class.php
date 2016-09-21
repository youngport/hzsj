<?php

/**
 * Description of TermordersController
 *
 * @author Administrator
 */
class termordersAction extends Action {

    function index() {
        $where = "1=1";
        $model = M("term_orders");
        import("ORG.Util.Page");
        $count = $model->where($where)->count();
        $p = new Page($count, 20);
        $order_str = " id desc";
        $list = $model->where($where)->limit($p->firstRow . ',' . $p->listRows)->order($order_str)->select();
        $page = $p->show();
        $this->assign('page', $page);
        $this->assign("list", $list);
        $this->display();
    }

    function edit() {
        $id = $_GET['id'];
        $model = M("term_orders");
        $data=$model->where("id='".$id."'")->find();
        $this->assign("info",$data);
        
        $gid=$data['good_id'];
        $good_model=M("intelligentterminal");
        $good=$good_model->where("id='".$gid."'")->select();
       
        $this->assign("goods", $good);
        $this->display();
    }
    
    function editkd() {
        
    }

    function detail() {
        $this->display();
    }

}
