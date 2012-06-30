<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Basecontroller
{
	protected $layout = 'layouts/main';
	
	protected function checkAuth() {}
	
	public function action_index()
	{
		$data = array();
		
		if (!is_readable(APPPATH.'config/database.php'))
		{
			Request::initial()->redirect('install');
		}
		
		$cat = new Model_Category;
		$materials = new Model_Material;
		$cat->checkTree();
		
		$tree = $cat->getTree();
		
		$data['tree'] = $cat->getITree($tree);
		$data['teachers'] = $materials->getTeachersGropupByNode();
		
		$this->tpl->content = View::factory('main', $data);
	}
	
	public function action_prohibited()
	{
		$this->tpl->content = View::factory('auth/prohibited');
	}
	
}
