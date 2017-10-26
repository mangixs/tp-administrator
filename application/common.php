<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function p($arr){
    if ( is_bool($arr) ) {
        var_export($arr);
        exit;
    }else{
    	echo '<pre>';
    	print_r($arr);
    	echo '</pre>';
        exit;
    }
}
function encrypts($str,$key){ 
    srand((double)microtime() * 1000000);
    $encrypt_key=md5(rand(0, 32000));
    $ctr=0;
    $tmp='';
    for($i=0;$i<strlen($str);$i++){
        $ctr=$ctr==strlen($encrypt_key)?0:$ctr;
        $tmp.=$encrypt_key[$ctr].($str[$i] ^ $encrypt_key[$ctr++]);
    }
    return base64_encode(keys($tmp,$key));
}
function keys($str,$encrypt_key){
    $encrypt_key=md5($encrypt_key);
    $ctr=0;
    $tmp='';
    for($i=0;$i<strlen($str);$i++){
        $ctr=$ctr==strlen($encrypt_key)?0:$ctr;
        $tmp.=$str[$i] ^ $encrypt_key[$ctr++];
    }
    return $tmp;
}