<?php defined('SYSPATH') or die('No direct script access.');

class Model_Download extends ORM
{
	protected $_created_column = array(
		'column' => 'ctime',
		'format' => TRUE,
	);
	
	protected $_belongs_to = array(
		'user' => array()
	);
}
