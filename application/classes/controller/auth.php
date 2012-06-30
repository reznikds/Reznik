<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth extends Basecontroller
{
	protected $layout = 'layouts/main';
	
	protected function checkAuth() {}
	
	public function action_index()
	{
throw new Exception('Deprecated.');
		$auth = Auth::instance();
		$data = array();
		
		if($auth->logged_in())
		{
			Request::initial()->redirect('');
		}
		else
		{
			if($this->isPressed('btnSubmit'))
			{
				$login = Arr::get($_POST, 'login', '');
				$password = Arr::get($_POST, 'password', '');
				
				if($auth->login($login, $password))
				{
					$session = Session::instance();
					$auth_redirect = $session->get('auth_redirect', '');
					$session->delete('auth_redirect');
					
					Request::initial()->redirect($auth_redirect);
				}
				else
				{
					$data['error'] = 'y';
				}
			}
		}
		$this->tpl->content =  View::factory('auth/login', $data);
	}
	
	public function action_login()
	{
		$data = array();
		$auth = Auth::instance();
		
		if($auth->logged_in()) Request::initial()->redirect('');
		
		if($this->isPressed('btnSubmit'))
		{
			$login = Arr::get($_POST, 'login', '');
			$password = Arr::get($_POST, 'password', '');
			
			if($auth->login($login, $password))
			{
				$auth_redirect = Session::instance()->get('auth_redirect', '');
				Session::instance()->delete('auth_redirect');
				
				Request::initial()->redirect($auth_redirect);
			}
			
			$data['error'] = 'y';
		}
		
		$this->tpl->content = View::factory('auth/login', $data);
	}
	
	public function action_registration()
	{
		$data = array();
		$data['values'] = array();
		
		if($this->isPressed('btnSubmit'))
		{
			$email   = Arr::get($_POST, 'email', '');
			$name    = Arr::get($_POST, 'name', '');
			$regcode = Arr::get($_POST, 'regcode', '');
			
			$user = ORM::factory('user');
			
			if ($user->register($email, $name, $regcode))
			{
				$data['success'] = '';
			}
			else
			{
				$data['errors'] = $user->getErrors();
				$data['values'] = $_POST;
			}
		}
		
		$this->tpl->content = View::factory('auth/registration', $data);
	}
	
	public function action_logout()
	{
		Auth::instance()->logout();
		Session::instance()->destroy();
		Request::initial()->redirect('');
	}

	public function action_restorepassword()
	{
		$data = array();
		
		if($this->isPressed('btnSubmit'))
		{
			$email = Arr::get($_POST, 'email', '');
			
			$user = ORM::factory('user');
			
			if($user->restorePassword($email))
			{
				$data['success'] = '';
			}
			else
			{
				$data['errors'] = $user->getErrors();
			}
		}
		
		$this->tpl->content =  View::factory('auth/restorepassword', $data);
	}
	
	public function action_generatenewpass()
	{
		$code = $this->request->param('id', NULL);
		
		$data = array();
		
		$user = ORM::factory('user');
		
		if($user->generateNewPass($code))
		{
			$data['success'] = '';
		}
		else
		{
			$data['errors'] = $user->getErrors();
		}
		
		$this->tpl->content =  View::factory('auth/generatenewpass', $data);
	}

}
