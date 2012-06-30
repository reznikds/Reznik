<?php defined('SYSPATH') or die('No direct script access.');

class Validation extends Kohana_Validation
{
	public function add_empty_rule($rule)
	{
		$this->_empty_rules[] = $rule;
	}
}
