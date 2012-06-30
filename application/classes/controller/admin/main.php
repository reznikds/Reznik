<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Main extends Basecontroller
{
	protected $layout = 'layouts/main';
	
	public function init()
	{
		$this->checkRole('admin');
	}
	
	public function action_index()
	{
		$data = array();
		$this->tpl->content =  View::factory('admin/adminview', $data);
	}

}
