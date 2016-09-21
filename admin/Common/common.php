<?php
//删除商品图片和目录可以是数组或者文件
function delDirFile($path,$arr){
    if(is_array($arr)){
        foreach($arr as $v){
            $delPath = $path.'/'.$v;
            $allFile = scandir($delPath);
            foreach($allFile as $val){
                if($val != '.' || $val != '..'){
                    $delfile = $delPath.'/'.$val;
                    unlink($delfile);
                }
            }
            rmdir($delPath);
        }
    }else{
        $delfile = $path.'/'.$arr;
        unlink($delfile);
    }
}
//清除api缓存
function delCache($dir){	//删除目录
	    $handle=@opendir($dir);
	    while ($file = @readdir($handle)) {
	        $bdir=$dir.'/'.$file;
	        if (filetype($bdir)=='dir') {
	            if($file!='.' && $file!='..')
	            delCache($bdir);
	        } else {
	            @unlink($bdir);
	        }
	    }
	    closedir($handle);
	    @rmdir($dir);
		return true;
}
//清除所有缓存新方法
function deleteCacheData($dir){
		$fileArr	=	file_list($dir);		
	 	foreach($fileArr as $file)
	 	{
	 		if(strstr($file,"Logs")==false)
	 		{	 			
	 			@unlink($file);	 			
	 		}
	 	}
	 
	 	$fileList	=	array();
	}
function file_list($path)
{
 	global $fileList;
 	if ($handle = opendir($path)) 
 	{
 		while (false !== ($file = readdir($handle))) 
 		{
 			if ($file != "." && $file != "..") 
 			{
 				if (is_dir($path."/".$file)) 
 				{ 					
 						
 					file_list($path."/".$file);
 				} 
 				else 
 				{
 						//echo $path."/".$file."<br>";
 					$fileList[]	=	$path."/".$file;
 				}
 			}
 		}
 	}
 	return $fileList;
}


function url_parse($url){    	
    $rs = preg_match("/^(http:\/\/|https:\/\/)/", $url, $match);
	if (intval($rs)==0) {
		$url = "http://".$url;
	}		
	return $url;
}
function uimg($img){
	if(empty($img)){
		return SITE_ROOT."data/user/avatar.gif";
	}
	return $img;
}
//转换时间
function gmtTime()
{	
	return date('YmdHis');
}
//如果不是二维数组返回true
function IsTwoArray($array){
	  return count($array)==count($array, 1);
}


//表单过滤函数
function setFormString($_string) {
		if (!get_magic_quotes_gpc()) {
			if (is_array($_string)) {
				foreach ($_string as $_key=>$_value) {
					$_string[$_key] = setFormString($_value);	//迭代调用
				}
			} else {
				return addslashes($_string); //mysql_real_escape_string($_string, $_link);不支持就用代替addslashes();
			}
		}
		return $_string;
}
/**
 * 验证输入的邮件地址是否合法
 *
 * @param   string      $email      需要验证的邮件地址
 *
 * @return bool
 */
