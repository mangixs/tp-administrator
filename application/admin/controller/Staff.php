<?php
namespace app\admin\controller;
use \think\Controller;
use \think\View;
use BaseAdmin;
use FormCheck;
use AdminModel;
class Staff extends BaseAdmin{
	private $rule=[
		'staff_form'=>[
			'login_name'=>['name'=>'login_name','preg'=>'/^[\w|_]{4,}$/','notice'=>'请输入正确的登录名!'],
            'staff_num'=>['name'=>'staff_num','preg'=>':number','notice'=>'请输入正确的用户编号!'],
            'true_name'=>['name'=>'true_name','preg'=>':ch','notice'=>'请输入正确的用户名!','not_null'=>false],
            'sex'=>['name'=>'sex','preg'=>'/^[1|2]{1}$/','notice'=>'请选择用户性别','not_null'=>false],
            'header_img'=>['name'=>'header_img','preg'=>':notnull','notice'=>'请上传用户头像','not_null'=>false],				
		],
	];
    public function index(){
    	$sessionkey=session('staff_session_object');
        $this->assign('key',$sessionkey['key']['staff']);
        return view();
    }
    public function pageData(){
    	$m = new AdminModel();
    	$db=db('staff');
    	$ret['page']=$m->setPage($db);
    	$m->setSearch($db);
    	$ret['data']=$db->field('id,login_name,staff_num,sex,true_name')->select();
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
    	$data=db('staff')->field('id,login_name,staff_num,sex,true_name,header_img')->where('id',$id)->find();
    	$this->assign('data',$data);
    	return $this->fetch('add');
    }
    public function upload(){
	    $file = request()->file('files');
	    $info = $file->validate(['size'=>2*1024*1024,'ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'upload'.DS.'headimage');
	    if($info){
	    	$path='/upload/headimage/'.$info->getSaveName();
	    	return json(['result'=>'SUCCESS','msg'=>'上传成功','path'=>$path]);
	    }else{
	        return json(['result'=>'ERROR','msg'=>$file->getError()]);
	    }
	}
	public function save(){
		$data['login_name']=input('post.login_name');
		$data['staff_num']=input('post.staff_num');
		$data['true_name']=input('post.true_name');
		$data['sex']=input('post.sex');
		$data['header_img']=input('post.header_img');
		$formObj=new FormCheck($this->rule);
		$checkResult=$formObj->checkFrom($data,'staff_form');
		if ( $checkResult['result'] !=='CHECK_PASS' ) {
			return json($checkResult);
		}
		$action=input('post.action');
		switch ($action) {
			case 'add':
				$data['pwd']=md5(123456);
				db('staff')->insert($data);
				$id=db('staff')->getLastInsID();
				break;
			case 'edit':
				$id=input('post.id');
				db('staff')->where('id',$id)->update($data);
				break;
			default:
				# code...
				break;
		}
		return json(['result'=>'SUCCESS','msg'=>'操作成功','id'=>$id]);
	}
	public function deleteStaff($id){
		if (empty($id) and !is_numeric($id)) {
			return json(['result'=>'ERROR','msg'=>'参数错误']);
		}
		db('staff')->where('id',$id)->delete();
		return json(['result'=>'SUCCESS','msg'=>'删除成功']);
	}
	public function setjob($id){
		if (empty($id) and !is_numeric($id)) {
			return json(['result'=>'ERROR','msg'=>'参数错误']);
		}
		$job=db('admin_job')->field('id,job_name')->where('vaild',1)->select();
		$res=$this->hasJob($id);
		$this->assign('data',$job);
		$this->assign('has',$res);
		$this->assign('staff_id',$id);
		return view();
	}
	private function hasJob($id){
		$ret=db('staff_job')->field('job_id')->where('staff_id',$id)->select();
		$res=[];
		foreach ($ret as $v) {
			$res[]=$v['job_id'];
		}		
		return $res;
	}
	public function jobSave(){
		$staff_id=input('post.staff_id');
		$job_id=input('post.job_id');
		$set=input('post.set')==='true'?true:false;
		if ( empty($staff_id) and empty( $job_id ) and !is_numeric($staff_id) and !is_numeric($job_id) and !is_bool($set) ) {
			return json(['result'=>'ERROR','msg'=>'参数错误']);
		}
		db('staff_job')->where('staff_id',$staff_id)->where('job_id',$job_id)->delete();
		if ( $set ) {
			db('staff_job')->insert(['staff_id'=>$staff_id,'job_id'=>$job_id]);
		}		
		$res=$this->hasJob($staff_id);
		db('staff')->where('id',$staff_id)->update(['job'=>json_encode($res)]);
		return json(['result'=>'SUCCESS','msg'=>'设置成功']);
	}
}
