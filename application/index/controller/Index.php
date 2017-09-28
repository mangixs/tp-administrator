<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller{
	private $key='98aksjd298adkjs234aoiuz0asd879s';
	private $rule=[
		'username'=>'require|max:6',
		'pwd'=>'require|max:10',
		'captcha'=>'require|captcha',
	];
    public function index(){
    	return view();
		// if ( empty( input('session.user_session_object') ) ) {
		// 	$this->redirect('Index/login');
		// }else{
		// 	return view();
		// }
    }
    public function login(){
    	return view();
    }
    public function sub(){
    	$data=[
    		'username'=>input('post.username'),
    		'pwd'=>input('post.pwd'),
    		'captcha'=>input('post.captcha')
    	];
    	$result = $this->validate($data,$this->rule);
		if($result !== true){
		    return json(['result'=>'ERROR','msg'=>'请填写正确的用户名密码']);
		}
		$ret=db('user')->where('username',$data['username'])->where('valid',1)->where('pwd',md5($data['pwd']) )->find();
		if ( empty($ret) ) {
			return json(['result'=>'ERROR','msg'=>'用户名密码错误']);
		}
		session('user_session_object', serialize($data));
		$url=url('index');
		return json(['result'=>'SUCCESS','msg'=>'登录成功','href'=>$url]);
    }
    public function test(){
    	return view();
    }
}
