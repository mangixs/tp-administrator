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
			'key'=>['name'=>'key','preg'=>':en','notice'=>'请输入功能键值!'],
            'func_name'=>['name'=>'func_name','preg'=>':notnull','notice'=>'请输入功能名称'],				
		],
	];
    public function index(){
    	echo 'article';
    }
}