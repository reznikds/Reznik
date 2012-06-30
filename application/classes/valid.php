<?php defined('SYSPATH') or die('No direct script access.');

class Valid extends Kohana_Valid
{
	public static function check_id($value, $tablename)
	{
		$id = (int) $value;
		
		if (! preg_match("/^[a-z_]+$/i", $tablename)) return FALSE;

		$count = DB::select(array('COUNT("*")', 'total_count'))
			->from($tablename)
			->where('id', '=', $id)
			->execute()
			->get('total_count');

		if ($count != 1) return FALSE;
		
		return TRUE;
	}
	
	public static function mydate($value)
	{
		$value = str_replace(array(',', '/', '-'), '.', $value);
		
		if (! preg_match("/^\d{2}.\d{2}.(\d{2}|\d{4})$/i", $value)) return FALSE;
		
		$parts = explode('.', $value);

		$day	= (int) $parts[0];
		$month	= (int) $parts[1];
		$year	= (int) $parts[2];

		if (!checkdate($month, $day, $year)) return FALSE;

		return TRUE;
	}

}
