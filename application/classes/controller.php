<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller extends Kohana_Controller
{

	// метод проверяет, была ли отправлена форма по наличию имени кнопки в массиве $_POST,
	// кроме того происходит проверка referer
	protected function isPressed($buttonName)
	{
		if (!isset($_POST[$buttonName]))
			return FALSE;
		
		// проверка referer
		if (!isset($_SERVER['HTTP_REFERER']))
			return TRUE;
		
		if (!preg_match('/^https?\:\/\/([^\/]+)\/.*$/i', $_SERVER['HTTP_REFERER'], $matches))
			return FALSE;
		
		if ($matches[1] != $_SERVER['HTTP_HOST'])
			return FALSE;
		
		return TRUE;
	}
	
}
