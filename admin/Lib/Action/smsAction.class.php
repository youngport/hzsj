<?php
set_time_limit(0);

/*
 * 短信管理
 */
class smsAction extends baseAction
{
	public function index()
	{   
        import("@.Common.SMS.Send");
        $s = new Send;  	
		$User = M("member"); 			
		if(isset($_POST['dosubmit'])){
        //模板是否填写
            if (empty($_POST['sms_id'])) {

                $this->error('模板不能为空');
        }
        //微信唯一码是否为空
            if (!empty($_POST['rec_id'])) {           	
            	$map['open_id'] = $_POST['rec_id'];                      
                $phone_tel = $User->where($map)->find();               
        //号码是否存在
            if (empty($phone_tel['phone_tel'])) {

                $this->error('该用户手机号码不存在');                  		
                
                } 
                $s->sendTemplateSMS($phone_tel['phone_tel'],array(),intval($_POST['sms_id']));//手机号码，替换内容数组，模板ID

            }else{
                 $where['phone_tel'] = array("neq","");
            	 //$where['open_id']=array("in",array("oc4cpuE_IwrJflhyYx-QV3yXD3uo","oc4cpuB_JYCrlzY3Ml1O37ENDkUg"));
            	 $phone_tel = $User->where($where)->select();
            	 
            	 foreach ($phone_tel as $key => $value) {
            	 	$to = $value['phone_tel'];
                    $s->sendTemplateSMS($to,array(),intval($_POST['sms_id']));//手机号码，替换内容数组，模板ID
            	 }
            }
			//成功，入库                                                          
			    $sms_mod = D('sms');
			    $data = $sms_mod->create();
			    $data['date'] = time();
                $data['status'] = 1;
			    $result = $sms_mod->add($data);
        
        if(false !== $result){

			$this->success(L('operation_success'),U('sms/index'));

		}else{

			$this->error(L('operation_failure'));
		}
	

	}

			$this->display();
  
	}	

    public function select(){
    	
    	import("ORG.Util.Page");
    	$sms = M("sms"); 
        $map['status'] = 1;                      
        $count = $sms->where($map)->count();
        $p = new Page($count, 20);    	      
        $sms_list = $sms->where($map)->select();
        $page = $p->show();
		$this->assign('page', $page);
		$this->assign('sms_list', $sms_list);		
     	$this->display();
     }
}
?>