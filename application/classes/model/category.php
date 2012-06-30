<?php defined('SYSPATH') or die('No direct script access.');

class Model_Category
{
	protected $tableName = 'tree';
	protected $nstree;
	
	protected $errors = array();
	
	public function __construct()
	{
		$this->nstree = new NSTree($this->tableName);
	}
	
	public function getTree()
	{
		return $this->nstree->getTree();
	}
	
	public function getITree($tree)
	{
		return $this->nstree->getITree($tree); // ?????? сделать метод nstree для конвертации данных
	}
	
	public function getPath($id)
	{
		return $this->nstree->getPath($id);
	}
	
	public function checkTree()
	{
		$this->nstree->check(TRUE);
	}
	
	public function isLeaf($id)
	{
		$node = $this->nstree->getNode($id);
		
		if ($node['right_key']-$node['left_key'] == 1)
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	protected function validate($data)
	{
		$validation = Validation::factory($data);
		$validation->rule('name', 'not_empty');
		$validation->rule('name', 'regex', array(':value', '/^([0-9a-zа-я_-\s]+)$/ui'));
		$validation->rule('name', 'min_length', array(':value', '3'));
		$validation->rule('name', 'max_length', array(':value', '250'));
		$validation->rule('parentId', 'check_id', array(':value', $this->tableName));
		
		if(!$validation->check())
		{
			$this->errors = $validation->errors('catErrors');
			return FALSE;
		}
		
		return TRUE;
	}
	
	public function catInsert($parentId, $data = array())
	{
		$data = Arr::extract($data, array('name'));
		$vData = $data;
		$vData['parentId'] = $parentId;
		
		if(!$this->validate($vData))
		{
			return FALSE;
		}
		
		$this->nstree->insert($parentId, $data);
		return TRUE;
	}
	
	public function changeName($parentId, $categoryName)
	{
		$data = array('parentId' => $parentId, 'name' => $categoryName);
		
		if(!$this->validate($data))
		{
			return FALSE;
		}
		
		$query = DB::update($this->tableName)
			->set(array('name' => $categoryName))
			->where('id', '=', $parentId)
			->execute();
		
		return TRUE;
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	public function catDelete($catDeleteId)
	{
		if ($catDeleteId != 0)
			$this->nstree->delete($catDeleteId);
	}
	
	
}