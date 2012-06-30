<?php defined('SYSPATH') or die('No direct script access.');

class Model_Material extends ORM
{
	protected $filesDirectory = 'files/';
	
	public function __construct($id = NULL)
	{
		$this->add_empty_rule('checkMaterialExists');
		
		parent::__construct($id);
	}
	
	protected $_created_column = array(
		'column' => 'ctime',
		'format' => TRUE,
	);
	protected $_updated_column = array(
		'column' => 'mtime',
		'format' => TRUE
	);
	
	public function rules()
	{
		return array(
			'node_id' => array(
				array('not_empty'),
				array('digit'),
				array('check_id', array(':value', 'tree')),
			),
			'teacher_id' => array(
				array('not_empty'),
				array('digit'),
				array('check_id', array(':value', 'users')),
			),
			'subjectName' => array(
				array('not_empty'),
				array('max_length', array(':value', 250)),
			),
			'materialName' => array(
				array('not_empty'),
				array('max_length', array(':value', 1000)),
			),
			'access' => array(
				array('not_empty'),
				array('regex', array(':value', '/^(all|auth)$/')),
			),
			'filename' => array(
				array('max_length', array(':value', 50)),
			),
			'url' => array(
//				array('not_empty'),
				array('url'),
				array('max_length', array(':value', 250)),
				array(array($this, 'checkMaterialExists'), array(':value', 'materialFile')),
			),
		);
	}
	
	public function checkMaterialExists($value, $uploadedFileField)
	{
		// если url не пустой, то ок
		if ($value != '') return TRUE;
		
		// если файл уже был загружен ранее а сейчас нет, то ок
		if (is_file($this->filesDirectory.$this->filename))
			return TRUE;
		
		// далее проверяем, был ли загружен файл
		if (!isset($_FILES[$uploadedFileField]['name']))
			return FALSE;
		
		if ($_FILES[$uploadedFileField]['name'] != '')
			return TRUE;
		
		return FALSE;
	}
	
	// Возвращает список материалов по указанному преподавателю
	public function getMaterialsByTeacher($pagination, $teacher_id=NULL, $node_id=NULL, $filter=NULL)
	{
		$cat = new Model_Category;
		
		$qry = DB::select('materials.*',
			array('users.name', 'name')
		)
			->join('users', 'left')->on('users.id', '=', 'materials.teacher_id')
			->from('materials');
		
		if ($teacher_id)
		{
			$qry->where('teacher_id', '=', $teacher_id);
		}
		
		if ($node_id)
		{
			$qry->where('node_id', '=', $node_id);
		}
		
		if (isset($filter['FIO']) && ($filter['FIO'] != ''))
		{
			$qry->where('name', 'like', '%'.$filter['FIO'].'%');
		}
		
		if (!$teacher_id)
		{
			$qry->order_by('users.name', 'asc');
		}
		
		
		if($pagination) 
		{ 
			$data = $qry->order_by('subjectName', 'asc')
			->order_by('ctime', 'asc')
			->limit($pagination->items_per_page)
			->offset($pagination->offset)
			->execute()
			->as_array();
		} 
		else
		{
			$data = $qry->order_by('subjectName', 'asc')
			->order_by('ctime', 'asc')
			->execute()
			->as_array();
		}
		
		foreach ($data as $key => $item)
		{
			$data[$key]['path']   = $cat->getPath($item['node_id']);
			$data[$key]['isLeaf'] = $cat->isLeaf($item['node_id']);
			$data[$key]['link'] = $this->getLink($item);
		}
		
		return $data;
	}
	
	// Возвращает список преподавателей, сгруппированных по идентификатору ноды для вывода в дереве материалов
	public function getTeachersGropupByNode()
	{
		$data = DB::select('materials.*',
			array('users.name', 'name')
		)
			->from('materials')
			->join('users', 'left')->on('users.id', '=', 'materials.teacher_id')
			->group_by('materials.node_id')
			->group_by('materials.teacher_id')
			->order_by('users.name', 'asc')
			->execute();
//			->as_array();
		
		$result = array();
		foreach ($data as $item)
		{
			$idx = $item['node_id'];
			
			if (!isset($result[$idx]))
			{
				$result[$idx] = array();
			}
			
			$result[$idx][] = $item;
		}
		
		return $result;
	}
	
	public function getLink($item)
	{
		if ($item['filename'] != '')
		{
			return $this->filesDirectory.$item['filename'];
		}
		else
		{
			return $item['url'];
		}
	}
	
	// метод засчитывает загрузку материала и добавляет запись в таблицу статистики загрузки
	public function countDownload()
	{
		$auth = Auth::instance();
		
		if (!$auth->logged_in()) return;
		
		$user_id = $auth->get_user()->id;
		
		$stat = ORM::factory('download');
		
		$stat->material_id = $this->id;
		$stat->user_id = $user_id;
		$stat->ctime = time();
		
		$stat->save();
	}
	
	protected function mydateToUnixTime($mydate)
	{
		// вычисляем ctime из cdate
		$mydate = str_replace(array(',', '/', '-'), '.', $mydate);
		$parts = explode('.', $mydate);
		
		if (count($parts) != 3)
			throw new Exception('Invalid $mydate');
		
		$unixTime = mktime(0, 0, 0, (int) $parts[1], (int) $parts[0], (int) $parts[2]);
		
		if ($unixTime === FALSE)
			throw new Exception('Invalid $mydate');
		
		return $unixTime;
	}
	
	public function getStats($id, $filter=array())
	{
		$fltDateFrom = trim(Arr::get($filter, 'dateFrom', ''));
		$fltDateTo   = trim(Arr::get($filter, 'dateTo', ''));
		$fltFIO      = trim(Arr::get($filter, 'FIO', ''));
		
		if ($fltDateFrom)
			try {
				$fltDateFrom = $this->mydateToUnixTime($fltDateFrom);
			} catch (Exception $e) {
				$fltDateFrom = '';
			}
			
		if ($fltDateTo)
			try {
				$fltDateTo = $this->mydateToUnixTime($fltDateTo);
			} catch (Exception $e) {
				$fltDateTo = '';
			}
		
		$stats = ORM::factory('download');
		
		$stats->join('users', 'LEFT')->on('users.id', '=', 'download.user_id');
		$stats->where('material_id', '=', $id);
		
		if ($fltDateFrom)
			$stats->where('ctime', '>', $fltDateFrom);
		
		if ($fltDateTo)
			$stats->where('ctime', '<', $fltDateTo+86400);
		
		if ($fltFIO)
			$stats->where('name', 'like', '%'.$fltFIO.'%');
		
		$stats->order_by('ctime', 'desc');
		$result = $stats->find_all();
		
		return $result;
	}
	
	public function deleteFile() // really public ???
	{
		if (!$this->loaded())
			throw new Exception('Cannot delete file because model is not loaded.');
		
		if ($this->filename != '')
			@unlink($this->filesDirectory.$this->filename);
		
		$this->filename = '';
	}
	
	public function getFileName($extension) // really public ???
	{
		if (!$this->loaded())
			throw new Exception('Cannot ctreate filename because model is not loaded.');
		
		return $this->id.'.'.$extension;
	}
	
	public function delete()
	{
		$this->deleteFile();
		
		parent::delete();
	}
	
}
