<?php defined('SYSPATH') or die('No direct script access.');

if (!function_exists('e'))
{
	function e($var)
	{
		return htmlspecialchars($var);
	}
}

class View extends Kohana_View {}
