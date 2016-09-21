<?php

/**
 * Description of NewAction
 *
 * @author admin
 */
class NewAction extends Action {

    function index() {

        if ($_POST) {
            $pid = trim($_POST['pid']);
            $id = trim($_POST['openid']);

            $order = M("orders", "sk_");
            $store = M("erweima", 'sk_');
            $member = M("member", "sk_");
            $pop = M("pop", "wei_");
//根据openid获取侯志勇的 mac地址
            $data = $store->field("mac")->where("openid='" . $pid . "'")->find();
            $mac = $data['mac'];
//通过id 去 查找一级盟友，然后把盟友全部转移到侯志勇的账号
            $result = $member->field('open_id,mac_address')->where("pid='" . $id . "'")->select();   //得到一级盟友
           
            $firstArr = array();
            $secArr = array();
            foreach ($result as $key => $value) {
                $firstArr[] = $value['open_id'];
                if (empty($value['mac_address'])) {
                    $secArr[] = $value['open_id'];
                }
            }

//更改一级盟友为侯志勇，有mac地址的  用户表
            if (!empty($firstArr)) {
                $strfirst = implode(',', $firstArr);
//var_dump($strfirst);   exit;
                $map['open_id'] = array('in', $strfirst);
                $res_m = $member->where($map)->setField('pid', $pid);
                echo $member->getLastSql();
                echo "----1<hr/>";
            }

//一级盟友 没有mac地址的补上，有的不做修改
            if (!empty($secArr)) {
                $sec_str = implode(',', $secArr);
                $map_sec['open_id'] = array('in', $sec_str);
                $res_mac = $member->where($map_sec)->setField('mac_address', $mac);
                echo $member->getLastSql();
                echo "----4<hr/>";
            }
            
            //根据一级盟友查出他们原有的订单
            $this->getArr($firstArr, 'orders', $mac); //
            $this->getArr($firstArr, 'pops', $mac);
////------------一级盟友所有盟友------------
////---------------------------------------------
            $data_arr = $this->utcount($result); // 查找一级盟友的所有盟友
////------------一级盟友所有盟友-----------
////----------------------------------------------
            $bb = array();  //查出不带mac地址的openid
            $aa = array(); //查出带mac地址的openid
            foreach ($data_arr as $key => $value) {
                $aa[] = $value['open_id'];
                if (empty($value['mac_address'])) {
                    $bb[] = $value['open_id'];
                }
            }


            if (!empty($bb)) {
                if (is_array($bb)) {
                    $bb_str = implode(',', $bb);
                    $map_bb['open_id'] = array('in', $bb_str);
                    $res_mac = $member->where($map_bb)->setField('mac_address', $mac);  // 修改一级盟友的所有盟友的mac 地址，没有的就补上
                    echo $member->getLastSql();
                } else {
                    $res_mac = $member->where("open_id='" . $bb . "'")->setField('mac_address', $mac);  // 修改一级盟友的所有盟友的mac 地址，没有的就补上
                    echo $member->getLastSql();
                }
                echo "----5<hr/>";
            }

// 修改订单的没有 mac 地址 下级盟友
// 修改订单前需先找出是否带mac地址的，有带mac地址的就不用更新，没有带的就放到侯志勇下面
            if (!empty($aa)) {
                $aa_str = implode(',', $aa);
                $map_aa['buyer_id'] = array('in', $aa_str);
                $data_orders = $order->field('buyer_id,mac_address')->where($map_aa)->select();  // 先查出所有盟友的订单的

                $cc = array();
                foreach ($data_orders as $key => $value) {
                    if ($value['mac_address']) {
                        $cc[] = $value['buyer_id'];
                    }
                }
                if (!empty($cc)) {
                    $cc_str = implode(',', $cc);
                    $map_cc['buyer_id'] = array('in', $cc_str);
                    $res_order = $order->where($map_cc)->setField('mac_address', $mac);  // 修改一级盟友的所有订单的mac 地址，没有的就补上
                    echo $order->getLastSql();
                    echo "----6<hr/>";
                }
            }

//$res1=$order->where("buyer_id='".$id."'")->setField(array('buyer_id', $pid),array('mac_address',$mac));  //修改一级盟友的的订单，全部改为侯志勇的，其他的2,3,....n级按照原来的            
// 修改收益没有mac 地址
//① 先查出一级盟友的所有盟友收益
            if (!empty($aa)) {
                $dd_str = implode(',', $aa);
                $map_dd['openid'] = array('in', $dd_str);
                $data_wei = $pop->field('openid,mac_address')->where($map_dd)->select();
                echo $pop->getLastSql();
                echo "----7<hr/>";
            }


//②循环查出有没有mac地址的
            $ee = array();
            foreach ($data_wei as $key => $value) {
                if ($value['mac_address']) {
                    $ee[] = $value['openid'];
                }
            }
//③ 把没有mac地址的全部归到侯志勇下
            if (!empty($ee)) {
                $ee_str = implode(',', $ee);
                $map_ee['openid'] = array('in', $ee_str);
                $res3 = $pop->where($map_ee)->setField('mac_address', $mac);
            }

            $res_orders0 = $order->where("buyer_id='" . $id . "'")->setField('buyer_id', $pid);  //修改订单的openid
            $res_orders7 = $order->where("buyer_id='" . $pid . "'  and mac_address='' ")->setField('mac_address', $mac);  //修改费雷迪没有mac的订单，加上mac_address

            $res_pop = $pop->where("openid='" . $id . "'")->setField('openid', $pid);  // 修改收益的openid

            $res4 = $pop->where("openid='" . $pid . "' and mac_address='' ")->setField('mac_address', $mac); // 把费雷迪没有mac的收益都加上mac地址
        // 查出 $id 的收益后把该账号删掉
            $data = $member->field("shouyi")->where("open_id='" . $id . "'")->find();  //查出店铺的总收益

            $data_hzy = $member->field("shouyi")->where("open_id='" . $pid . "'")->find();  // 侯志勇的总收益
            echo $data['shouyi'] . "===" . $data_hzy['shouyi'] . "<hr/>";
            $member->where("open_id='" . $pid . "'")->setField('shouyi', $data['shouyi'] + $data_hzy['shouyi']);  // 把收益归到侯志勇下面

            $dd=$member->where("open_id='" . $id . "'")->delete(); // 删除 用户表原来的商家
            ///$dd = $store->where("openid='" . $id . "'")->delete(); // 删除商家表的商家
             // 修改 终端的openid 
            $dd = $store->where("openid='" . $id . "'" )->setField('openid', $pid); 
            if ($dd) {
                $this->success('修改成功', U('New/index'));
            }
        } else {
            $this->display();
        }
    }

