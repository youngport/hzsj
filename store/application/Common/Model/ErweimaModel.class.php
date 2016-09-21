<?php
namespace Common\Model;
use Common\Model\CommonModel;
class ErweimaModel extends CommonModel {
	protected $tablePrefix='sk_';
	protected $_validate=array(
		array('id','number','2.ID不存在'),
		array('dianpname','require','3.店铺名称不能为空'),
		array('dianpname','','4.店铺名称已存在',1,'unique'),
		array('dianp_lxfs','require','5.手机号码不能为空'),
		array('dianp_lxfs','/^(13[0-9]|15[0-9]|18[0-9])\d{8}$/','6.手机号码非法'),
		array('dianp_lxfs','','7.手机号码已存在',1,'unique'),
		array('rmac','require','8.路由器MAC不能为空'),
		array('rmac','','9.路由器MAC已存在',1,'unique'),
		array('zd_bianhao','require','10.终端编号不能为空'),
		array('zd_bianhao','','11.终端编号已存在',1,'unique'),
		array('xxdizhi','require','12.地址不能为空')
	);
}