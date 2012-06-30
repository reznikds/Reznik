<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Materials extends Basecontroller
{
	protected $layout = 'layouts/main';
	
	public function init()
	{
		$this->checkRole('teacher');
	}
	
	public function action_index()
	{
		$data = array();

		$filter = Session::instance()->get('materialsFilter', array());
		
		$user = Auth::instance()->get_user();
		
		if ($this->isPressed('btnFilter'))
		{
			$filter['FIO'] = Arr::get($_POST, 'FIO');
			
			Session::instance()->set('materialsFilter', $filter);
		}
		
		$material = ORM::factory('material');
		  
		// получаем общее количество материалов
		$count = count( ORM::factory('material')-> getMaterialsByTeacher('', NULL, NULL, $filter) );
		
		// передаем значение количества материалов в модуль pagination и формируем ссылки
		$pagination = Pagination::factory(array('total_items' => $count))->route_params
		(array('controller' => Request::current()->controller(), 'action' => Request::current()->action(),));

		$data['pagination'] = $pagination;
		  
		  if ($user->isAdmin())
		  {
			 $data['materials'] = $material->getMaterialsByTeacher($pagination, NULL, NULL, $filter);
		  }
		  else
		  {
			 $data['materials'] = $material->getMaterialsByTeacher($pagination, $user->id);
		  }
			
		$data['isAdmin'] = $user->isAdmin();
		$data['filter'] = $filter;
		
		$this->tpl->content = View::factory('materials/index', $data);
	}
	
	public function action_stats()
	{
		$data = array();
		$errors = array();
		$filter = Session::instance()->get('statFilter', array());
		
		if ($this->isPressed('btnFilter'))
		{
			$filter['FIO']      = Arr::get($_POST, 'FIO');
			$filter['dateFrom'] = Arr::get($_POST, 'dateFrom');
			$filter['dateTo']   = Arr::get($_POST, 'dateTo');
			
			Session::instance()->set('statFilter', $filter);
			
			if (($filter['dateFrom'] != '') && !Valid::mydate($filter['dateFrom']))
				$errors['dateFrom'] = 'Дата должна быть в формате dd.mm.yyyy';
			
			if (($filter['dateTo'] != '') && !Valid::mydate($filter['dateTo']))
				$errors['dateTo'] = 'Дата должна быть в формате dd.mm.yyyy';
		}
		
		$material_id = $this->request->param('id', NULL);
		
		$material = ORM::factory('material', $material_id);
		
		$data['materialName'] = $material->materialName;
		$data['stats'] = $material->getStats($material_id, $filter);
		$data['count'] = count($data['stats']);
		$data['filter'] = $filter;
		$data['errors'] = $errors;
		
		$this->tpl->content = View::factory('materials/stats', $data);
	}
	
	public function action_add()
	{
		$this->edit(NULL, 'add');
	}
	
	public function action_edit()
	{
		$id = $this->request->param('id', NULL);
		$this->edit($id, 'edit');
	}
	
	public function edit($id, $mode)
	{
		$data = array();
		$data['errors'] = array();
		$data['mode'] = $mode;
		
		$material = ORM::factory('material', $id);
		$user = ORM::factory('user');
		
		$isAdmin = $user->isAdmin();
		$data['isAdmin'] = $isAdmin;
		
		if ($this->isPressed('btnSubmit'))
		{
			$file = Validation::factory($_FILES);
//			$file->rule('materialFile', 'Upload::not_empty'));
			$file->rule('materialFile', 'Upload::valid');
			$file->rule('materialFile', 'Upload::type', array(':value', array(
				'jpg', 'gif', 'png', 'tif', 'tiff',
				'pdf', 'djv', 'djvu', 'txt', 'doc', 'docx', 'xls', 'xlsx', 'odt', 'ods', 'odp', 'odg', 'odm',
				'7z', 'arj', 'rar', 'zip',
				'mp3', 'avi', 'mkv', 'mp4', 'flv',
			)));
			$file->rule('materialFile', 'Upload::size', array(':value', '50M'));
			
			if (!$isAdmin)
			{
				$_POST['teacher_id'] = Auth::instance()->get_user()->id;
			}
			$material->values($_POST);
			
			$materialError = $fileError = FALSE;
			try {
				$material->check();
			} catch (ORM_Validation_Exception $e) {
				$materialError = TRUE;
				$data['errors'] = $e->errors('validation', FALSE);
			}
			
			if (!$file->check())
			{
				$fileError = TRUE;
				$data['errors'] = Arr::merge($data['errors'], $file->errors('upload', FALSE));
			}
			
			if (!$materialError && !$fileError)
			{
				$material->save();
				
				// Если файл был загружен, то обработать его
				if (!empty($_FILES['materialFile']['tmp_name']))
				{
					// удаляем старый файл, если имеется
					$material->deleteFile();
					
					//определяем имя файла, под которым будет записан материал
					$path_parts = pathinfo($_FILES['materialFile']['name']);
					$filename = $material->getFileName($path_parts['extension']);
					$material->filename = $filename;
					$material->url = ''; // если мы загружаем файл, то ссылка на сторонний ресурс бессмысленна
					
					$savedfilename = Upload::save($file['materialFile'], $filename, 'files');
					
					if ($savedfilename === FALSE)
						throw new Exception('Unable to save uploaded file!');
				}
				
				// если не было загрузки файла а был установлен URL, то удалить файл, если таковой имеется
				if ($material->url != '')
					$material->deleteFile();
				
				$material->save();
				
				Request::initial()->redirect('materials.html');
			}
		}
		
		$data['values'] = $material->as_array();
		
		$cat = new Model_Category;
		$data['tree'] = $cat->getTree();
		$data['teachers'] = $user->getTeachers();
		
		$this->tpl->content = View::factory('materials/edit', $data);
	}
	
	public function action_delete()
	{
		$material_id = $this->request->param('id', NULL);
		
		$material = ORM::factory('material', $material_id);
		
		$material->delete();
		
		Request::initial()->redirect('materials.html');
	}
	
}