    /**
     * 
     * @staticvar array $count
     * @param type $partner
     * @return boolean
     *  
     */
    function utcount($partner) {
        if (!is_array($partner)) {
            return false;
        }
        $member = M('member');
        static $count = array();
        foreach ($partner as $k => $v) {
            $count[] = $v;
            $p = $member->where("pid='" . $v['open_id'] . "'")->field('open_id,mac_address')->select();
            if (!empty($p)) {
                $this->utcount($p);
            }
        }
        return $count;
    }

    /**
     *
     * @param type $arr
     * @param type $str
     * @param type $mac
     * @return boolean
     */
    function getArr($arr, $str, $mac) {

        if (!is_array($arr)) {
            return FALSE;
        }
        if ($str == 'orders') {
            $order = M("orders", "sk_");
            $arr_str = implode(',', $arr);
            $map_arr['buyer_id'] = array('in', $arr_str);
            $data_orders = $order->field('buyer_id,mac_address')->where($map_arr)->select();  // 先查出所有盟友的订单的

            $aa = array();
            foreach ($data_orders as $key => $value) {
                if (empty($value['mac_address'])) {
                    $aa[] = $value['buyer_id'];
                }
            }
//修改没有mac地址的为后智勇的mac地址
            $aa_str = implode(',', $aa);
            $map_aa['buyer_id'] = array('in', $aa_str);
            $res = $order->where($map_aa)->setField('mac_address', $mac); // 修改盟友的订单的mac

            if ($res !== false) {
                return TRUE;
            } else {
                return FALSE;
            }
        } elseif ($str == 'pops') {
            $pop = M("pop", "wei_");
            $dd_str = implode(',', $aa);
            $map_dd['openid'] = array('in', $dd_str);
            $data_wei = $pop->field('openid,mac_address')->where($map_dd)->select();
            $bb = array();
            foreach ($data_wei as $key => $value) {
                if (empty($value['mac_address'])) {
                    $bb[] = $value['openid'];
                }
            }
            $bb_str = implode(',', $bb);
            $map_bb['openid'] = array('in', $bb_str);
            $res = $pop->where($map_bb)->setField('mac_address', $mac); // 修改盟友的订单的mac
            if ($res !== false) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

}
