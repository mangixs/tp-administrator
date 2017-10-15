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
		'menu_form'=>[
			'named'=>['name'=>'named','preg'=>':notnull','notice'=>'请输入名称'],
            'url'=>['name'=>'url','preg'=>'/[a-z|A-Z|\/]+/','notice'=>'请输入功能名称','not_null'=>false],
            'screen_auth'=>['name'=>'screen_auth','preg'=>':notnull','notice'=>'请设置权限'],
            'sort'=>['name'=>'sort','preg'=>':number','notice'=>'请输入排序','not_null'=>false],
            'parent'=>['name'=>'parent','preg'=>':number','notice'=>'请输入选择菜单父级'],				
            'icon'=>['name'=>'icon','preg'=>':notnull','notice'=>'请上传图标','not_null'=>false],
		],
	];
    public function index(){
    	$sessionkey=session('staff_session_object');
        $this->assign('key',$sessionkey['key']['menu']);
        return view();
    }
    public function pageData(){
    	$m = new AdminModel();
    	$db=db('menu');
    	$ret['page']=$m->setPage($db);
    	$m->setSearch($db);
    	$ret['data']=$db->field('id,named,url,level,parent,sort')->where('parent',0)->select();
    	return json($ret);
    }
    public function childMenu(){
    	$pid=input('post.pid');
    	$ret=db('menu')->where('parent',$pid)->field('id,named,url,level,parent,sort')->select();
    	if ( empty($ret) ) {
    		return json(['result'=>'EMPTY','msg'=>'无子菜单数据','pid'=>$pid]);
    	}
    	return json(['result'=>'SUCCESS','msg'=>'获取成功','data'=>$ret,'pid'=>$pid]);
    }
    public function add(){
    	$pid=input('get.pid');
    	$all=$this->allMenu();
    	$func=$this->funcAuth();
    	$this->assign('all',$all);
    	$this->assign('pid',$pid);
    	$this->assign('action','add');
		$this->assign('func',$func);
    	return view();
    }
    public function edit($id,$act){
    	$all=$this->allMenu();
    	$data=db('menu')->where('id',$id)->find();
    	$func=$this->funcAuth();
    	$this->assign('func',$func);
    	$this->assign('all',$all);
    	$this->assign('action',$act);
    	$this->assign('data',$data);
    	return view('add');
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
	public function save(){
		$data['named']=input('post.named');
		$data['url']=input('post.url');
		$data['screen_auth']=$_POST['screen_auth'];
		$data['sort']=input('post.sort');
		$data['parent']=input('post.parent');
		$data['icon']=input('post.icon');
		$formObj = new FormCheck($this->rule);
		$checkResult=$formObj->checkFrom($data,'menu_form');
		if ( $checkResult['result'] !=='CHECK_PASS' ) {
			return json($checkResult);
		}
		$action=input('post.action');
		switch ($action) {
			case 'add':
				$id=$this->addMenu($data);
				break;
			case 'edit':
				$id=input('post.id');
				$this->editMenu($id,$data);
				break;
			default:
				# code...
				break;
		}
		return json(['result'=>'SUCCESS','msg'=>'操作成功','id'=>$id]);
	}
	private function addMenu($data){
		if ( $data['parent'] == 0 ) {
			$data['level']=0;
			db('menu')->insert($data);
			$id=db('menu')->getLastInsID();
			return $id;
		}else{
			$parent=db('menu')->where('id',$data['parent'])->field('level')->find();
			$data['level']=(int)$parent['level']+1;
			db('menu')->insert($data);
			$id=db('menu')->getLastInsID();
			return $id;
		}
	}
	private function editMenu($id,$data){
		if ( $data['parent'] == 0 ) {
			$data['level']=0;
			db('menu')->where('id',$id)->update($data);
		}else{
			$parent=db('menu')->where('id',$data['parent'])->field('level')->find();
			$data['level']=(int)$parent['level']+1;
			db('menu')->where('id',$id)->update($data);
		}
	}
	public function deleteMenu($id){
		if ( empty($id) and !is_numeric($id) ) {
			return json(['result'=>'ERROR','msg'=>'参数错误']);
		}
		$hasChild=db('menu')->where('parent',$id)->field('id,named')->select();
		if ( !empty($hasChild) ) {
			return json(['result'=>'ERROR','msg'=>'存在子菜单,不可删除']);
		}
		db('menu')->where('id',$id)->delete();
		return json(['result'=>'SUCCESS','msg'=>'删除成功']);
	}
}
