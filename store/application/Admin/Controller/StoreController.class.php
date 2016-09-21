<?php

// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class StoreController extends AdminbaseController {

    protected $store_model;
    protected $role = 0;
    protected $info;
    protected $auth = array(2, 5); //用户归属组检查
    protected $ca = array("index"); //权限不检查
    protected $open_id = '';
    protected $id = 0;

    function _initialize() {
        parent::_initialize();
        $this->store_model = M("erweima", "sk_");
        $this->role = M("role_user")->where(array("user_id" => get_current_admin_id(), 'role_id' => array("in", $this->auth)))->count(); //假如检测到则启动检查
        $this->sid = I("sid", 0, "intval");
        $this->open_id = $this->store_model->getFieldById($this->sid, "openid");
        if ($this->role > 0) {
            $this->info = M("role_store")->join("left join sk_erweima on id=sid")->where("uid=" . get_current_admin_id())->getField("id", true); //如果是用户组的话就统计出他的终端,下面的方法在访问前会检查此终端是否在这组数据里,不在你懂的
            if (empty($this->info)) {
                $this->info = array("");
            }
            if (!in_array(ACTION_NAME, $this->ca)) {//每次调用方法都会检测
                if (!in_array($this->sid, $this->info)) {//防止非法访问其他终端
                    $this->error("非法访问");
                }
            }
        }
    }

    function index() {
        $formget = array();
        if ($this->role > 0)
            $formget['id'] = array("in", $this->info);
        if (isset($_POST['shenhe']) && is_numeric($_POST['shenhe']))
            $formget['shenhe'] = I("shenhe");
        if (isset($_POST['bianhao']) && $_POST['bianhao'] != '')
            $formget['bianhao'] = I("bianhao");
        if (isset($_POST['name']) && $_POST['name'] != '')
            $formget['dianpname'] = array("like", "%" . I("name") . "%");
        if (isset($_POST['order']) && $_POST['order'] != '')
            $order = array(I("order") => 'desc');
        else
            $order = array("id" => 'desc');
        $count = $this->store_model->where($formget)->count();
        $page = $this->page($count, 20);
        $list = $this->store_model->limit($page->firstRow . ',' . $page->listRows)->field("id,bianhao,openid,dianpname,dianp_img,shenhe,(select sum(pop) from wei_pop where openid=sk_erweima.openid) pop")->where($formget)->order($order)->select();

        $this->assign("formget", I("post."));
        $this->assign("list", $list);
        $this->assign("Page", $page->show("Admin"));
        $this->assign("count", $count);
        $this->display();
    }

    //店铺信息
    function info() {
        $info = $this->store_model->join("sk_member b on sk_erweima.openid=b.open_id")->field("id,wei_nickname,bianhao,dianpname,sk_erweima.dizhi,xxdizhi,weixin_account,dianp_img,dianp_img2,shenhe")->find($this->sid);
        $this->assign("info", $info);
        $this->display();
    }

    //盟友
    function partner() {
        $user_model = M("member", "sk_");
        $formget['pid'] = $this->open_id;
        $info = utcount($formget['pid'], array(), array("open_id", "jointag"), 1); //在common公用控制器里的common文件里的function文件
        $info['now']['mper'] = round(($info['now']['zmcount'] / $info['now']['zcount']) * 100);
        $info['total']['mper'] = round(($info['total']['zmcount'] / $info['total']['zcount']) * 100);
        $info['dianpname'] = $this->store_model->getFieldByOpenid($formget['pid'], "dianpname");
        if (isset($_POST['nickname']) && $_POST['nickname'] != '')
            $formget['wei_nickname'] = array("like", "%" . I("nickname") . "%");
        if (isset($_POST['status']) && is_numeric($_POST['status']))
            $formget['jointag'] = I("status");
        if (isset($_POST['add_start_time']) && $_POST['add_start_time'] != '')
            $formget['login_time'][] = array("EGT", I("add_start_time"));
        if (isset($_POST['add_end_time']) && $_POST['add_end_time'] != '')
            $formget['login_time'][] = array("ELT", I("add_end_time"));
        if (isset($_POST['start_time']) && $_POST['start_time'] != '')
            $formget['last_login'][] = array("EGT", strtotime(I("start_time")));
        if (isset($_POST['end_time']) && $_POST['end_time'] != '')
            $formget['last_login'][] = array("ELT", strtotime(I("end_time")));
        if (isset($_POST['order']) && $_POST['order'] != '')
            $order = array(I("order") => 'desc');
        else
            $order = array("login_time" => 'desc');
        $count = $user_model->where($formget)->count();
        $page = $this->page($count, 20);
        $list = $user_model->limit($page->firstRow . ',' . $page->listRows)->where($formget)->field("wei_nickname,(select count(*) from sk_member a where a.pid=sk_member.open_id) pidnum,login_time,last_login,jointag")->order($order)->select();
        $this->assign("formget", I("post."));
        $this->assign("info", $info);
        $this->assign("list", $list);
        $this->assign("Page", $page->show("Admin"));
        $this->assign("count", $count);
        $this->assign("sid", $this->sid);
        $this->display();
    }

    //收益
    function income() {
        $pop_model = M("pop", "wei_");
        $formget['openid'] = $this->open_id;
        if (isset($_POST['nickname']) && $_POST['nickname'] != '')
            $formget['wei_nickname'] = array("like", "%" . I("nickname") . "%");
        if (isset($_POST['order_sn']) && $_POST['order_sn'] != '')
            $formget['wei_pop.order_sn'] = I('order_sn');
        if (isset($_POST['start_time']) && $_POST['start_time'] != '')
            $formget['shijianc'][] = array("EGT", strtotime(I("start_time")));
        if (isset($_POST['end_time']) && $_POST['end_time'] != '')
            $formget['shijianc'][] = array("ELT", strtotime(I("end_time")));
        if (isset($_POST['order']) && $_POST['order'] != '')
            $order = array(I("order") => 'desc');
        else
            $order = array("shijianc" => 'desc');
        $count = $pop_model->where($formget)->count();
        $page = $this->page($count, 20);
        $list = $pop_model->join("left join sk_orders b on wei_pop.order_sn=b.order_sn")->join("left join sk_member c on b.buyer_id=c.open_id")->field("b.order_sn,c.wei_nickname,b.order_amount,wei_pop.pop,shijianc")->limit($page->firstRow . ',' . $page->listRows)->where($formget)->order($order)->select();

        $group = M("role_user")->field("count(*) count,sum(if(role_id=2,1,0)) count2")->where(array("user_id" => get_current_admin_id()))->find();
        if ($group['count'] > $group['count2']) {
            $this->assign("groups", 1); //代理人查看自己的收益(商家不可看)
        }
        $this->assign("formget", I("post."));
        $this->assign("list", $list);
        $this->assign("Page", $page->show("Admin"));
        $this->assign("count", $count);
        $this->assign("sid", $this->sid);
        $this->assign("open_id", $this->open_id);
        $this->display();
    }

    //拆分现金券为优惠券发放
    function cfcoupon(){        
                $map['sendid'] = $_GET['sendid'];
                $map['end_time']=array("LT",time());
                $arr = M("erweima","sk_")->field("openid")->where(array('id'=>$_GET['sendid']))->find();  
                $sid=$this->sid;
                $formget['open_id']=$this->open_id;
                $formget['status']=1;
                $formget['money']=array("EGT",0);
                $formget['start_time']=array("ELT",time());
                $formget['end_time']=array("EGT",time());
                $mcou=M("mcoupon","sk_")->field("id,money")->where($formget)->select();
                //查收属于这个店铺拆分出的所有现金券,并且已经过去和没使用的金额总和
                $list=M("coupon","sk_")->field("sum(js) count")->where($map)->select();
                $cou['money'] = $list[0]['count']+$mcou[0]['money'];
                //返回店铺的现金券
                $res= M("mcoupon","sk_")->where(array('open_id'=>$arr['openid']))->save($cou);
                //删除这种已经过期的。
                if ($res) {
                    M("coupon","sk_")->where($map)->delete();
                }              
                $mcoupon=M("mcoupon","sk_")->field("id,money")->where($formget)->select();
                $partner=M("member","sk_")->where(array('pid'=>$this->open_id))->select();
                $this->assign("mcoupon",$mcoupon);
                $this->assign("partner",$partner);
                $this->assign("sid",$sid);
                $this->display();
    }

    //拆分现金券为优惠券发放提交
    function cfcoupon_post() {
        if (IS_POST) {
            $mcoupon = M("mcoupon", "sk_");
            $mid = I('mcoupon');
            $coupon = M("coupon", "sk_");
            $rec = I("rec");
            $mdata = $mcoupon->where(array('id' => $mid, 'openid' => $this->open_id))->find();
            if (!empty($mdata)) {
                $coupon->sendid = $this->sid;
                if (M("member", "sk_")->where(array("pid" => $this->open_id, "open_id" => $rec))->count() <= 0) {
                    $this->error("用户不存在或不是您下级");
                }
                $coupon->rec = $rec;
                $coupon->xz = I("xz", 0, "intval");
                $coupon->js = I("js", 0, "intval");
                if ($coupon->js == 0 || $coupon->js > $mdata['money'] || $coupon->xz <= $coupon->js) {
                    $this->error("金额为0或超出限制金额");
                }
                $coupon->start_time = strtotime(I("start_time"));
                $coupon->end_time = strtotime(I("end_time"));
                if ($coupon->start_time == '' || $coupon->end_time == '') {
                    $this->error("时间设置错误");
                }
                $coupon->sn = substr(md5("hz" . time()), 0, 5);
                $coupon->add_time = time();
                if ($coupon->add() !== false) {
                    $this->success("发送成功");
                    $mdata['money']-=I('js');
                    $mcoupon->save($mdata);
                }
            } else {
                $this->error("现金券不存在");
            }
        }
    }

    //店铺路由器统计
    function router_count() {
        $info = $this->store_model->field("dianpname,rmac")->find($this->sid);
        import('@.lib.Router');
        $router = new \router();
        $time1['startTime'] = date("Y-m-d", strtotime("-1 day")); //昨日
        $time1['endTime'] = date("Y-m-d H:i:s", strtotime($time1['startTime'] . " +1 day") - 1);
        $time2['startTime'] = date("Y-m-d H:i:s", strtotime("-1 week Monday")); //这个周
        $time2['endTime'] = date("Y-m-d H:i:s", strtotime("+0 week Monday") - 1);
        $time3['startTime'] = date("Y-m"); //这个月
        $time3['endTime'] = date('Y-m-d', strtotime(date('Y-m-01') . ' +1 month -1 day'));
        $time4['startTime'] = date("Y-m-d H:i:s", strtotime("2016-1-1")); //总
        $time4['endTime'] = date("Y-m-d H:i:s", strtotime("+1 day") - 1);
        $post = array("apMac" => $info['rmac'], "Timeslot" => array($time1, $time2, $time3, $time4));
        $post = json_encode($post);
        $data['all'] = $router->GetAllVistors($post);
        $data['old'] = $router->GetOldVistors($post);
        $data['new'] = $router->GetNewVistors($post);
        $this->assign("info", $info);
        $this->assign("data", $data);
        $this->display();
    }

    //订单统计
    function order_count() {
        if (isset($_POST['start_time']) && $_POST['start_time'] != '')
            $time[] = array("EGT", strtotime(I("start_time")));
        else
            $time[] = array("EGT", strtotime("-1 week Monday"));
        if (isset($_POST['end_time']) && $_POST['end_time'] != '')
            $time[] = array("ElT", strtotime(I("end_time")));
        else
            $time[] = array("ElT", strtotime("+0 week Monday") - 1);
        $formget['add_time|pay_time'] = $time;

        $orderinfo = $this->otcount_days($this->open_id, $formget); //在common公用控制器里的common文件里的function文件

        $total = $orderinfo['total'];
        unset($orderinfo['total']);
        $chart = array();
        foreach ($orderinfo as $k => $v) {
            $chart['key'][] = $k;
            $chart['xd'][] = $v['xd'];
            $chart['fd'][] = $v['fd'];
        }
        $chart = json_encode($chart);
        $this->assign("chart", $chart);
        $this->assign("info", $total);
        $this->assign("formget", I("post."));
        $this->assign("sid", $this->sid);
        $this->display();
    }

    /**
     * 订单统计,按天计算
     * @param $openid 微信ID
     * @param array $where 对下级盟友的订单条件筛选
     */
    function otcount_days($openid, $where = array()) {
        if (empty($openid))
            return false;
        $member = M("member", "sk_");
        $orders = M("orders", "sk_");
        static $count = array('total' => array("xd" => 0, "fd" => 0, "xdmoney" => 0, "fdmoney" => 0));
        $day = round(($where['add_time|pay_time'][1][1] - $where['add_time|pay_time'][0][1]) / 86400);
        $stime = $where['add_time|pay_time'][0][1];
        for ($i = 0; $i <= $day; $i++) {
            $date = date("Y-m-d", $stime);
            if (!array_key_exists($date, $count)) {
                $stime += 86400;
                $count[$date] = array("xd" => 0, "fd" => 0, "xdmoney" => 0, "fdmoney" => 0);
            }
        }
        $partner = $member->where("pid='$openid'")->getField("open_id", true);
        if (empty($partner))
            return $count;
        $where['buyer_id'] = array("in", $partner);
        $result = $orders->field("status,add_time,pay_time,order_amount")->where($where)->select();
        foreach ($result as $v) {
            if (in_array($v['status'], array(1, 2, 3, 4))) {
                $date = date("Y-m-d", $v['pay_time']);
                $count[$date]['fd'] ++;
                $count['total']['fd'] ++;
                $count[$date]['fdmoney']+=$v['order_amount'];
                $count['total']['fdmoney']+=$v['order_amount'];
            }
            $date = date("Y-m-d", $v['add_time']);
            if (array_key_exists($date, $count)) {
                $count[$date]['xd'] ++;
                $count['total']['xd'] ++;
                $count[$date]['xdmoney'] += $v['order_amount'];
                $count['total']['xdmoney'] += $v['order_amount'];
            }
        }
        foreach ($partner as $v) {
            if ($member->where("pid='$v'")->count() > 0)
                $this->otcount_days($v, $where);
        }
        return $count;
    }

    //盟友统计
    function partner_count() {
        $formget = array();
        if (isset($_POST['start_time']) && $_POST['start_time'] != '')
            $formget['login_time'][] = array("EGT", date("Y-m-d H:i:s", strtotime(I("start_time"))));
        else
            $formget['login_time'][] = array("EGT", date("Y-m-d H:i:s", strtotime("-1 week Monday")));
        if (isset($_POST['end_time']) && $_POST['end_time'] != '')
            $formget['login_time'][] = array("ElT", date("Y-m-d H:i:s", strtotime(I("end_time"))));
        else
            $formget['login_time'][] = array("ElT", date("Y-m-d H:i:s", strtotime("+0 week Monday") - 1));
        $orderinfo = utcount_days($this->open_id, $formget, 1); //在common公用控制器里的common文件里的function文件
        $count['total'] = $orderinfo['total'];
        $count['total']['mper'] = round(($count['total']['zmcount'] / $count['total']['zcount']) * 100);
        unset($orderinfo['total']);
        $count['now'] = $orderinfo['now'];
        $count['now']['mper'] = round(($count['now']['nmcount'] / $count['now']['ncount']) * 100);
        unset($orderinfo['now']);
        $chart = array();
        foreach ($orderinfo as $k => $v) {
            $chart['key'][] = $k;
            $chart['zcount'][] = $v['zcount'];
            $chart['zmcount'][] = $v['zmcount'];
            $chart['ncount'][] = $v['ncount'];
            $chart['nmcount'][] = $v['nmcount'];
        }
        $chart = json_encode($chart);
        $this->assign("chart", $chart);
        $this->assign("info", $count);
        $this->assign("formget", I("post."));
        $this->assign("sid", $this->sid);
        $this->display();
    }

    function action_count() {
        $formget = array();
        if (isset($_POST['start_time']) && $_POST['start_time'] != '')
            $formget['time'][] = array("EGT", strtotime(I("start_time")));
        else
            $formget['time'][] = array("EGT", strtotime("-1 week Monday"));
        if (isset($_POST['end_time']) && $_POST['end_time'] != '')
            $formget['time'][] = array("ElT", strtotime(I("end_time")));
        else
            $formget['time'][] = array("ElT", strtotime("+0 week Monday") - 1);
        $data = atcount($this->open_id, array(), $formget); //在common公用控制器里的common文件里的function文件
        $i = 1;
        $chart = array("key" => array(0), "count" => array(0));
        if (!empty($data)) {
            $chart = array();
            foreach ($data as $k => $v) {
                $chart['key'][] = "TOP" . $i;
                $chart['count'][] = $v['count'];
                unset($data[$k]);
                $data["TOP" . $i] = $v;
                $i++;
            }
        }
        $chart = json_encode($chart);
        $this->assign("chart", $chart);
        $this->assign("data", $data);
        $this->assign("formget", I("post."));
        $this->assign("sid", $this->sid);
        $this->display();
    }

    function comment() {
        $dppinglun = M("dppinglun", "sk_");
        $formget['dianpid'] = $this->sid;
        $formget['sk_dppinglun.pid'] = 0;
        $order['shijian'] = 'desc';
        if (isset($_POST['wei_nickname']) && $_POST['wei_nickname'] != '') {
            $formget['wei_nickname'] = I("wei_nickname");
        }
        if (isset($_POST['neirong']) && $_POST['neirong'] != '') {
            $formget['neirong'] = array("like", "%" . I("neirong") . "%");
        }
        $count = $dppinglun->join("sk_member on open_id=openid")->where($formget)->count();
        $page = $this->page($count, 20);
        $list = $dppinglun->field("id,neirong,wei_nickname,shijian,zan,is_jx,jiancha")->join("sk_member on open_id=openid")->where($formget)->order($order)->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign("formget", I("post."));
        $this->assign("Page", $page->show("Admin"));
        $this->assign("count", $count);
        $this->assign('list', $list);
        $this->assign("dppinglun", $dppinglun);
        $this->assign("sid", $this->sid);
        $this->display();
    }

    function is_jx() {
        $dppinglun = M('dppinglun', 'sk_');
        $formget['id'] = I("cid");
        $formget['dianpid'] = $this->sid;
        $cid = $dppinglun->field("id,is_jx")->where($formget)->find();
        if (!empty($cid)) {
            $result = $dppinglun->where($formget)->setField(array('is_jx' => ($cid['is_jx'] == 1 ? 0 : 1)));
            if ($result !== false) {
                $this->ajaxReturn(array('status' => 1, 'message' => '修改成功'));
            } else {
                $this->ajaxReturn(array('status' => 3, 'message' => '修改失败'));
            }
        } else {
            $this->ajaxReturn(array('status' => 98, 'message' => '数据对象错误'));
        }
    }

    function comment_reply() {
        $dppinglun = M('dppinglun', 'sk_');
        $formget['id'] = intval(I("cid"));
        $formget['dianpid'] = $this->sid;
        $count = $dppinglun->field("id,is_jx")->where($formget)->count();
        if ($count > 0) {
            $data['pid'] = $formget['id'];
            $data['dianpid'] = $this->sid;
            $data['openid'] = $this->store_model->getFieldById($this->sid, "openid");
            $data['neirong'] = I("reply");
            $data['jiancha'] = 1;
            $data['shijian'] = time();
            if ($dppinglun->add($data)) {
                $this->ajaxReturn(array('status' => 1, 'message' => '发送成功'));
            } else {
                $this->ajaxReturn(array('status' => 2, 'message' => '发送失败'));
            }
        } else {
            $this->ajaxReturn(array('status' => 3, 'message' => 'ID非法'));
        }
    }

    /*     * *
     * 店铺统计
     * Store statistics
     */

    function statistics() {
        $formget = " 1=1 ";
        $time = '';
        $dianpuName = I("post.dianpuName");
        $startTime = I("post.start_time");
        $endTime = I("post.end_time");
        if (!empty($dianpuName)) {
            $formget .= " and dianpname like'%" . I("post.dianpuName") . "%'";
        }
        if (!empty($startTime)) {
            $time .= " and sk_member.join_time>='" . I("post.start_time") . "'";
        }
        if ($endTime) {
            $time .= " and sk_member.join_time<='" . I("post.end_time") . "'";
        }
        $order = " join_time desc";
        $pagecount = $this->store_model->where($formget)->join('sk_member on sk_member.open_id = sk_erweima.openid')->count();
        $page = $this->page($pagecount, 20);

        $list = $this->store_model->limit($page->firstRow . ',' . $page->listRows)->field("openid,dianpname ,(SELECT join_time FROM sk_member WHERE sk_member.open_id=sk_erweima.openid  " . $time . ") as join_time,(select count(*) FROM sk_member where sk_member.pid=sk_erweima.openid) as yijimeng ,(select count(*) FROM sk_member where sk_member.pid=sk_erweima.openid AND jointag='1') as yijihuiyuan,(select sum(pop) from wei_pop where wei_pop.openid=sk_erweima.openid ) as income ")->where($formget)->order($order)->select();
        $member = M("member", "sk_");
        foreach ($list as $k => $v) {
            $pid = $v['openid'];
            $cc = $member->where("pid='$pid'")->field('open_id,jointag')->select();

            $count = $this->utcount($cc); // 盟友总数和总会员数的数组
            $ordercount = $this->getOrder_count($pid); //订单数
            $income = $this->getImcom($pid); //该店收益
            $list[$k]['mcount'] = $count['count']; //盟友总数
            $list[$k]['hycount'] = $count['mcount']; //会员总数
            $list[$k]['ordercount'] = $ordercount['xd'] ;  // 下单数 （所有盟友）=付单数+未付单数
            $list[$k]['ordermoney'] = $ordercount['xdmoney'] ; //下单金额（所有盟友）=付单金额+ 未付单金额
            $list[$k]['paymoney'] = $ordercount['fdmoney']; //付款金额（所有盟友）
            $list[$k]['income'] = $income[0]['sum_pop']; // 店铺收益
        }

        if (!empty($_POST['daochu'])) {
            //导入PHPExcle插件
            import("Common.Org.PHPExcel");
            import("Common.Org.PHPExcel.Writer.Excel5");
            import("Common.Org.PHPExcel.IOFactory.php");
            $fileName = "店铺统计";
            $this->getExcel($fileName, $list); // 把数据 导向出到Excle 表格
        }
        $this->assign("list", $list);
        $this->assign("formget", I());
        $this->assign("Page", $page->show("Admin"));
        $this->assign("count", $pagecount);
        $this->display();
    }

    //这个递归无法用到数组里面，可参考uscount
    /*     * *
     * 计算总盟友和总会员
     */
    function utcount($partner) {
        if (!is_array($partner))
            return false;
        $member = M('member', 'sk_');
        static $count = array();
        foreach ($partner as $k => $v) {
            $count['count'] ++;
            if ($v['jointag'] == 1) {
                $count['mcount'] ++;
            }
            $p = $member->where("pid='" . $v['open_id'] . "'")->field('open_id,jointag')->select();
            if (!empty($p))
                $this->utcount($p);
        }
        return $count;
    }

    /*     * *
     * 根据店铺的openid 获取店铺的一级盟友
     */

