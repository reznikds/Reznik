<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_User extends Model_Auth_User
{
	protected $_errors = array();
	protected $_validationName = FALSE;
	
	public function rules()
	{
		$method = 'rules_'.$this->_validationName;
		if (method_exists($this, $method)) return $this->$method();
		
		return array(
			
			'username' => array(
				array('not_empty'),
				array('min_length', array(':value', 4)),
				array('max_length', array(':value', 32)),
				array(array($this, 'unique'), array('username', ':value')),
			),
			'password' => array(
				array('not_empty'),
			),
			'email' => array(
				array('not_empty'),
				array('max_length', array(':value', 127)),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			),
			'name' => array(
				array('not_empty'),
				array('min_length', array(':value', 3)),
				array('max_length', array(':value', 20)),
			),
		);
	}
	
	public function rules_createActiveUser()
	{
		return array(
			'username' => array(
				array('not_empty'),
				array('max_length', array(':value', 32)),
				array(array($this, 'unique'), array('username', ':value')),
			),
			'password' => array(
				array('not_empty'),
			),
			'email' => array(
				array('not_empty'),
				array('max_length', array(':value', 127)),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			),
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 100)),
			),
		);
	}
	
	public function rules_createInactiveUser()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 100)),
			),
			'confirmcode' => array(
				array('not_empty'),
				array('max_length', array(':value', 64)),
			),
		);
	}
	
	public function rules_saveUser()
	{
		return array(
			'username' => array(
				array('not_empty'),
				array('max_length', array(':value', 32)),
				array(array($this, 'unique'), array('username', ':value')),
			),
			'password' => array(
//				array('not_empty'),
			),
			'email' => array(
				array('not_empty'),
				array('max_length', array(':value', 127)),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			),
		);
	}
	
	public function setValidationName($name)
	{
		$this->_validationName = $name;
	}
	
	public function delete()
	{
		if (!$this->loaded())
			throw new Exception('Cannot delete user because model is not loaded.');
		
		$materials = ORM::factory('material')
			->where('teacher_id', '=', $this->id)
			->find_all();
		
		foreach ($materials as $material)
		{
			$material->delete();
		}
		
		parent::delete();
	}
	
	public function getErrors()
	{
		return $this->_errors;
	}

	public function register($email, $name, $code)
	{
		$this->_errors = array();
		
		$user = ORM::factory('user')->where('confirmcode', '=', $code)->find();
		
		if (!$user->loaded())
		{
			$this->_errors['regcode'] = Kohana::message('messages', 'regcode');
			return FALSE;
		}
		
		$user->username = $email;
		$user->email = $email;
		$user->name = $name;
		
		//Генерируем пароль
		$pass = Text::random('alnum', 8);
		$user->password = $pass;
		
		try
		{
			$user->save();
		}
		catch(ORM_Validation_Exception $e)
		{
			$this->_errors = $e->errors('validation', FALSE);
			return FALSE;
		}
		
		//Отправка эл. почты
		$data = array(
			'email'    => $email,
			'pass'     => $pass,
			'hostname' => $_SERVER['HTTP_HOST'],
		);
		
		try
		{
			$from    = Kohana::$config->load('email.systemEmail');
			$subject = Kohana::message('messages', 'registrationSubject');
			$message = View::factory('email/registered', $data);
			$sent = Email::send($email, $from, $subject, $message, FALSE);
			
			if ($sent == 0) throw new Exception('Error sending email message.');
		}
		catch(Exception $e)
		{
			$user->delete();
			
			$this->_errors['emailsend'] = Kohana::message('messages', 'emailsend');
			return FALSE;
		}
		
		//Деактивация регистрационного кода
		$user->confirmcode = NULL;
		$user->save();
		
		return TRUE;
	}
	
	public function getLoggedInUsername()
	{
		$user = Auth::instance()->get_user();
		
		return $user->username;
	}
	
	public function changePassword($oldpass, $newpass, $newpassConfirm)
	{
		$data = array(
			'oldpass' => $oldpass,
			'newpass' => $newpass,
			'newpassConfirm' => $newpassConfirm
		);
		
		$validation = Validation::factory($data);
		$validation->rule('oldpass', 'not_empty');
		$validation->rule('oldpass', 'alpha_numeric');
		$validation->rule('oldpass', array($this, 'checkPassword'));
		$validation->rule('newpass', 'not_empty');
		$validation->rule('newpass', 'alpha_numeric');
		$validation->rule('newpass', 'matches', array(':validation', 'newpass', 'newpassConfirm'));
		
		if(!$validation->check())
		{
			$this->_errors = $validation->errors('catErrors');
			return FALSE;
		}
		
		$userId = Auth::instance()->get_user();
		
		$user = ORM::factory('user', $userId);
		$user->password = $newpass;
		$user->save();
		
		return TRUE;
	}
	
	public function checkPassword($password)
	{
		return Auth::instance()->check_password($password);
	}
	
	public function restorePassword($email)
	{
		$user = ORM::factory('user', array('username'=>$email));
		
		if(!$user->loaded())
		{
			$this->_errors['email'] = Kohana::message('messages', 'emailnotfound');
			return FALSE;
		}
		
		$code = Text::random('alnum', 48);
		
		//Отправка эл. почты
		$data = array(
			'code'     => $code,
			'hostname' => $_SERVER['HTTP_HOST'],
		);
		
		try
		{
			$from    = Kohana::$config->load('email.systemEmail');
			$subject = Kohana::message('messages', 'requestNewPassSubject');
			$message = View::factory('email/restorepassword', $data);
			$sent = Email::send($email, $from, $subject, $message, FALSE);
			
			if ($sent == 0) throw new Exception('Error sending email message.');
		}
		catch(Exception $e)
		{
			$this->_errors['emailsend'] = Kohana::message('messages', 'emailsend');
			return FALSE;
		}
		
		$user->confirmcode = $code;
		$user->save();
		
		return TRUE;
	}

	public function generateNewPass($code)
	{
		$user = ORM::factory('user', array('confirmcode'=>$code));
		
		if(!$user->loaded())
		{
			$this->_errors['emailsend'] = Kohana::message('messages', 'confirmcodenotfound');
			return FALSE;
		}
		
		$pass = Text::random('alnum', 8);
		$user->password = $pass;
		$user->confirmcode = NULL;
		
		//Отправка эл. почты
		$data = array(
			'email'    => $user->email,
			'pass'     => $pass,
			'hostname' => $_SERVER['HTTP_HOST'],
		);
		
		try
		{
			$from    = Kohana::$config->load('email.systemEmail');
			$subject = Kohana::message('messages', 'requestNewPassSubject');
			$message = View::factory('email/restorepassword2', $data);
			$sent = Email::send($user->email, $from, $subject, $message, FALSE);
			
			if ($sent == 0) throw new Exception('Error sending email message.');
		}
		catch(Exception $e)
		{
			$this->_errors['emailsend'] = Kohana::message('messages', 'emailsend');
			return FALSE;
		}
		
		$user->save();
		
		return TRUE;
	}
	
	public function importUsers($filename)
	{
		$errors = array();
		$data = $this->getUsersData($filename);
		
		foreach ($data as $user)
		{
			$err = $this->validateRegCode($user);
			if (count($err) == 0)
			{
				list($insert_id, $affected_rows) =
				DB::insert('users', array('confirmcode', 'note'))
					->values(array($user['code'], $user['note']))
					->execute();
				
				$obj = ORM::factory('user', $insert_id);
				$obj->setRole($user['role']);
			}
			else
			{
				foreach ($err as $e)
					$errors[] = $user['role'].' - '.$e;
			}
		}
		
		return $errors;
	}
	
	// проверяет регистрационный код
	protected function validateRegCode($data)
	{
		$validation = Validation::factory($data);
		$validation->rule('code', 'not_empty');
		$validation->rule('code', 'max_length', array(':value', '50'));
		$validation->rule('code', array($this, 'regcodeUnique'), array(':value'));
		$validation->rule('role', 'not_empty');
		$validation->rule('role', 'in_array', array(':value', array('admin', 'teacher', 'student')));
		
		if(!$validation->check())
		{
			return $validation->errors('regcodes');
		}
		
		return array();
	}
	
	// проверяет уникальность регистрационного кода
	public function regcodeUnique($value)
	{
		$regcode = DB::select()
			->from('users')
			->where('confirmcode', '=', $value)
			->execute()
			->current();
		
		if ($regcode)
			return FALSE;
		
		return TRUE;
	}
	
	// обрабатывает csv файл со списком пользователей и возвращает массив данных
	protected function getUsersData($filename)
	{
		if (($handle = fopen($filename, 'r')) === FALSE)
		{
			fclose($handle);
			throw new Exception('Error: file '.$filename.' is not readable.');
		}
		
		$data = array();
		
		while (($row = fgetcsv($handle, 1000, ';', '"')) !== FALSE)
		{
			$item = array(
				'code' => $row[0],
				'role' => $row[1],
				'note' => $row[2],
			);
			
			$data[] = $item;
		}
		
		fclose($handle);
		
		return $data;
	}
	
	public function getTeachers()
	{
		$role = ORM::factory('role', array('name' => 'teacher'));
		$users = $role->users->order_by('name', 'ASC')->find_all();
		
		return $users;
	}
	
	public function isTeacher()
	{
		if (Auth::instance()->logged_in('teacher'))
			return TRUE;
		
		return FALSE;
	}
	
	public function isAdmin()
	{
		if (Auth::instance()->logged_in('admin'))
			return TRUE;
		
		return FALSE;
	}
	
	public function getUserList($filter, $pagination = '')
       {
               $user = ORM::factory('user')
                       ->select('user.*', array('COUNT("role_id")', 'numroles'))
                       ->join('roles_users', 'left')->on('roles_users.user_id', '=', 'user.id')
                       ->group_by('user.id')
                       ->order_by('numroles', 'DESC')
                       ->order_by('name', 'ASC');

               if (isset($filter['FIO']))
                       $user->where('name', 'like', '%'.$filter['FIO'].'%');

               if (isset($filter['role']))
               {
                       if ($filter['role'] == 'teacher')
                       {
                               $role = ORM::factory('role', array('name' => 'teacher'));
                               $user->where('role_id', '=', $role->id);
                       }
                       else
                       {
                               $user->having('numroles', '=', 1); // количество ролей = 1, включая роль login
                       }
               }

               if (isset($filter['isActive']))
               {
                       if ($filter['isActive'] == 'yes')
                       {
                               $user->where('confirmcode', 'is', NULL);
                       }
                       else
                       {
                               $user->where('confirmcode', 'is not', NULL);
                       }
               }

               if (isset($filter['note']))
                       $user->where('note', '=', $filter['note']);

					if($pagination)
					{  
						return $user->limit($pagination->items_per_page)->offset($pagination->offset)->find_all(); 
					}
					else 
					{  
						return $user->find_all(); 
					}
					   
       }
	
	// возвращает список всех возможных примечаний
	public function getDistinctNotes()
	{
		$result = DB::select(array('DISTINCT("note")', 'note'))
			->from('users')
			->where('note', '!=', '')
			->execute();
		
		return $result;
	}
	
	// Получает роль пользователя
	public function getRole()
	{
		if (!$this->loaded())
			throw new Exception('Cannot get role because model is not loaded.');
		
		if ($this->has('roles', ORM::factory('role', array('name' => 'admin'))))
		{
			return 'admin';
		}
		elseif ($this->has('roles', ORM::factory('role', array('name' => 'teacher'))))
		{
			return 'teacher';
		}
		
		return 'student';
	}
	
	// Устанавливает роль пользователю
	public function setRole($role)
	{
		if (!$this->loaded())
			throw new Exception('Cannot set role because model is not loaded.');
		
		// удаляем все роли
		DB::delete('roles_users')
			->where('user_id', '=', $this->id)
			->execute();
		
		// добавляем роль "login" для всех
		$this->add('roles', ORM::factory('role', array('name' => 'login')));
		
		// добавляем соответствующую роль
		switch($role)
		{
			case 'admin':
				$this->add('roles', ORM::factory('role', array('name' => 'admin')));
				$this->add('roles', ORM::factory('role', array('name' => 'teacher')));
				break;
			case 'teacher':
				$this->add('roles', ORM::factory('role', array('name' => 'teacher')));
				break;
		}
	}
	
}
