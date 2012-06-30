<?php defined('SYSPATH') or die('No direct script access.');

class Basecontroller extends Controller
{
	protected $layout = 'template';
	protected $_auth = NULL; // экземпляр класса Auth
	
	public function before()
	{
		$this->_auth = Auth::instance();
		
		$this->checkAuth();
		$this->init();
		
		$this->tpl = View::factory($this->layout);
		$this->tpl->content = '';
		
		return parent::before();
	}
	
	public function after()
	{
		if (isset($this->menu)) {
			$this->tpl->menu = View::factory($this->menu);
		}
		
		$this->tpl->set_filename($this->layout);
		$this->response->body($this->tpl->render());
		
		return parent::after();
	}
	
	protected function init()
	{
	}
	
	protected function checkAuth()
	{
		if($this->_auth->logged_in() == 0)
		{
			Session::instance()->set('auth_redirect', $_SERVER['REQUEST_URI']);
			Request::initial()->redirect('auth/login.html');
		}
	}
	
	protected function checkRole($role, $excludePages=array())
	{
		if (in_array($this->request->action(), $excludePages)) return;
		
		if($this->_auth->logged_in($role) == 0) $this->prohibited();
	}
	
	protected function prohibited()
	{
		Request::initial()->redirect('/main/prohibited.html');
	}
	
	protected function isAuthorized($role)
	{
		if(0 != $this->_auth->logged_in($role))
		{
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
}
