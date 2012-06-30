<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Users extends Basecontroller
{
	protected $layout = 'layouts/main';
	
	public function init()
	{
		$this->checkRole('admin');
	}
	
	public function action_index()
	{
		throw new Exception('Not implemented');
	}
	
	public function action_upload()
	{
		$data = array();
		$data['errors'] = array();
		
		if($this->isPressed('btnSubmit'))
		{
			$user = ORM::factory('user');
			
			$file = Validation::factory($_FILES);
			$file->rule('codesFile', 'Upload::not_empty');
			$file->rule('codesFile', 'Upload::valid');
			$file->rule('codesFile', 'Upload::type', array(':value', array('csv')));
			$file->rule('codesFile', 'Upload::size', array(':value', '1M'));
			
			if ($file->check())
			{
				$savedfilename = Upload::save($file['codesFile'], 'tmpCodesList.csv', 'files');
				
				if ($savedfilename === FALSE)
					throw new Exception('Unable to save tmpCodesList.csv file!');
				
				$data['errors'] = $user->importUsers($savedfilename);
				unlink($savedfilename);
				
				if (count($data['errors']) == 0)
					$data['success'] = TRUE;
			}
			else
			{
				$data['errors'] = $file->errors('upload', FALSE);
			}
			
		}
		
		$this->tpl->content =  View::factory('admin/uploadusers', $data);
	}
	
	public function action_list()
       {
               $data = array();
               $filter = Session::instance()->get('userlistFilter', array());

                $user = ORM::factory('user');

               if ($this->isPressed('btnFilter'))
               {
                       $filter['FIO']      = trim(Arr::get($_POST, 'FIO'));
                       $filter['role']     = trim(Arr::get($_POST, 'role'));
                       $filter['isActive'] = trim(Arr::get($_POST, 'isActive'));
                       $filter['note']     = trim(Arr::get($_POST, 'note'));

                       foreach ($filter as $key => $value)
                       {
                               if ($value == '')
                                       unset($filter[$key]);
                       }

                       Session::instance()->set('userlistFilter', $filter);
               }

               if ($this->isPressed('btnDelete'))
               {
                       $idList = Arr::get($_POST, 'cb', array());
                       foreach ($idList as $id => $value)
                       {
                               $user = ORM::factory('user', $id);
                               $user->delete();
                       }
               }

               $user = ORM::factory('user');

             
               $data['notes'] = $user->getDistinctNotes();
               $data['filter'] = $filter;

               // получаем общее количество пользователей
               $count = ORM::factory('user')-> getUserList($filter)->count();

               // передаем значение количества пользователей в модуль pagination и формируем ссылки
               $pagination = Pagination::factory(array('total_items' => $count))->route_params
               (array('controller' => Request::current()->controller(), 'action' => Request::current()->action(),));

				$data['users'] = $user->getUserList($filter, $pagination);
              

               $data['pagination'] = $pagination;

               $this->tpl->content =  View::factory('admin/userlist', $data);
       }
	
	public function action_delete()
	{
		$id = $this->request->param('id', NULL);
		
		$user = ORM::factory('user', $id);
		$user->delete();
		
		Request::initial()->redirect('admin/users/list.html');
	}
	
	public function action_add()
	{
		$this->edit(0, 'add');
	}
	
	public function action_edit()
	{
		$id = (int) $this->request->param('id', NULL);
		
		$this->edit($id, 'edit');
	}
	
	protected function edit($id, $mode)
	{
		$data = array();
		
		$data['errors'] = array();
		
		$user = ORM::factory('user', $id);
		
		if ($this->isPressed('btnSubmit'))
		{
			if ($mode == 'edit')
				$action = 'saveUser';
			elseif(isset($_POST['confirmcode']))
				$action = 'createInactiveUser';
			else
				$action = 'createActiveUser';
			
			$user->setValidationName($action);
			
			$values = array();
			
			$pv = Validation::factory($_POST);
			if ($action != 'createInactiveUser')
			{
				$pv->rule('password', 'min_length', array(':value', '6'));
				$pv->rule('password_confirm',  'matches', array(':validation', ':field', 'password'));
			}
			
			switch ($action) {
				case 'saveUser':
					$values['email']       = trim(Arr::get($_POST, 'email'));
					$values['username']    = trim(Arr::get($_POST, 'email'));
					$values['password']    = trim(Arr::get($_POST, 'password'));
					$values['name']        = trim(Arr::get($_POST, 'name'));
					$values['confirmcode'] = trim(Arr::get($_POST, 'confirmcode'));
					$values['note']        = trim(Arr::get($_POST, 'note'));
				
					break;
				case 'createInactiveUser':
					$values['name']        = trim(Arr::get($_POST, 'name'));
					$values['confirmcode'] = trim(Arr::get($_POST, 'confirmcode'));
					$values['note']        = trim(Arr::get($_POST, 'note'));
					break;
				
				case 'createActiveUser':
					$values['email']       = trim(Arr::get($_POST, 'email'));
					$values['username']    = trim(Arr::get($_POST, 'email'));
					$values['password']    = trim(Arr::get($_POST, 'password'));
					$values['name']        = trim(Arr::get($_POST, 'name'));
					$values['confirmcode'] = '';
					$values['note']        = trim(Arr::get($_POST, 'note'));
					break;
			};
			
			if (isset($values['email']) && $values['email'] == '') unset($values['email']);
			if (isset($values['username']) && $values['username'] == '') unset($values['username']);
			if (isset($values['confirmcode']) && $values['confirmcode'] == '') unset($values['confirmcode']);
			if (isset($values['password']) && $values['password'] == '') unset($values['password']);
			
			$user->values($values);
			
			try {
				$user->save($pv);
				
				$role = trim(Arr::get($_POST, 'role'));
				
				if ($role != '') $user->setRole($role);
				
				Request::initial()->redirect('admin/users/list.html');
			} catch (ORM_Validation_Exception $e)
			{
				$data['errors'] = $e->errors('validation', FALSE);
				
				if (isset($data['errors']['_external']))
				{
					$data['errors'] = $data['errors'] + $data['errors']['_external'];
					unset($data['errors']['_external']);
				}
			}
		}
		
		$data['mode'] = $mode;
		$data['role'] = ($mode=='edit')?$user->getRole():'student';
		$data['values'] = $user->as_array();
		
		$this->tpl->content = View::factory('admin/useredit', $data);
	}
}
