<?php
namespace app\admin\controller;
use \think\Controller;
use \think\View;
use \think\Request;
use BaseAdmin;
use FormCheck;
use AdminModel;

class Article extends BaseAdmin{
	private $rule=[
		'article_form'=>[
			'title'=>['name'=>'title','preg'=>':notnull','notice'=>'请输入标题!'],
            'type'=>['name'=>'type','preg'=>':number','notice'=>'请选择文章类型!'],
            'content'=>['name'=>'content','preg'=>':notnull','notice'=>'请输入详情!'],
            'show_place'=>['name'=>'show_place','preg'=>'/^[1|2]{1}$/','notice'=>'请选择展示位置'],
            'first_img'=>['name'=>'first_img','preg'=>':notnull','notice'=>'请上传新闻首图'],
            'is_show'=>['name'=>'is_show','preg'=>'/^[1|2]{1}$/','notice'=>'请选择是否展示'],				
		],
	];
	private $articleType=['1'=>'新闻','2'=>'公司文化'];
    public function index(){
		$sessionkey=session('staff_session_object');
        $this->assign('key',$sessionkey['key']['article']);
        return view(); 	
    }
    public function pageData(){
		$m = new AdminModel();
    	$db=db('article');
    	$ret['page']=$m->setPage($db);
    	$m->setSearch($db);
    	$ret['data']=$db->field('id,title,type,update_at,first_img,sort')->select();
    	foreach ($ret['data'] as $key => &$v) {
    		$v['update_at']=date('Y-m-d H:i:s',$v['update_at']);
    	}
    	return json($ret);    	
    }
    public function add(){
    	$this->assign('action','add');
    	$this->assign('articleType',$this->articleType);
    	return view();
    }
    public function edit($id,$act){
		if (empty($id) and !is_string($id) ) {
    		return json(['result'=>'ERROR','msg'=>'参数错误']);
    	}
    	$this->assign('action',$act);
    	$data=db('article')->where('id',$id)->find();
    	$this->assign('data',$data);
    	$this->assign('articleType',$this->articleType);
    	return $this->fetch('add');    	
    }
    public function save(){
		$data['title']=input('post.title');
		$data['type']=input('post.type');
		$data['is_show']=input('post.is_show');
		$data['sort']=input('post.sort');
		$data['show_place']=input('post.show_place');
		$data['first_img']=input('post.first_img');
		$data['summary']=input('post.summary');
		$data['content']=$_POST['content'];
		$formObj=new FormCheck($this->rule);
		$checkResult=$formObj->checkFrom($data,'article_form');
		if ( $checkResult['result'] !=='CHECK_PASS' ) {
			return json($checkResult);
		}
		$data['update_at']=time();
		$action=input('post.action');
		switch ($action) {
			case 'add':
				$data['insert_at']=$data['update_at'];
				$id=db('article')->insertGetId($data);
				break;
			case 'edit':
				$id=input('post.id');
				db('article')->where('id',$id)->update($data);
				break;
			default:
				# code...
				break;
		}
		return json(['result'=>'SUCCESS','msg'=>'操作成功','id'=>$id]);    	
    }
    public function deleteArticle($id){
    	if ( !is_numeric($id) ) {
    		return json(['result'=>'ERROR','msg'=>'参数错误']);
    	}
    	db('article')->where('id',$id)->delete();
    	return json(['result'=>'SUCCESS','msg'=>'删除成功']);
    }
}