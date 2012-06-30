<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Install extends Controller
{
	protected $layout = 'layouts/install';
	
	protected function checkInstalled()
	{
		if (is_readable(APPPATH.'config/database.php'))
		{
			Request::initial()->redirect('install/already_installed.html');
		}
	}
	
	protected function writeCfgFile($filename, $tplname, $data)
	{
		$content = View::factory($tplname, $data)->render();
		
		file_put_contents($filename, $content);
	}
	
	protected function setBody($view, $data=NULL)
	{
		$content = View::factory($view, $data);
		
		$body = View::factory($this->layout, array('content' => $content));

		$this->response->body($body);
	}

	public function action_index()
	{
		$this->checkInstalled();
		
		if($this->isPressed('btnSubmit'))
		{
			Request::initial()->redirect('install/step2.html');
		}
		
		$this->setBody('install/index');
	}
	
	public function action_already_installed()
	{
		$this->setBody('install/already_installed');
	}
	
	public function action_step2()
	{
		$this->checkInstalled();
		
		$data = array();
		$data['values'] = array();
		
		if($this->isPressed('btnSubmit'))
		{
			$values = Arr::extract($_POST, array('hostname', 'database', 'username', 'password'));
			
			$dbconf = array(
				'type'       => 'mysql',
				'connection' => array(
					'hostname'   => $values['hostname'],
					'database'   => $values['database'],
					'username'   => $values['username'],
					'password'   => $values['password'],
					'persistent' => FALSE,
				),
				'table_prefix' => '',
				'charset'      => 'utf8',
				'caching'      => FALSE,
				'profiling'    => TRUE,
			);
			
			try {
				$sql = 'SELECT 1+1';
				$db = Database::instance(NULL, $dbconf);
				$db->query(Database::SELECT, $sql, FALSE)->as_array();
				
				Session::instance()->set('dbconf', $values);
				
				Request::initial()->redirect('install/step3.html');
			}
			catch (Database_Exception $e)
			{
				$data['dberror'] = TRUE;
			}
			
			$data['values'] = $values;
		}
		
		$this->setBody('install/step2', $data);
	}
	
	public function action_step3()
	{
		$this->checkInstalled();
		
		$data = array();
		$data['values'] = array();
		
		$values = Arr::extract($_POST, array('hostname', 'username', 'password', 'port', 'timeout', 'localDomain', 'systemEmail', 'myemail'));
		
		if($this->isPressed('btnSubmit'))
		{
				$smtpconf = Session::instance()->get('smtpconf');
				
				if (!empty($smtpconf))
					Request::initial()->redirect('install/done.html');
				
				$data['needCheck'] = TRUE;
		}
		
		if($this->isPressed('btnCheck'))
		{
			$smtpconf = array(
				'driver' => 'smtp',
				'options' => array(
					'hostname' => $values['hostname'],
					'username' => $values['username'],
					'password' => $values['password'],
					'port'     => $values['port'],
					'timeout'  => $values['timeout'],
					'localDomain' => $values['localDomain'],
				),
				'systemEmail' => $values['systemEmail'],
			);
			
			try {
				$time = date('d.m.Y H:i:s', time());
				
				$email = Arr::get($_POST, 'myemail');
				
				$from    = array($values['systemEmail'], 'Администратор');
				$subject = 'Проверка настроек SMTP сервера';
				$message  = "Поздравляем !\n";
				$message .= "Вы получили это сообщение, отправленное в $time, значит настройки SMTP сервера правильные.\n";
				$message .= "Перейдите на страницу установки образовательной системы и нажмите кнопку 'Продолжить'\n";
				
				Email::connect($smtpconf);
				$sent = Email::send($email, $from, $subject, $message, FALSE);
				
				if ($sent == 0) throw new Exception('Error sending email message.');
				
				Session::instance()->set('smtpconf', $values);
				$data['time'] = $time;
			}
			catch (Exception $e)
			{
				$data['SMTPerror'] = TRUE;
			}
		}
		
		$data['values'] = $values;
		
		$this->setBody('install/step3', $data);
	}
	
	public function action_done()
	{
		$this->checkInstalled();
		
		$data = array();
		
		// создание конфигурационных файлов
		$dbconf   = Session::instance()->get('dbconf');
		$smtpconf = Session::instance()->get('smtpconf');
		
		$this->writeCfgFile(APPPATH.'config/auth.php', 'install/tpl_auth', array('hash' => Text::random('alnum', 64)));
		$this->writeCfgFile(APPPATH.'config/email.php', 'install/tpl_email', $smtpconf);
		$this->writeCfgFile(APPPATH.'config/database.php', 'install/tpl_database', $dbconf);
		
		// обновить bootstrap
		$bfn = APPPATH.'bootstrap.php';
		$bootstrap = file_get_contents($bfn);
		$newcookie = 'Cookie::$salt = \''.Text::random('alnum', 64).'\';';
		$bootstrap = preg_replace('/^Cookie\:\:\$salt\s=\s(.+)$/m', $newcookie, $bootstrap);
		
		file_put_contents($bfn, $bootstrap);
		
		// создание базы данных из дампа
		$dump = file_get_contents(APPPATH.'views/install/dump.sql');
		
		$queries = preg_split('/[;][\s]+[\n|\r]/', $dump);
		
		foreach ($queries as $sql)
		{
			$sql = trim($sql);
			
			if ($sql != '')
				DB::query(NULL, $sql)->execute();
		}
		
		// добавить пользователя "admin" и установить права администратора
		$login = $smtpconf['systemEmail'];
		$user = ORM::factory('user');
		$user->email    = $login;
		$user->username = $login;
		$user->password = 'admin';
		$user->name     = 'Администратор';
		$user->note     = '';
		$user->save();
		
		$user->setRole('admin');
		
		$data['login'] = $login;
		$this->setBody('install/done', $data);
	}
	
}
