<?php
use \think\Controller;
use \think\db;
class BaseAdmin extends Controller{
	public function __construct(){
		parent::__construct();
		if ( empty( session('staff_session_object') ) ) {
			$this->redirect('admin/login/index');
		}
	}
}