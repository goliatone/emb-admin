<?php defined('SYSPATH') OR die('No direct script access.');

class Helper_Admin
{
	
	public static function is_admin()
	{
		$url = 'http';
		if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {$url .= "s";}
		$url .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		
		return strrpos($url, "admin");
	}
	
}
