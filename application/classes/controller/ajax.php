<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {

	public function action_checkPassword()
	{
		$pass = Arr::get($_POST, 'oldpass', '');
		
		$res = ORM::factory('user')->checkPassword($pass);
		
		echo json_encode(array('result' => $res));
	}
}
