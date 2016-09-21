<?php
class verify{
    private $config=array(
        'width'=>60,
        'height'=>30,
        'nstr'=>"123456789",
        'lstr'=>"abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",
        'fontsize'=>16,
        'length'=>4,
        'time'=>1800
    );
    private $_image=null;
    private $code=null;
    public function __get($name){
        return $this->config[$name];
    }
    public function __set($name,$value){
        if(array_key_exists($name,$this->config))
            $this->config[$name]=$value;
    }
    public function __construct($config=array()){
        $this->config=array_merge($this->config,$config);
    }
    public function show(){
        header("content-type:image/png;");
    }
    public function verify(){
        $this->_image=imagecreate($this->width,$this->height);
        imagecolorallocate($this->_image,mt_rand(150,255),mt_rand(150,255),mt_rand(150,255));
        $codeNX=0;
        $this->get_code();
        for($i=0;$i<strlen($this->code);$i++){
            $codeNX+=($this->width/$this->length)*0.7;
            $color=imagecolorallocate($this->_image, mt_rand(1,150), mt_rand(1,150), mt_rand(1,150));
            imagettftext($this->_image,$this->fontsize,mt_rand(-30, 30),$codeNX,$this->fontsize*1.5,$color,"../font/1.ttf",$this->code{$i});
        }
        $_SESSION['check']['verify']['code']=$this->code;
        $_SESSION['check']['verify']['time']=time();
        header("content-type: image/png");
        imagepng($this->_image);
        imagedestroy($this->_image);
    }
    private function get_code($type=0){
        if($type==0){
            $str=$this->nstr.$this->lstr;
        }elseif($type==1){
            $str=$this->nstr;
        }elseif($type==2){
            $str=$this->lstr;
        }
        for($i=0;$i<$this->length;$i++){
            $code=substr(str_shuffle($str),0,1);
            $this->code.=$code;
        }
        return $this->code;
    }

    public function voiceverify($mobile,$voiceinfo=array()){
        if(!preg_match("/^(13[0-9]|15[0-9]|18[0-9])\d{8}$/", $mobile)) {
            echo 0;
            return;
        }
        include($_SERVER['DOCUMENT_ROOT']."/wei/base/verify/sdk/CCPRestSDK.php");
        $rest = new REST($voiceinfo);
        $result = $rest->voiceVerify($this->get_code(1),$mobile);
        if($result == NULL ) {
            echo 2;
            return;
        }
        if($result->statusCode!=0) {
            echo 2;
        } else{
            echo 1;
            $_SESSION['check']['voiceverify']['code']=$this->code;
            $_SESSION['check']['voiceverify']['time']=time();
        }
    }
}