//    function getyijiyh($pid){
//        if(empty($pid)){
//            return FALSE;
//        }
//        $member = M('member', 'sk_');
//        $data=$member->field(" count(*) as yhcount")->where(" pid='".$pid."'");
//        return $data[0]['yhcount'];
//    }

    /*     * *
     * @param $pid
     * @param $formget
     * @return mixed
     * 订单数 和订单金额
     */
    function getOrder_count($pid) {
        $orderinfo = $this->otcount($pid, $formget);
        $total = $orderinfo['total'];
        return $total;
    }

    /**
     * 订单总统计
     * @param $openid 微信ID
     * @param array $where 对下级盟友的订单条件筛选
     * @param array $field 需要的字段,根据$type
     * @param string $type 默认count统计数量,info则配合$field来获取用户信息组
     */
    function otcount($openid, $where = array(), $field = array("status", "add_time", "order_amount"), $type = 'count') {
        if (empty($openid)) {
            return false;
        }
        $member = M("member", "sk_");
        $orders = M("orders", "sk_");
        static $count = array('total' => array("xd" => 0, "fd" => 0, "xdmoney" => 0, "fdmoney" => 0));
        static $info = array();
        $partner = $member->where("pid='$openid'")->getField("open_id", true);
        if (empty($partner)) {
            return $count;
        }
        $where['buyer_id'] = array("in", $partner);
        $result = $orders->field($field)->where($where)->select();
        foreach ($result as $v) {
            if ($type == 'count') {
                if (in_array($v['status'], array(1, 2, 3, 4))) {
                    $count['total']['fd'] ++;
                    $count['total']['fdmoney'] += $v['order_amount'];
                }
                $count['total']['xd'] ++;
                $count['total']['xdmoney'] += $v['order_amount'];
            } elseif ($type == 'info') {
                $v['wei_nickname'] = $member->getFieldByOpen_id($v['buyer_id'], 'wei_nickname');
                $info[] = $v;
            }
        }
        foreach ($partner as $v) {
            if ($member->where("pid='$v'")->count() > 0)
                $this->otcount($v, $where, $field, $type);
        }
        return $$type;
    }

    /*     * *
     * @param $pid
     * @return mixed
     * 商家收益
     */

    function getImcom($pid) {
        $pop_model = M("pop", "wei_");
        $list = $pop_model->field("sum(pop) as sum_pop")->where("openid='$pid'")->select();
        return $list;
    }

    /*     * *
     * @param $fileName
     * @param $data
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * 导出表格
     */

    function getExcel($fileName, $data) {
        //对数据进行检验
        if (empty($data) || !is_array($data)) {
            die("data must be a array");
        }
        $date = date("Y_m_d", time());
        $fileName.="_{$date}.xls";
        //创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getActiveSheet()->getStyle()->getFont()->setName('微软雅黑'); //设置字体
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25); //设置默认高度

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('20'); //设置列宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('20'); //设置列宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('20'); //设置列宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('20'); //设置列宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('20'); //设置列宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('20'); //设置列宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('20'); //设置列宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('20'); //设置列宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('20'); //设置列宽

        $fileName = iconv("utf-8", "gb2312", $fileName);
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '店铺名称')->setCellValue('B1', '总盟友')
                ->setCellValue('C1', '总会员')->setCellValue('D1', '一级盟友')
                ->setCellValue('E1', '一级会员')->setCellValue('F1', '订单数')
                ->setCellValue('G1', '下单金额')->setCellValue('H1', '已付金额')
                ->setCellValue('I1', '收益');

        for ($i = 0; $i < count($data); $i++) {
            $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 2), $data[$i]['dianpname']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 2), $data[$i]['mcount']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 2), $data[$i]['hycount']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 2), $data[$i]['yijimeng']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 2), $data[$i]['yijihuiyuan']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 2), $data[$i]['ordercount']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 2), $data[$i]['ordermoney']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($i + 2), $data[$i]['paymoney']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('I' . ($i + 2), $data[$i]['income']);
        }

        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->setTitle("店铺统计");
        //$objPHPExcel->getActiveSheet()->fromArray($data);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }

}
