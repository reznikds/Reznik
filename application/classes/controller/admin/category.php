<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Category extends Basecontroller
{
	protected $layout = 'layouts/main';
	
	public function init()
	{
		$this->checkRole('admin');
	}
	
	public function action_index()
	{
		$data = array();
		
		$category = new Model_Category('tree');
		$category->checkTree(TRUE);
		$data['categories'] = $category->getTree();

		if($this->isPressed('btnSubmitAdd'))
		{
			$categoryName = Arr::get($_POST, 'categoryName', '');
			$parentId = Arr::get($_POST, 'parentId', 0);
			
			$res = $category->catInsert($parentId, array('name'=>$categoryName));
			
			if($res)
			{
				Request::initial()->redirect('admin/category');
			}
			
			$data['errors'] = $category->getErrors();
		}
		
		if($this->isPressed('btnSubmitChange'))
		{
			$categoryName = Arr::get($_POST, 'categoryName', '');
			$parentId = Arr::get($_POST, 'parentId', 0);
			
			if($category->changeName($parentId, $categoryName))
			{
				Request::initial()->redirect('admin/category');
			}
			
			$data['errors'] = $category->getErrors();
		}
		
		if($this->isPressed('btnSubmitDel'))
		{
			$catDeleteId = Arr::get($_POST, 'catDeleteId', 0);
			$category->catDelete($catDeleteId);
			Request::initial()->redirect('admin/category');
		}
		
		$this->tpl->content =  View::factory('admin/categoryeditview', $data);
	}
}
