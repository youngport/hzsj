
<?php
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */


class REST {
    private $voiceinfo=array(
        'AccountSid'=>'8a48b55152f73add0152f74db90a0032',//主帐号,对应开官网发者主账号下的 ACCOUNT SID
        'AccountToken'=>'0295a66afe07483e9242027856648e01',//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
        'AppId'=>'8a48b551537e986901537eb131cb0024',//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID,在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
        'ServerIP'=>'sandboxapp.cloopen.com',//请求地址:沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com;生产环境（用户应用上线使用）：app.cloopen.com
        'ServerPort'=>'8883',//请求端口，生产环境和沙盒环境一致
        'SoftVersion'=>'2013-12-26'//REST版本号，在官网文档REST介绍中获得。
    );
	private $Batch;  //时间戳
	private $BodyType = "xml";//包体格式，可填值：json 、xml
	private $enabeLog = true; //日志开关。可填值：true、
	private $Filename="/log.txt"; //日志文件
	private $Handle; 
	function __construct($voiceinfo=array())
	{
		$this->Batch = date("YmdHis");
        $this->voiceinfo=array_merge($this->voiceinfo,$voiceinfo);//初始化动态改设置
        $this->Filename=$_SERVER['DOCUMENT_ROOT']."/wei/base/verify/log".$this->Filename;
        $this->Handle = fopen($this->Filename, 'a');
	}

    function __get($name){
        if(array_key_exists($name,$this->voiceinfo))
            return $this->voiceinfo[$name];
    }

    function __set($name,$value){
        if(array_key_exists($name,$this->voiceinfo))
            $this->voiceinfo[$name]=$value;
    }
   /**
    * 打印日志
    * 
    * @param log 日志内容
    */
    function showlog($log){
      if($this->enabeLog){
         fwrite($this->Handle,$log."\n");  
      }
    }
    
    /**
     * 发起HTTPS请求
     */
     function curl_post($url,$data,$header,$post=1)
     {
       //初始化curl
       $ch = curl_init();
       //参数设置  
       $res= curl_setopt ($ch, CURLOPT_URL,$url);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
       curl_setopt ($ch, CURLOPT_HEADER, 0);
       curl_setopt($ch, CURLOPT_POST, $post);
       if($post)
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
       $result = curl_exec ($ch);
       //连接失败
       if($result == FALSE){
          if($this->BodyType=='json'){
             $result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";
          } else {
             $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Response><statusCode>172001</statusCode><statusMsg>网络错误</statusMsg></Response>"; 
          }    
       }

       curl_close($ch);
       return $result;
     } 
    
    /**
    * 语音验证码
    * @param verifyCode 验证码内容，为数字和英文字母，不区分大小写，长度4-8位
    * @param playTimes 播放次数，1－3次
    * @param to 接收号码
    * @param displayNum 显示的主叫号码
    * @param respUrl 语音验证码状态通知回调地址，云通讯平台将向该Url地址发送呼叫结果通知
    * @param lang 语言类型
    * @param userData 第三方私有数据
	* @param welcomePrompt  欢迎提示音，在播放验证码语音前播放此内容（语音文件格式为wav）           
	* @param playVerifyCode  语音验证码的内容全部播放此节点下的全部语音文件
    */
    function voiceVerify($verifyCode,$to,$respUrl='',$userData='',$playTimes=3,$displayNum='',$lang='zh',$welcomePrompt='',$playVerifyCode='')
    {
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->accAuth();
        if($auth!=""){
            return $auth;
        }
        // 拼接请求包体
        if($this->BodyType=="json"){
           $body= "{'appId':'$this->AppId','verifyCode':'$verifyCode','playTimes':'$playTimes','to':'$to','respUrl':'$respUrl','displayNum':'$displayNum',
           'lang':'$lang','userData':'$userData','welcomePrompt':'$welcomePrompt','playVerifyCode':'$playVerifyCode'}";
        }else{
           $body="<VoiceVerify>
                    <appId>$this->AppId</appId>
                    <verifyCode>$verifyCode</verifyCode>
                    <playTimes>$playTimes</playTimes>
                    <to>$to</to>
                    <respUrl>$respUrl</respUrl>
                    <displayNum>$displayNum</displayNum>
                    <lang>$lang</lang>
                    <userData>$userData</userData>
					<welcomePrompt>$welcomePrompt</welcomePrompt>
					<playVerifyCode>$playVerifyCode</playVerifyCode>
                  </VoiceVerify>";
        }
        $this->showlog("request body = ".$body);
        // 大写的sig参数
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL  
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/Calls/VoiceVerify?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头  
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url,$body,$header);
        $this->showlog("response body = ".$result);
        if($this->BodyType=="json"){//JSON格式
           $datas=json_decode($result); 
        }else{ //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }

        return $datas;
    }
         
  /**
    * 主帐号鉴权
    */   
   function accAuth()
   {
       if($this->ServerIP==""){
            $data = new stdClass();
            $data->statusCode = '172004';
            $data->statusMsg = 'IP为空';
          return $data;
        }
        if($this->ServerPort<=0){
            $data = new stdClass();
            $data->statusCode = '172005';
            $data->statusMsg = '端口错误（小于等于0）';
          return $data;
        }
        if($this->SoftVersion==""){
            $data = new stdClass();
            $data->statusCode = '172013';
            $data->statusMsg = '版本号为空';
          return $data;
        } 
        if($this->AccountSid==""){
            $data = new stdClass();
            $data->statusCode = '172006';
            $data->statusMsg = '主帐号为空';
          return $data;
        }
        if($this->AccountToken==""){
            $data = new stdClass();
            $data->statusCode = '172007';
            $data->statusMsg = '主帐号令牌为空';
          return $data;
        }
        if($this->AppId==""){
            $data = new stdClass();
            $data->statusCode = '172012';
            $data->statusMsg = '应用ID为空';
          return $data;
        }   
   }
}
?>
