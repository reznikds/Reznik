<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Loginform extends Controller
{
	public function action_index()
	{
		$viewtype = $this->request->param('id', NULL);
		
		//Закрываем доступ к контроллеру первичным запросам
		if($this->request->is_initial())
		{
			throw new HTTP_Exception_404('File not found!');
		}

		$auth = Auth::instance();
		if($auth->logged_in())
		{
			if($auth->logged_in('admin'))
			{
				switch ($viewtype)
				{
					case 1:
						$this->response->body(View::factory('authform/authformlogoutadminviewaccount'));
						break;
					case 2:
						$this->response->body(View::factory('authform/authformlogoutadminviewadmins'));
						break;
					default:
						$this->response->body(View::factory('authform/authformlogoutadminview'));
				}
			}
			else
			{
				if($viewtype)
				{
					$this->response->body(View::factory('authform/authformlogoutviewaccount'));
				}
				else
				{
					$this->response->body(View::factory('authform/authformlogoutview'));
				}

			}
		}
		else
		{
			$this->response->body(View::factory('authform/authformloginview'));
		}

	}

}
