<?php
namespace app\admin\controller;
use \think\Controller;
use \think\View;
use \think\Request;
use BaseAdmin;
use FormCheck;
use AdminModel;

class Slide extends BaseAdmin{
	private $rule=[
		'slide_form'=>[
			'title'=>['name'=>'title','preg'=>':notnull','notice'=>'请输入标题!'],
            'type'=>['name'=>'type','preg'=>':number','notice'=>'请选择幻灯片类型!'],
            'url'=>['name'=>'url','preg'=>':url','notice'=>'请输入正确的链接!','not_null'=>false],
            'is_show'=>['name'=>'is_show','preg'=>'/^[1|2]{1}$/','notice'=>'请选择展示位置'],
            'img'=>['name'=>'img','preg'=>':notnull','notice'=>'请上传图片'],				
		],
	];
	private $slideType=['1'=>'MOBILE','2'=>'PC'];
    public function index(){
		$sessionkey=session('staff_session_object');
        $this->assign('key',$sessionkey['key']['slide']);
        return view(); 	
    }
    public function pageData(){
		$m = new AdminModel();
    	$db=db('slide');
    	$ret['page']=$m->setPage($db);
    	$m->setSearch($db);
    	$ret['data']=$db->field('id,title,type,update_at,img,sort')->select();
    	foreach ($ret['data'] as $key => &$v) {
    		$v['update_at']=date('Y-m-d H:i:s',$v['update_at']);
    	}
    	return json($ret);    	
    }
    public function add(){
    	$this->assign('action','add');
    	$this->assign('slideType',$this->slideType);
    	return view();
    }
    public function edit($id,$act){
		if (empty($id) and !is_string($id) ) {
    		return json(['result'=>'ERROR','msg'=>'参数错误']);
    	}
    	$this->assign('action',$act);
    	$data=db('slide')->where('id',$id)->find();
    	$this->assign('data',$data);
    	$this->assign('slideType',$this->slideType);
    	return $this->fetch('add');    	
    }
    public function save(){
		$data['title']=input('post.title');
		$data['type']=input('post.type');
		$data['is_show']=input('post.is_show');
		$data['sort']=input('post.sort');
		$data['img']=input('post.img');
		$data['url']=input('post.url');
		$formObj=new FormCheck($this->rule);
		$checkResult=$formObj->checkFrom($data,'slide_form');
		if ( $checkResult['result'] !=='CHECK_PASS' ) {
			return json($checkResult);
		}
		$data['update_at']=time();
		$action=input('post.action');
		switch ($action) {
			case 'add':
				$data['insert_at']=$data['update_at'];
				$id=db('slide')->insertGetId($data);
				break;
			case 'edit':
				$id=input('post.id');
				db('slide')->where('id',$id)->update($data);
				break;
			default:
				# code...
				break;
		}
		return json(['result'=>'SUCCESS','msg'=>'操作成功','id'=>$id]);    	
    }
    public function deleteSlide($id){
    	if ( !is_numeric($id) ) {
    		return json(['result'=>'ERROR','msg'=>'参数错误']);
    	}
    	db('slide')->where('id',$id)->delete();
    	return json(['result'=>'SUCCESS','msg'=>'删除成功']);
    }
}