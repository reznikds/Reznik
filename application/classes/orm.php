<?php defined('SYSPATH') or die('No direct script access.');

class ORM extends Kohana_ORM
{
	protected $_empty_rules = array();
	
	public function add_empty_rule($rule)
	{
		$this->_empty_rules[] = $rule;
	}
	
	protected function _validation()
	{
		parent::_validation();
		
		foreach ($this->_empty_rules as $rule)
		{
			$this->_validation->add_empty_rule($rule);
		}
	}
}
