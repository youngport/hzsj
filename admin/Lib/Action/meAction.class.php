<?php

class meAction extends baseAction {

    function index() {
        $sj = M("erweima", 'sk_');
        import("ORG.Util.Page"); // 导入分页类
        $where = " 1=1";
        $keyword=$_POST['keyword'];
        $openid=$_POST['openid'];
        if($keyword){
            $where .=" and dianpname like '%".$keyword."%'";
        }
        
        if($openid){
            $where .=" and openid ='".$openid."'";
        }
        
        $count = $sj->where($where)->count();
        $pagecount = 20;
        $page = new Page($count, $pagecount);
        $show = $page->show();
        $list = $sj->where($where)->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('page', $show);
        $this->assign('list', $list);
        $this->assign("keyword", $keyword);
        $this->assign("openid",$openid);
        $this->display();
    }

    function getMyByPid($pid) {
        static $arr = array();
        $arr[] = $pid;
        $member = M("member", "sk_");
        $p = $member->field("open_id")->where("pid='$pid'and pid !=''")->select();
        echo $member->getLastSql();
        echo "<hr/>";
        foreach ($p as $key => $val) {
            $arr[] = $val['open_id'];
            //var_dump($arr);
            if (!empty($p)) {
                $this->getMyByPid($val['open_id']);
            }
        }
        return $arr;
    }

    function edit() {
        $sj = M("erweima", 'sk_');
        $id = $_GET['id'];
        $data = $sj->field('mac,openid')->where("id ='$id'")->find();
        $res = $this->bb($data['openid'], $data['mac']);
        if ($res) {
            $this->success("修改成功", U("me/index"));
        }
    }

    /**
     * 
     * @param type $pid
     * @param type $mac
     * 修改
     */
    function bb($pid, $mac) {
        $arr = $this->getMyByPid($pid);
        $member = M("member", "sk_"); // 用户
        $order = M("orders", "sk_");
        $pop = M("pop", "wei_");
        if (!empty($arr)) {
            $str = implode(",", $arr);
            $map['open_id'] = array('in', $str);
            $res = $member->where($map)->setField("mac_address", $mac);
            $map_order['buyer_id'] = array('in', $str);
            $res_order = $order->where($map_order)->setField("mac_address", $mac);
            $map_pop['openid'] = array('in', $str);
            $res_pop = $pop->where($map_pop)->setField("mac_address", $mac);

            return 1;
        }
    }

}

?>