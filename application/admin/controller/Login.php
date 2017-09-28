<?php
namespace app\admin\controller;
use \think\Controller;
class Login extends Controller{
    public function index(){
        return view();
    }
    public function sub(){
    	$username=input('post.username');
    	$pwd=input('post.password');
    	$remember=input('post.remember');
    	if ( empty($username) and !preg_match("/^[\w|_]{4,10}$/", $username) ) {
    		return err('用户名错误');
    	}
    	if ( empty($pwd) and !preg_mtch("/^[\w|_]{4,10}$/") ) {
    		return err('密码错误');
    	}
    	$ret=db('staff')->where('login_name',$username)->where('pwd',md5($pwd) )->find();
    	if ( empty($ret))  {
    		return err("用户名或密码错误");
    	}
    	unset($ret['pwd']);
    	session('staff_session_object', $ret);
    	$url=url('admin/index/index');
    	return json(['result'=>'SUCCESS','msg'=>'登录成功','data'=>$url]);
    }
}
