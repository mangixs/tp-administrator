<?php
namespace app\admin\controller;
use \think\Controller;
use \think\View;
use \think\Request;
use BaseAdmin;
use FormCheck;
use AdminModel;
class Func extends BaseAdmin{
	private $rule=[
		'func_form'=>[
			'key'=>['name'=>'key','preg'=>':en','notice'=>'请输入功能键值!'],
            'func_name'=>['name'=>'func_name','preg'=>':notnull','notice'=>'请输入功能名称'],				
		],
	];
    public function index(){
        return view();
    }
    public function pageData(){
    	$m = new AdminModel();
    	$db=db('background_func');
    	$ret['page']=$m->setPage($db);
    	$m->setSearch($db);
    	$ret['data']=$db->field('key,func_name')->select();
    	return json($ret);
    }
    public function add(){
    	$this->assign('action','add');
    	return $this->fetch();
    }
    public function edit($id,$act){
    	if (empty($id) and !is_string($id) ) {
    		return json(['result'=>'ERROR','msg'=>'参数错误']);
    	}
    	$this->assign('action',$act);
    	$data=db('background_func')->field('key,func_name')->where('key',$id)->find();
    	$this->assign('data',$data);
    	return $this->fetch('add');
    }
	public function save(){
		$data['key']=input('post.key');
		$data['func_name']=input('post.func_name');
		$formObj=new FormCheck($this->rule);
		$checkResult=$formObj->checkFrom($data,'func_form');
		if ( $checkResult['result'] !=='CHECK_PASS' ) {
			return json($checkResult);
		}
		$action=input('post.action');
		switch ($action) {
			case 'add':

				db('background_func')->insert($data);
				$id=$data['key'];
				break;
			case 'edit':
				$id=$data['key'];
				db('background_func')->where('key',$data['key'])->update(['func_name'=>$data['func_name']]);
				break;
			default:
				# code...
				break;
		}
		return json(['result'=>'SUCCESS','msg'=>'操作成功','id'=>$id]);
	}
	public function deleteFunc($id){
		if (empty($id) and !is_string($id)) {
			return json(['result'=>'ERROR','msg'=>'参数错误']);
		}
		db('background_func')->where('key',$id)->delete();
		db('func_auth')->where('func_key',$id)->delete();
		return json(['result'=>'SUCCESS','msg'=>'删除成功']);
	}
	public function editKey($key){
		if (empty($key) and !is_string($key)) {
			return json(['result'=>'ERROR','msg'=>'参数错误']);
		}
		$data=db('background_func')->where('key',$key)->find();
		$this->assign('data',$data);
		$set=db('func_auth')->where('func_key',$key)->select();
		$this->assign('set',$set);
		return view();
	}
	public function set(){
		$keys=input( 'post.key' );
		$names=input('post.extendname/a');
		$func_key=input('post.extendkey/a');
		$data=[];
		foreach( $func_key as $i=>$key ){
			$data[]=['key'=>$key,'func_key'=>$keys,'auth_name'=>$names[$i]];
		}
		db('func_auth')->where('func_key',$keys)->delete();
		db('func_auth')->insertAll($data);
		return json(['result'=>'SUCCESS','msg'=>'操作成功']);
	}
    public function setauth(){
    	$data['admin_job_id']=input('post.admin_job_id');
        $data['auth_key']=input('post.auth_key');
        $data['func_key']=input('post.func_key');
        $ret=$this->auth($data);
        if ( $ret ) {
        	return json(['result'=>'SUCCESS','msg'=>'删除成功']);
        }else{
        	return json(['result'=>'SUCCESS','msg'=>'添加成功']);
        }
    }
    private function auth($data){
    	$db=db('admin_job_auth');
        $cou=$db->where('admin_job_id',$data['admin_job_id'])->where('auth_key',$data['auth_key'])->where('func_key',$data['func_key'])->count();
        if( $cou>0 ){
            $db->where('admin_job_id',$data['admin_job_id'])->where('auth_key',$data['auth_key'])->where('func_key',$data['func_key'])->delete();
            return true;
        }
        else{
            $db->insert($data);
            return false;
        }
    }
}
