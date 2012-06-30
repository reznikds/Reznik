<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Account extends Basecontroller
{
	protected $layout = 'layouts/main';
	
	public function action_index()
	{
		$user = new Model_User();
		$data['username'] = $user->getLoggedInUsername();
		
		if($this->isPressed('btnSubmit'))
		{
			$oldpass = Arr::get($_POST, 'oldpass', '');
			$newpass1 = Arr::get($_POST, 'newpass1', '');
			$newpass2 = Arr::get($_POST, 'newpass2', '');

			if($user->changePassword($oldpass, $newpass1, $newpass2))
			{
				$data['success'] = '';
			}
			else
			{
				$data['errors'] = $user->getErrors();
			}
		}
		
		if ($user->isTeacher())
		{
			$data['teacher'] = TRUE;
		}
		
		$this->tpl->content = View::factory('account/index', $data);
	}

}
