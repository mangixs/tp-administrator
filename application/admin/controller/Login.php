<?php
namespace app\admin\controller;
use \think\Controller;
use Code;
class Login extends Controller{
    public function index(){
        return view();
    }
    public function captcha(){
        $config =[
            'width' =>  100,
            'height'=>  34,
            'codeLen'=> 4,
            'fontSize'=>16
            ];
        $code = new Code($config);
        return $code->show();
    }
    public function sub(){
    	$username=input('post.username');
    	$pwd=input('post.password');
    	$captcha=input('post.captcha');
        if(!isset($_SESSION)){
            session_start();
        }
        if(strtoupper($captcha) != $_SESSION['code']){
            return json(['result'=>'ERROR','msg'=>'验证码错误']);
        } 
    	if ( empty($username) and !preg_match("/^[\w|_]{4,10}$/", $username) ) {
    		return json(['result'=>'ERROR','msg'=>'用户名错误']);
    	}
    	if ( empty($pwd) and !preg_mtch("/^[\w|_]{4,10}$/") ) {
    		return json(['result'=>'ERROR','msg'=>'密码错误']);
    	}
    	$ret=db('staff')->where('login_name',$username)->where('pwd',md5($pwd) )->find();
    	if ( empty($ret))  {
    		return json(['result'=>'ERROR','msg'=>'用户名或者密码错误']);
    	}
    	unset($ret['pwd']);
    	session('staff_session_object', $ret);
    	$url=url('admin/index/index');
    	return json(['result'=>'SUCCESS','msg'=>'登录成功','data'=>$url]);
    }
}
