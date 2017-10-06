<?php
namespace app\admin\controller;
use \think\Controller;
use \think\View;
use BaseAdmin;
use FormCheck;
use AdminModel;
class Job extends BaseAdmin{
	private $rule=[
		'Job_form'=>[
			'job_name'=>['name'=>'job_name','preg'=>':notnull','notice'=>'请输入职位!'],
            'explain'=>['name'=>'explain','preg'=>':notnull','notice'=>'请输入职位描述','not_null'=>false],				
		],
	];
    public function index(){
        return view();
    }
    public function pageData(){
    	$m = new AdminModel();
    	$db=db('admin_job');
    	$ret['page']=$m->setPage($db);
    	$m->setSearch($db);
    	$ret['data']=$db->field('id,job_name')->where('vaild',1)->select();
    	return json($ret);
    }
    public function add(){
    	$this->assign('action','add');
    	return $this->fetch();
    }
    public function edit($id,$act){
    	if (empty($id) and !is_numeric($id) ) {
    		return json(['result'=>'ERROR','msg'=>'参数错误']);
    	}
    	$this->assign('action',$act);
    	$data=db('admin_job')->field('id,job_name,explain')->where('vaild',1)->where('id',$id)->find();
    	$this->assign('data',$data);
    	return $this->fetch('add');
    }
	public function save(){
		$data['job_name']=input('post.job_name');
		$data['explain']=input('post.explain');
		$formObj=new FormCheck($this->rule);
		$checkResult=$formObj->checkFrom($data,'Job_form');
		if ( $checkResult['result'] !=='CHECK_PASS' ) {
			return json($checkResult);
		}
		$action=input('post.action');
		switch ($action) {
			case 'add':
				db('admin_job')->insert($data);
				$id=db('admin_job')->getLastInsID();
				break;
			case 'edit':
				$id=input('post.id');
				db('admin_job')->where('id',$id)->update($data);
				break;
			default:
				# code...
				break;
		}
		return json(['result'=>'SUCCESS','msg'=>'操作成功','id'=>$id]);
	}
	public function deleteJob($id){
		if (empty($id) and !is_numeric($id)) {
			return json(['result'=>'ERROR','msg'=>'参数错误']);
		}
		db('admin_job')->where('id',$id)->delete();
		return json(['result'=>'SUCCESS','msg'=>'删除成功']);
	}
	public function set($id){
		$func=$this->funcAuth();
		$ret=$this->hasAuth($id);
		$this->assign('func',$func);
		$this->assign('has',$ret);
		$this->assign('admin_job_id',$id);
		return view();
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
