<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Public extends Basecontroller
{
	protected $layout = 'layouts/main';
	
	protected function checkAuth() {}
	
	public function action_index()
	{
		throw new Exception('Not implemented.');
		$data = array();
		
//		$this->tpl->content = View::factory('materials/index', $data);
	}
	
	public function action_shownode()
	{
		//Приведение к целому чеслу, если передайотся float то abs() убрать!
		$node_id    = abs((int) $this->request->param('id', NULL));
		$teacher_id = abs((int) $this->request->param('id2', NULL));
		
		$data = array();
		//Настройки навигации
		$count = ORM::factory('material')->count_all();
		$pagination = Pagination::factory(array(
			'total_items' => $count,
		))->route_params(array(
			'controller' => $this->request->controller(),
			'action' => $this->request->action(),
		));
		//Выбор
		$material = ORM::factory('material')
				->limit($pagination->items_per_page)
				->offset($pagination->offset)
				->find_all();;
		$cat = new Model_Category;
		
		$data['path'] = $cat->getPath($node_id);
		$data['materials'] = $material->getMaterialsByTeacher('', $teacher_id, $node_id);
		$data['teachername'] = ORM::factory('user', $teacher_id)->name;
		
		//Вывод в шаблон
		$content = View::factory('materials/shownode',array(
			'data' => $data,
			'pagination' => $pagination
		));
		$this->tpl->content = array($content);
	}
	
	public function action_download()
	{
		$material_id = $this->request->param('id', NULL);
		
		$material = ORM::factory('material', $material_id);
		
		if (!$material->loaded()) return;
		
		if (Auth::instance()->logged_in() || ($material->access == 'all'))
		{
			$material->countDownload();
			$link = $material->getLink($material->as_array());
			Request::initial()->redirect($link);
		}
		
		Session::instance()->set('auth_redirect', $_SERVER['REQUEST_URI']);
		
		$this->tpl->content = View::factory('materials/download');
	}
}