function is_email($user_email)
{
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,5}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
    {
        if (preg_match($chars, $user_email))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

/**
 * 检查是否为一个合法的时间格式
 *
 * @param   string  $time
 * @return  void
 */
function is_time($time)
{
    $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';

    return preg_match($pattern, $time);
}
/**
 * 获得当前的域名
 *
 * @return  string
 */
function get_domain()
{
    /* 协议 */
    $protocol = (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';

    /* 域名或IP地址 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    elseif (isset($_SERVER['HTTP_HOST']))
    {
        $host = $_SERVER['HTTP_HOST'];
    }
    else
    {
        /* 端口 */
        if (isset($_SERVER['SERVER_PORT']))
        {
            $port = ':' . $_SERVER['SERVER_PORT'];

            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
            {
                $port = '';
            }
        }
        else
        {
            $port = '';
        }

        if (isset($_SERVER['SERVER_NAME']))
        {
            $host = $_SERVER['SERVER_NAME'] . $port;
        }
        elseif (isset($_SERVER['SERVER_ADDR']))
        {
            $host = $_SERVER['SERVER_ADDR'] . $port;
        }
    }

    return $protocol . $host;
}

/**
 * 获得网站的URL地址
 *
 * @return  string
 */
function site_url()
{
    return get_domain() . substr(PHP_SELF, 0, strrpos(PHP_SELF, '/'));
}


/**
 * 截取UTF-8编码下字符串的函数
 *
 * @param   string      $str        被截取的字符串
 * @param   int         $length     截取的长度
 * @param   bool        $append     是否附加省略号
 *
 * @return  string
 */
function sub_str($string, $length = 0, $append = true)
{

    if(strlen($string) <= $length) {
        return $string;
    }

    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

    $strcut = '';

    if(strtolower(CHARSET) == 'utf-8') {
        $n = $tn = $noc = 0;
        while($n < strlen($string)) {

            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif(194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif(224 <= $t && $t < 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif(240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif(248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
                $n++;
            }

            if($noc >= $length) {
                break;
            }

        }
        if($noc > $length) {
            $n -= $tn;
        }

        $strcut = substr($string, 0, $n);

    } else {
        for($i = 0; $i < $length; $i++) {
            $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
        }
    }

    $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

    if ($append && $string != $strcut)
    {
        $strcut .= '...';
    }

    return $strcut;

}
/**
 *  将一个字串中含有全角的数字字符、字母、空格或'%+-()'字符转换为相应半角字符
 *
 * @access  public
 * @param   string       $str         待转换字串
 *
 * @return  string       $str         处理后字串
 */
function make_semiangle($str)
{
    $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
        '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
        'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
        'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
        'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
        'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
        'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
        'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
        'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
        'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
        'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
        'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
        'ｙ' => 'y', 'ｚ' => 'z',
        '（' => '(', '）' => ')', '［' => '[', '］' => ']', '【' => '[',
        '】' => ']', '〖' => '[', '〗' => ']', '「' => '[', '」' => ']',
        '『' => '[', '』' => ']', '｛' => '{', '｝' => '}', '《' => '<',
        '》' => '>',
        '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
        '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
        '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
        '＂' => '"', '＇' => '`', '｀' => '`', '｜' => '|', '〃' => '"',
        '　' => ' ');

    return strtr($str, $arr);
}

/**
 * 格式化费用：可以输入数字或百分比的地方
 *
 * @param   string      $fee    输入的费用
 */
function format_fee($fee)
{
    $fee = make_semiangle($fee);
    if (strpos($fee, '%') === false)
    {
        return floatval($fee);
    }
    else
    {
        return floatval($fee) . '%';
    }
}

/**
 * 根据总金额和费率计算费用
 *
 * @param     float    $amount    总金额
 * @param     string    $rate    费率（可以是固定费率，也可以是百分比）
 * @param     string    $type    类型：s 保价费 p 支付手续费 i 发票税费
 * @return     float    费用
 */
function compute_fee($amount, $rate, $type)
{
    $amount = floatval($amount);
    if (strpos($rate, '%') === false)
    {
        return round(floatval($rate), 2);
    }
    else
    {
        $rate = floatval($rate) / 100;
        if ($type == 's')
        {
            return round($amount * $rate, 2);
        }
        elseif($type == 'p')
        {
            return round($amount * $rate / (1 - $rate), 2);
        }
        else
        {
            return round($amount * $rate, 2);
        }
    }
}/**
 * 获得用户操作系统的换行符
 *
 * @access  public
 * @return  string
 */
function get_crlf()
{
    /* LF (Line Feed, 0x0A, \N) 和 CR(Carriage Return, 0x0D, \R) */
    if (stristr($_SERVER['HTTP_USER_AGENT'], 'Win'))
    {
        $the_crlf = "\r\n";
    }
    elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Mac'))
    {
        $the_crlf = "\r"; // for old MAC OS
    }
    else
    {
        $the_crlf = "\n";
    }

    return $the_crlf;
}

//这个递归无法用到数组里面，可参考uscount
function utcount($partner){
    if(!is_array($partner))
        return false;
    $member=M('member');
    static $count=array();
    foreach($partner as $k=>$v){
        $count['count']++;
        if($v['jointag']==1)
            $count['mcount']++;
        $p=$member->where("pid='".$v['open_id']."'")->field('open_id,jointag')->select();
        if(!empty($p))
            utcount($p);
    }
    return $count;
}

//如果是遍历数组，就必须要区分$key，不然会累加全部赋值回去。
function uscount($partner,$key){ 
    if(!is_array($partner))
        return false;
    $member=M('member');   
    static $count=array();
    foreach($partner as $k=>$v){
        $count[$key]['count']++;
        if($v['jointag']==1)
            $count[$key]['mcount']++;
        $p=$member->where("pid='".$v['open_id']."'")->field('open_id,jointag')->select();
        if(!empty($p))
            uscount($p,$key);       
    }
    return $count[$key];
}

function sp_password($pw,$prefix='hm_'){
    $decor=md5($prefix);
    $mi=md5($pw);
    return substr($decor,0,12).$mi.substr($decor,-4,4);
}
 function coupon_sn(){
     $sn=substr(md5("hz".time()),0,5);
     if(M('coupon')->where("sn='$sn'")->count()>0) {
         $sn=coupon_sn();
     }
     return $sn;
 }

/**
     * 上传标签
     * @param string $tag  
     * url：上传的图片处理的控制器方法   
     * name：表单name   
     * word：提示文字
     */
  function _webuploader($tag){
    
        $url=isset($tag['url'])  ?U($tag['url']):U('guanggao/ajax_upload');
        $name=isset($tag['name'])?$tag['name']:'file_name';
        $word=isset($tag['word'])?$tag['word']:'或将照片拖到这里，单次最多可选300张';
        $id_name='upload-'.uniqid();
$str=<<<php
<div id="$id_name" class="xb-uploader">
    <input type="hidden" name="$name">
    <div class="queueList">
        <div class="placeholder">
            <div class="filePicker"></div>
            <p>$word</p>
        </div>
    </div>
    <div class="statusBar" style="display:none;">
        <div class="progress">
            <span class="text">0%</span>
            <span class="percentage"></span>
        </div>
        <div class="info"></div>
        <div class="btns">
            <div class="uploadBtn">开始上传</div>
        </div>
    </div>
</div>
<script>
jQuery(function() {
    var \$ = jQuery,    // just in case. Make sure it's not an other libaray.
 
        \$wrap = \$("#$id_name"),
 
        // 图片容器
        \$queue = \$('<ul class="filelist"></ul>')
            .appendTo( \$wrap.find('.queueList') ),
 
        // 状态栏，包括进度和控制按钮
        \$statusBar = \$wrap.find('.statusBar'),
 
        // 文件总体选择信息。
        \$info = \$statusBar.find('.info'),
 
        // 上传按钮
        \$upload = \$wrap.find('.uploadBtn'),
 
        // 没选择文件之前的内容。
        \$placeHolder = \$wrap.find('.placeholder'),
 
        // 总体进度条
        \$progress = \$statusBar.find('.progress').hide(),
 
        // 添加的文件数量
        fileCount = 0,
 
        // 添加的文件总大小
        fileSize = 0,
 
        // 优化retina, 在retina下这个值是2
        ratio = window.devicePixelRatio || 1,
 
        // 缩略图大小
        thumbnailWidth = 110 * ratio,
        thumbnailHeight = 110 * ratio,
 
        // 可能有pedding, ready, uploading, confirm, done.
        state = 'pedding',
 
        // 所有文件的进度信息，key为file id
        percentages = {},
 
        supportTransition = (function(){
            var s = document.createElement('p').style,
                r = 'transition' in s ||
                      'WebkitTransition' in s ||
                      'MozTransition' in s ||
                      'msTransition' in s ||
                      'OTransition' in s;
            s = null;
            return r;
        })(),
 
        // WebUploader实例
        uploader;
 
    if ( !WebUploader.Uploader.support() ) {
        alert( 'Web Uploader 不支持您的浏览器！如果你使用的是IE浏览器，请尝试升级 flash 播放器');
        throw new Error( 'WebUploader does not support the browser you are using.' );
    }
 
    // 实例化
    uploader = WebUploader.create({
        pick: {
            id: "#$id_name .filePicker",
            label: '点击选择文件',
            multiple : false
        },
        dnd: "#$id_name .queueList",
        paste: document.body,
        // accept: {
        //     title: 'Images',
        //     extensions: 'gif,jpg,jpeg,bmp,png',
        //     mimeTypes: 'image/*'
        // },
 
        // swf文件路径
        swf: BASE_URL + '/Uploader.swf',
 
        disableGlobalDnd: true,
 
        chunked: false,
        server: "$url",
        fileNumLimit: 300,
        fileSizeLimit: 150 * 1024 * 1024,    // 200 M
        fileSingleSizeLimit: 150 * 1024 * 1024    // 50 M
    });
 
    // 添加“添加文件”的按钮，
    // uploader.addButton({
    //    id: "#$id_name .filePicker2",
    //    label: '继续添加'
    // });
 
    // 当有文件添加进来时执行，负责view的创建
    function addFile( file ) {
        var \$li = \$( '<li id="' + file.id + '">' +
                '<p class="title">' + file.name + '</p>' +
                '<p class="imgWrap"></p>'+
                '<p class="progress"><span></span></p>' +
                '</li>' ),
 
            \$btns = \$('<div class="file-panel">' +
                '<span class="cancel">删除</span>' +
                '<span class="rotateRight">向右旋转</span>' +
                '<span class="rotateLeft">向左旋转</span></div>').appendTo( \$li ),
            \$prgress = \$li.find('p.progress span'),
            \$wrap = \$li.find( 'p.imgWrap' ),
            \$info = \$('<p class="error"></p>'),
 
            showError = function( code ) {
                switch( code ) {
                    case 'exceed_size':
                        text = '文件大小超出';
                        break;
 
                    case 'interrupt':
                        text = '上传暂停';
                        break;
 
                    default:
                        text = '上传失败，请重试';
                        break;
                }
 
                \$info.text( text ).appendTo( \$li );
            };
 
        if ( file.getStatus() === 'invalid' ) {
            showError( file.statusText );
        } else {
            // @todo lazyload
            \$wrap.text( '预览中' );
            uploader.makeThumb( file, function( error, src ) {
                if ( error ) {
                    \$wrap.text( '不能预览' );
                    return;
                }
 
                var img = \$('<img src="'+src+'">');
                \$wrap.empty().append( img );
            }, thumbnailWidth, thumbnailHeight );
 
            percentages[ file.id ] = [ file.size, 0 ];
            file.rotation = 0;
        }
 
        file.on('statuschange', function( cur, prev ) {
            if ( prev === 'progress' ) {
                \$prgress.hide().width(0);
            } else if ( prev === 'queued' ) {
                \$li.off( 'mouseenter mouseleave' );
                \$btns.remove();
            }
 
            // 成功
            if ( cur === 'error' || cur === 'invalid' ) {
                console.log( file.statusText );
                showError( file.statusText );
                percentages[ file.id ][ 1 ] = 1;
            } else if ( cur === 'interrupt' ) {
                showError( 'interrupt' );
            } else if ( cur === 'queued' ) {
                percentages[ file.id ][ 1 ] = 0;
            } else if ( cur === 'progress' ) {
                \$info.remove();
                \$prgress.css('display', 'block');
            } else if ( cur === 'complete' ) {
                \$li.append( '<span class="success"></span>' );
            }
 
            \$li.removeClass( 'state-' + prev ).addClass( 'state-' + cur );
        });
 
        \$li.on( 'mouseenter', function() {
            \$btns.stop().animate({height: 30});
        });
 
        \$li.on( 'mouseleave', function() {
            \$btns.stop().animate({height: 0});
        });
 
        \$btns.on( 'click', 'span', function() {
            var index = \$(this).index(),
                deg;
 
            switch ( index ) {
                case 0:
                    uploader.removeFile( file );
                    return;
 
                case 1:
                    file.rotation += 90;
                    break;
 
                case 2:
                    file.rotation -= 90;
                    break;
            }
 
            if ( supportTransition ) {
                deg = 'rotate(' + file.rotation + 'deg)';
                \$wrap.css({
                    '-webkit-transform': deg,
                    '-mos-transform': deg,
                    '-o-transform': deg,
                    'transform': deg
                });
            } else {
                \$wrap.css( 'filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ (~~((file.rotation/90)%4 + 4)%4) +')');
                // use jquery animate to rotation
                // \$({
                //     rotation: rotation
                // }).animate({
                //     rotation: file.rotation
                // }, {
                //     easing: 'linear',
                //     step: function( now ) {
                //         now = now * Math.PI / 180;
 
                //         var cos = Math.cos( now ),
                //             sin = Math.sin( now );
 
                //         \$wrap.css( 'filter', "progid:DXImageTransform.Microsoft.Matrix(M11=" + cos + ",M12=" + (-sin) + ",M21=" + sin + ",M22=" + cos + ",SizingMethod='auto expand')");
                //     }
                // });
            }
 
 
        });
 
        \$li.appendTo( \$queue );
    }
 
    // 负责view的销毁
    function removeFile( file ) {
        var \$li = \$('#'+file.id);
 
        delete percentages[ file.id ];
        updateTotalProgress();
        \$li.off().find('.file-panel').off().end().remove();
    }
 
    function updateTotalProgress() {
        var loaded = 0,
            total = 0,
            spans = \$progress.children(),
            percent;
 
        \$.each( percentages, function( k, v ) {
            total += v[ 0 ];
            loaded += v[ 0 ] * v[ 1 ];
        } );
 
        percent = total ? loaded / total : 0;
 
        spans.eq( 0 ).text( Math.round( percent * 100 ) + '%' );
        spans.eq( 1 ).css( 'width', Math.round( percent * 100 ) + '%' );
        updateStatus();
    }
 
    function updateStatus() {
        var text = '', stats;
 
        if ( state === 'ready' ) {
            text = '选中' + fileCount + '个文件，共' +
                    WebUploader.formatSize( fileSize ) + '。';
        } else if ( state === 'confirm' ) {
            stats = uploader.getStats();
            if ( stats.uploadFailNum ) {
                text = '已成功上传' + stats.successNum+ '个文件，'+
                    stats.uploadFailNum + '个上传失败，<a class="retry" href="#">重新上传</a>失败文件或<a class="ignore" href="#">忽略</a>'
            }
 
        } else {
            stats = uploader.getStats();
            text = '共' + fileCount + '个（' +
                    WebUploader.formatSize( fileSize )  +
                    '），已上传' + stats.successNum + '个';
 
            if ( stats.uploadFailNum ) {
                text += '，失败' + stats.uploadFailNum + '个';
            }
        }
 
        \$info.html( text );
    }
 
    uploader.onUploadAccept=function(object ,ret){
        if(ret.error_info){
            fileError=ret.error_info;
            return false;
        }
    }
 
    uploader.onUploadSuccess=function(file ,response){
        fileName=response.name;
// 	    console.log(fileName);
    }
    uploader.onUploadError=function(file){
        alert(fileError);
    }
 
    function setState( val ) {
        var file, stats;
        if ( val === state ) {
            return;
        }
 
        \$upload.removeClass( 'state-' + state );
        \$upload.addClass( 'state-' + val );
        state = val;
 
        switch ( state ) {
            case 'pedding':
                \$placeHolder.removeClass( 'element-invisible' );
                \$queue.parent().removeClass('filled');
                \$queue.hide();
                \$statusBar.addClass( 'element-invisible' );
                uploader.refresh();
                break;
 
            case 'ready':
                \$placeHolder.addClass( 'element-invisible' );
                \$( "#$id_name .filePicker2" ).removeClass( 'element-invisible');
                \$queue.parent().addClass('filled');
                \$queue.show();
                \$statusBar.removeClass('element-invisible');
                uploader.refresh();
                break;
 
            case 'uploading':
                \$( "#$id_name .filePicker2" ).addClass( 'element-invisible' );
                \$progress.show();
                \$upload.text( '暂停上传' );
                break;
 
            case 'paused':
                \$progress.show();
                \$upload.text( '继续上传' );
                break;
 
            case 'confirm':
                \$progress.hide();
                //\$upload.text( '开始上传' ).addClass( 'disabled' );
 
                stats = uploader.getStats();
                if ( stats.successNum && !stats.uploadFailNum ) {
                    setState( 'finish' );
                    return;
                }
                break;
            case 'finish':
                stats = uploader.getStats();
                if ( stats.successNum ) {
                    \$("#$id_name input[name='$name']").val(fileName);
                } else {
                    // 没有成功的图片，重设
                    state = 'done';
                    location.reload();
                }
                break;
        }
        updateStatus();
    }
 
    uploader.onUploadProgress = function( file, percentage ) {
        var \$li = \$('#'+file.id),
            \$percent = \$li.find('.progress span');
 
        \$percent.css( 'width', percentage * 100 + '%' );
        percentages[ file.id ][ 1 ] = percentage;
        updateTotalProgress();
    };
 
    uploader.onFileQueued = function( file ) {
        fileCount++;
        fileSize += file.size;
 
        if ( fileCount === 1 ) {
            \$placeHolder.addClass( 'element-invisible' );
            \$statusBar.show();
        }
 
        addFile( file );
        setState( 'ready' );
        updateTotalProgress();
    };
 
    uploader.onFileDequeued = function( file ) {
        fileCount--;
        fileSize -= file.size;
 
        if ( !fileCount ) {
            setState( 'pedding' );
        }
 
        removeFile( file );
        updateTotalProgress();
 
    };
 
    uploader.on( 'all', function( type ) {
        var stats;
        switch( type ) {
            case 'uploadFinished':
                setState( 'confirm' );
                break;
 
            case 'startUpload':
                setState( 'uploading' );
                break;
 
            case 'stopUpload':
                setState( 'paused' );
                break;
 
        }
    });
 
    uploader.onError = function( code ) {
        alert( 'Eroor: ' + code );
    };
 
    \$upload.on('click', function() {
        if ( \$(this).hasClass( 'disabled' ) ) {
            return false;
        }
 
        if ( state === 'ready' ) {
            uploader.upload();
        } else if ( state === 'paused' ) {
            uploader.upload();
        } else if ( state === 'uploading' ) {
            uploader.stop();
        }
    });
 
    \$info.on( 'click', '.retry', function() {
        uploader.retry();
    } );
 
    \$info.on( 'click', '.ignore', function() {
        alert( 'todo' );
    } );
 
    \$upload.addClass( 'state-' + state );
    updateTotalProgress();
});
</script>
php;
        return $str;
 }

 /**
  * 上传文件类型控制 此方法仅限ajax上传使用
  * @param  string   $path    字符串 保存文件路径示例： /Upload/image/
  * @param  string   $format  文件格式限制
  * @param  integer  $maxSize 允许的上传文件最大值 52428800
  * @return booler   返回ajax的json格式数据
  */
 function ajax_upload($path='file',$format='empty'){
 	
 	ini_set('max_execution_time', '0');
 	if(!empty($_FILES)){

 		import('ORG.Net.UploadFile');
 		$upload = new UploadFile();// 实例化上传类
 		$upload->maxSize  = '3145728000' ;// 设置附件上传大小
 		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','mp4','wmv','avi');// 设置附件上传类型

 		if($path == 'file'){
            $upload->savePath = ROOT_PATH.'/data/video/';// 设置附件上传目录
        }else{
            $upload->savePath = ROOT_PATH . $path;
        }

        $upload->saveRule = uniqid;
        $upload->autoSub  = true;
        $upload->subType  = date;
        $info=$upload->upload();
 		$data=array();
 		
 		if(!$info){
 			// 返回错误信息
 			$error=$upload->getErrorMsg();
 			$data['error_info']=$error;
 			echo json_encode($data);
 			exit();
 		}else{
 			// 返回成功信息
 			$upload_list = $upload->getUploadFileInfo();
            $path = ($path == 'file') ? '/data/video' : $path ;
 			$data['name']=trim('http://m.hz41319.com'. $path . $upload_list['0']['savename']);
 			echo json_encode($data);
 			
 		}
 	}
 	
 }

function city_array(){
     $city_arr = array('安徽'
     => Array(
             '合肥(*)', '合肥',
             '安庆', '安庆',
             '蚌埠', '蚌埠',
             '亳州', '亳州',
             '巢湖', '巢湖',
             '滁州', '滁州',
             '阜阳', '阜阳',
             '贵池', '贵池',
             '淮北', '淮北',
             '淮化', '淮化',
             '淮南', '淮南',
             '黄山', '黄山',
             '九华山', '九华山',
             '六安', '六安',
             '马鞍山', '马鞍山',
             '宿州', '宿州',
             '铜陵', '铜陵',
             '屯溪', '屯溪',
             '芜湖', '芜湖',
             '宣城', '宣城'),

         '北京'
         => Array(
             '东城', '东城',
             '西城', '西城',
             '崇文', '崇文',
             '宣武', '宣武',
             '朝阳', '朝阳',
             '丰台', '丰台',
             '石景山', '石景山',
             '海淀', '海淀',
             '门头沟', '门头沟',
             '房山', '房山',
             '通州', '通州',
             '顺义', '顺义',
             '昌平', '昌平',
             '大兴', '大兴',
             '平谷', '平谷',
             '怀柔', '怀柔',
             '密云', '密云',
             '延庆', '延庆'),

         '重庆'
         => Array(
             '万州', '万州',
             '涪陵', '涪陵',
             '渝中', '渝中',
             '大渡口', '大渡口',
             '江北', '江北',
             '沙坪坝', '沙坪坝',
             '九龙坡','九龙坡',
             '南岸', '南岸',
             '北碚', '北碚',
             '万盛', '万盛',
             '双挢', '双挢',
             '渝北', '渝北',
             '巴南', '巴南',
             '黔江', '黔江',
             '长寿', '长寿',
             '綦江', '綦江',
             '潼南', '潼南',
             '铜梁', '铜梁',
             '大足', '大足',
             '荣昌', '荣昌',
             '壁山', '壁山',
             '梁平', '梁平',
             '城口', '城口',
             '丰都', '丰都',
             '垫江', '垫江',
             '武隆', '武隆',
             '忠县', '忠县',
             '开县', '开县',
             '云阳', '云阳',
             '奉节', '奉节',
             '巫山', '巫山',
             '巫溪', '巫溪',
             '石柱', '石柱',
             '秀山', '秀山',
             '酉阳', '酉阳',
             '彭水', '彭水',
             '江津', '江津',
             '合川', '合川',
             '永川', '永川',
             '南川', '南川'),

         '福建'
         => Array(
             '福州(*)', '福州',
             '福安', '福安',
             '龙岩', '龙岩',
             '南平', '南平',
             '宁德', '宁德',
             '莆田', '莆田',
             '泉州', '泉州',
             '三明', '三明',
             '邵武', '邵武',
             '石狮', '石狮',
             '晋江', '晋江',
             '永安', '永安',
             '武夷山', '武夷山',
             '厦门', '厦门',
             '漳州', '漳州'),

         '甘肃'
         => Array(
             '兰州(*)', '兰州',
             '白银', '白银',
             '定西', '定西',
             '敦煌', '敦煌',
             '甘南', '甘南',
             '金昌', '金昌',
             '酒泉', '酒泉',
             '临夏', '临夏',
             '平凉', '平凉',
             '天水', '天水',
             '武都', '武都',
             '武威', '武威',
             '西峰', '西峰',
             '嘉峪关','嘉峪关',
             '张掖', '张掖'),

         '广东'
         => Array(
             '广州(*)', '广州',
             '潮阳', '潮阳',
             '潮州', '潮州',
             '澄海', '澄海',
             '东莞', '东莞',
             '佛山', '佛山',
             '河源', '河源',
             '惠州', '惠州',
             '江门', '江门',
             '揭阳', '揭阳',
             '开平', '开平',
             '茂名', '茂名',
             '梅州', '梅州',
             '清远', '清远',
             '汕头', '汕头',
             '汕尾', '汕尾',
             '韶关', '韶关',
             '深圳', '深圳',
             '顺德', '顺德',
             '阳江', '阳江',
             '英德', '英德',
             '云浮', '云浮',
             '增城', '增城',
             '湛江', '湛江',
             '肇庆', '肇庆',
             '中山', '中山',
             '珠海', '珠海'),

         '广西'
         => Array(
             '南宁(*)', '南宁',
             '百色', '百色',
             '北海', '北海',
             '桂林', '桂林',
             '防城港', '防城港',
             '河池', '河池',
             '贺州', '贺州',
             '柳州', '柳州',
             '来宾', '来宾',
             '钦州', '钦州',
             '梧州', '梧州',
             '贵港', '贵港',
             '玉林', '玉林'),

         '贵州'
         => Array(
             '贵阳(*)', '贵阳',
             '安顺', '安顺',
             '毕节', '毕节',
             '都匀', '都匀',
             '凯里', '凯里',
             '六盘水', '六盘水',
             '铜仁', '铜仁',
             '兴义', '兴义',
             '玉屏', '玉屏',
             '遵义', '遵义'),

         '海南'
         => Array(
             '海口(*)', '海口',
             '三亚', '三亚',
             '五指山', '五指山',
             '琼海', '琼海',
             '儋州', '儋州',
             '文昌', '文昌',
             '万宁', '万宁',
             '东方', '东方',
             '定安', '定安',
             '屯昌', '屯昌',
             '澄迈', '澄迈',
             '临高', '临高',
             '万宁', '万宁',
             '白沙黎族', '白沙黎族',
             '昌江黎族', '昌江黎族',
             '乐东黎族', '乐东黎族',
             '陵水黎族', '陵水黎族',
             '保亭黎族', '保亭黎族',
             '琼中黎族', '琼中黎族',
             '西沙群岛', '西沙群岛',
             '南沙群岛', '南沙群岛',
             '中沙群岛', '中沙群岛'
         ),

         '河北'
         => Array(
             '石家庄(*)', '石家庄',
             '保定', '保定',
             '北戴河', '北戴河',
             '沧州', '沧州',
             '承德', '承德',
             '丰润', '丰润',
             '邯郸', '邯郸',
             '衡水', '衡水',
             '廊坊', '廊坊',
             '南戴河', '南戴河',
             '秦皇岛', '秦皇岛',
             '唐山', '唐山',
             '新城', '新城',
             '邢台', '邢台',
             '张家口', '张家口'),

         '黑龙江'
         => Array(
             '哈尔滨(*)', '哈尔滨',
             '北安', '北安',
             '大庆', '大庆',
             '大兴安岭', '大兴安岭',
             '鹤岗', '鹤岗',
             '黑河', '黑河',
             '佳木斯', '佳木斯',
             '鸡西', '鸡西',
             '牡丹江', '牡丹江',
             '齐齐哈尔', '齐齐哈尔',
             '七台河', '七台河',
             '双鸭山', '双鸭山',
             '绥化', '绥化',
             '伊春', '伊春'),

         '河南'
         => Array(
             '郑州(*)', '郑州',
             '安阳', '安阳',
             '鹤壁', '鹤壁',
             '潢川', '潢川',
             '焦作', '焦作',
             '济源', '济源',
             '开封', '开封',
             '漯河', '漯河',
             '洛阳', '洛阳',
             '南阳', '南阳',
             '平顶山', '平顶山',
             '濮阳', '濮阳',
             '三门峡', '三门峡',
             '商丘', '商丘',
             '新乡', '新乡',
             '信阳', '信阳',
             '许昌', '许昌',
             '周口', '周口',
             '驻马店', '驻马店'),

         '香港'
         => Array(
             '香港', '香港',
             '九龙', '九龙',
             '新界', '新界'),

         '湖北'
         => Array(
             '武汉(*)', '武汉',
             '恩施', '恩施',
             '鄂州', '鄂州',
             '黄冈', '黄冈',
             '黄石', '黄石',
             '荆门', '荆门',
             '荆州', '荆州',
             '潜江', '潜江',
             '十堰', '十堰',
             '随州', '随州',
             '武穴', '武穴',
             '仙桃', '仙桃',
             '咸宁', '咸宁',
             '襄阳', '襄阳',
             '襄樊', '襄樊',
             '孝感', '孝感',
             '宜昌', '宜昌'),

         '湖南'
         => Array(
             '长沙(*)', '长沙',
             '常德', '常德',
             '郴州', '郴州',
             '衡阳', '衡阳',
             '怀化', '怀化',
             '吉首', '吉首',
             '娄底', '娄底',
             '邵阳', '邵阳',
             '湘潭', '湘潭',
             '益阳', '益阳',
             '岳阳', '岳阳',
             '永州', '永州',
             '张家界', '张家界',
             '株洲', '株洲'),

         '江苏'
         => Array(
             '南京(*)', '南京',
             '常熟', '常熟',
             '常州', '常州',
             '海门', '海门',
             '淮安', '淮安',
             '江都', '江都',
             '江阴', '江阴',
             '昆山', '昆山',
             '连云港', '连云港',
             '南通', '南通',
             '启东', '启东',
             '沭阳', '沭阳',
             '宿迁', '宿迁',
             '苏州', '苏州',
             '太仓', '太仓',
             '泰州', '泰州',
             '同里', '同里',
             '无锡', '无锡',
             '徐州', '徐州',
             '盐城', '盐城',
             '扬州', '扬州',
             '宜兴', '宜兴',
             '仪征', '仪征',
             '张家港', '张家港',
             '镇江', '镇江',
             '周庄', '周庄'),

         '江西'
         => Array(
             '南昌(*)', '南昌',
             '抚州', '抚州',
             '赣州', '赣州',
             '吉安', '吉安',
             '景德镇', '景德镇',
             '井冈山', '井冈山',
             '九江', '九江',
             '庐山', '庐山',
             '萍乡', '萍乡',
             '上饶', '上饶',
             '新余', '新余',
             '宜春', '宜春',
             '鹰潭', '鹰潭'),

         '吉林'
         => Array(
             '长春(*)', '长春',
             '白城', '白城',
             '白山', '白山',
             '珲春', '珲春',
             '辽源', '辽源',
             '梅河', '梅河',
             '吉林', '吉林',
             '四平', '四平',
             '松原', '松原',
             '通化', '通化',
             '延吉', '延吉'),
         '辽宁'
         => Array(
             '沈阳(*)', '沈阳',
             '鞍山', '鞍山',
             '本溪', '本溪',
             '朝阳', '朝阳',
             '大连', '大连',
             '丹东', '丹东',
             '抚顺', '抚顺',
             '阜新', '阜新',
             '葫芦岛', '葫芦岛',
             '锦州', '锦州',
             '辽阳', '辽阳',
             '盘锦', '盘锦',
             '铁岭', '铁岭',
             '营口', '营口'),

         '澳门'
         => Array(
             '澳门', '澳门'),

         '内蒙古'
         => Array(
             '呼和浩特(*)', '呼和浩特',
             '阿拉善盟', '阿拉善盟',
             '包头', '包头',
             '赤峰', '赤峰',
             '东胜', '东胜',
             '海拉尔', '海拉尔',
             '集宁', '集宁',
             '临河', '临河',
             '通辽', '通辽',
             '乌海', '乌海',
             '乌兰浩特', '乌兰浩特',
             '锡林浩特', '锡林浩特'),

         '宁夏'
         => Array(
             '银川(*)', '银川',
             '固原', '固原',
             '中卫', '中卫',
             '石嘴山', '石嘴山',
             '吴忠', '吴忠'),

         '青海'
         => Array(
             '西宁(*)', '西宁',
             '德令哈', '德令哈',
             '格尔木', '格尔木',
             '共和', '共和',
             '海东', '海东',
             '海晏', '海晏',
             '玛沁', '玛沁',
             '同仁', '同仁',
             '玉树', '玉树'),

         '山东'
         => Array(
             '济南(*)', '济南',
             '滨州', '滨州',
             '兖州', '兖州',
             '德州', '德州',
             '东营', '东营',
             '菏泽', '菏泽',
             '济宁', '济宁',
             '莱芜', '莱芜',
             '聊城', '聊城',
             '临沂', '临沂',
             '蓬莱', '蓬莱',
             '青岛', '青岛',
             '曲阜', '曲阜',
             '日照', '日照',
             '泰安', '泰安',
             '潍坊', '潍坊',
             '威海', '威海',
             '烟台', '烟台',
             '枣庄', '枣庄',
             '淄博', '淄博'),

         '上海'
         => Array(
             '崇明', '崇明',
             '黄浦', '黄浦',
             '卢湾', '卢湾',
             '徐汇', '徐汇',
             '长宁', '长宁',
             '静安', '静安',
             '普陀', '普陀',
             '闸北', '闸北',
             '虹口', '虹口',
             '杨浦', '杨浦',
             '闵行', '闵行',
             '宝山', '宝山',
             '嘉定', '嘉定',
             '浦东', '浦东',
             '金山', '金山',
             '松江', '松江',
             '青浦', '青浦',
             '南汇', '南汇',
             '奉贤', '奉贤',
             '朱家角', '朱家角'),

         '山西'
         => Array(
             '太原(*)', '太原',
             '长治', '长治',
             '大同', '大同',
             '候马', '候马',
             '晋城', '晋城',
             '离石', '离石',
             '临汾', '临汾',
             '宁武', '宁武',
             '朔州', '朔州',
             '忻州', '忻州',
             '阳泉', '阳泉',
             '榆次', '榆次',
             '运城', '运城'),

         '陕西'
         => Array(
             '西安(*)', '西安',
             '安康', '安康',
             '宝鸡', '宝鸡',
             '汉中', '汉中',
             '渭南', '渭南',
             '商州', '商州',
             '绥德', '绥德',
             '铜川', '铜川',
             '咸阳', '咸阳',
             '延安', '延安',
             '榆林', '榆林'),

         '四川'
         => Array(
             '成都(*)', '成都',
             '巴中', '巴中',
             '达州', '达州',
             '德阳', '德阳',
             '都江堰', '都江堰',
             '峨眉山', '峨眉山',
             '涪陵', '涪陵',
             '广安', '广安',
             '广元', '广元',
             '九寨沟', '九寨沟',
             '康定', '康定',
             '乐山', '乐山',
             '泸州', '泸州',
             '马尔康', '马尔康',
             '绵阳', '绵阳',
             '眉山', '眉山',
             '南充', '南充',
             '内江', '内江',
             '攀枝花', '攀枝花',
             '遂宁', '遂宁',
             '汶川', '汶川',
             '西昌', '西昌',
             '雅安', '雅安',
             '宜宾', '宜宾',
             '自贡', '自贡',
             '资阳', '资阳'),

         '台湾'
         => Array(
             '台北(*)', '台北',
             '基隆', '基隆',
             '台南', '台南',
             '台中', '台中',
             '高雄', '高雄',
             '屏东', '屏东',
             '南投', '南投',
             '云林', '云林',
             '新竹', '新竹',
             '彰化', '彰化',
             '苗栗', '苗栗',
             '嘉义', '嘉义',
             '花莲', '花莲',
             '桃园', '桃园',
             '宜兰', '宜兰',
             '台东', '台东',
             '金门', '金门',
             '马祖', '马祖',
             '澎湖', '澎湖',
             '其它', '其它'),

         '天津'
         => Array(
             '天津', '天津',
             '和平', '和平',
             '东丽', '东丽',
             '河东', '河东',
             '西青', '西青',
             '河西', '河西',
             '津南', '津南',
             '南开', '南开',
             '北辰', '北辰',
             '河北', '河北',
             '武清', '武清',
             '红挢', '红挢',
             '塘沽', '塘沽',
             '汉沽', '汉沽',
             '大港', '大港',
             '宁河', '宁河',
             '静海', '静海',
             '宝坻', '宝坻',
             '蓟县', '蓟县' ),

         '新疆'
         => Array(
             '乌鲁木齐(*)', '乌鲁木齐',
             '阿克苏', '阿克苏',
             '阿勒泰', '阿勒泰',
             '阿图什', '阿图什',
             '博乐', '博乐',
             '昌吉', '昌吉',
             '东山', '东山',
             '哈密', '哈密',
             '和田', '和田',
             '喀什', '喀什',
             '克拉玛依', '克拉玛依',
             '库车', '库车',
             '库尔勒', '库尔勒',
             '奎屯', '奎屯',
             '石河子', '石河子',
             '塔城', '塔城',
             '吐鲁番', '吐鲁番',
             '伊宁', '伊宁'),

         '西藏'
         => Array(
             '拉萨(*)', '拉萨',
             '阿里', '阿里',
             '昌都', '昌都',
             '林芝', '林芝',
             '那曲', '那曲',
             '日喀则', '日喀则',
             '山南', '山南'),

         '云南'
         => Array(
             '昆明(*)', '昆明',
             '大理', '大理',
             '保山', '保山',
             '楚雄', '楚雄',
             '大理', '大理',
             '东川', '东川',
             '个旧', '个旧',
             '景洪', '景洪',
             '开远', '开远',
             '临沧', '临沧',
             '丽江', '丽江',
             '六库', '六库',
             '潞西', '潞西',
             '曲靖', '曲靖',
             '思茅', '思茅',
             '文山', '文山',
             '西双版纳', '西双版纳',
             '玉溪', '玉溪',
             '中甸', '中甸',
             '昭通', '昭通'),

         '浙江'
         => Array(
             '杭州', '杭州',
             '安吉', '安吉',
             '慈溪', '慈溪',
             '定海', '定海',
             '奉化', '奉化',
             '海盐', '海盐',
             '黄岩', '黄岩',
             '湖州', '湖州',
             '嘉兴', '嘉兴',
             '金华', '金华',
             '临安', '临安',
             '临海', '临海',
             '丽水', '丽水',
             '宁波', '宁波',
             '瓯海', '瓯海',
             '平湖', '平湖',
             '千岛湖', '千岛湖',
             '衢州', '衢州',
             '江山', '江山',
             '瑞安', '瑞安',
             '绍兴', '绍兴',
             '嵊州', '嵊州',
             '台州', '台州',
             '温岭', '温岭',
             '温州', '温州',
             '余姚', '余姚',
             '舟山', '舟山')
     );
     return $city_arr;
 }

function exportExcel($filename,$content){
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Type: application/force-download");
    header("Content-Type: application/download");
    //header("Content-Type: application/x-msexecl;filename=".$filename);
    header("Content-Disposition: attachment;filename=".$filename);
    header("Content-Transfer-Encoding: binary");
    //header("Pragma: no-cache");
    header('Pragma:public');
    header("Expires: 0");

    return  $content;
}


?>
