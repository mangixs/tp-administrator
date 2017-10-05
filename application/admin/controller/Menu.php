<?php
namespace app\admin\controller;
use \think\Controller;
use \think\View;
use \think\Request;
use BaseAdmin;
use FormCheck;
use AdminModel;
class Menu extends BaseAdmin{
	private $rule=[
		'func_form'=>[
			'key'=>['name'=>'key','preg'=>':en','notice'=>'请输入功能键值!'],
            'func_name'=>['name'=>'func_name','preg'=>':notnull','notice'=>'请输入功能名称'],				
		],
	];
    public function index(){
        return view();
    }
    public function add(){
    	$pid=input('get.pid');
    	$func=$this->funcAuth();
    	$this->assign('pid',$pid);
    	$this->assign('action','add');
		$this->assign('func',$func);
    	return view();
    }
    public function upload(){
	    $file = request()->file('files');
	    $info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'upload'.DS.'icon');
	    if($info){
	    	$path='/upload/icon/'.$info->getSaveName();
	    	return json(['result'=>'SUCCESS','msg'=>'上传成功','path'=>$path]);
	    }else{
	        return json(['result'=>'ERROR','msg'=>$file->getError()]);
	    }
	}
}
