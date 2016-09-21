<?php
class hycouponModel extends RelationModel
{
	
	protected $_validate = array(
			
    	array('coupon_title','require','优惠券标题不能为空!'),
		array('openid','require','openid不能为空!'),
		array('money','require','总金额不能为空!'),
		array('manjian','require','满减不能为空!'),
		array('per_money','require','面值!'),
		array('start_time','require','开始时间不能为空!'),
		array('end_time','require','结束时间不能为空!'),

	);
	
	public function _before_insert(&$data, $options){
		
		$data['by_user'] = $_SESSION['admin_info']['user_name'];
		$data['start_time'] = strtotime($data['start_time']);
		$data['end_time'] = strtotime($data['end_time']);
		$data['add_time'] = time();
		$data['balance'] = $data['money'];
		
	}
	
	public function _before_update(&$data, $options){
		
		$data['start_time'] = strtotime($data['start_time']);
		$data['end_time'] = strtotime($data['end_time']);
		$data['modify_time'] = time();
		
	}
		
}