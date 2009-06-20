<?php

class WAI
{
	const DB_MySQL 				= 'Database type mysql';
	const PHP5 					= 'Php5';
	const GD2 					= 'gd2';
	const XML 					= 'xml';
	const MEMORY_ALLOCATED 		= 'Memory allocated';
	const NOT_DEFINED_CLASSES 	= 'Not defined Classes';
	const FOLDER_EXISTS			= 'Folder exists';
	const SERVER_ROOT 			= 'Server Root';
	const FILE_WRITE 			= 'File write';
	const FOLDER_WRITE 			= 'Folder write';
	const TEMPORARY_FOLDER 		= 'Temporary Folder';
	const SERVER_SOFTWARE 		= 'Server Software';
	const MOD_REWRITE 			= 'mod_rewrite';
	
	static private $instance = null;
	static private $wiki_parser = null;
	
	private $html_string = '';
	private $style = '';
	private $logo = '';
	private $title = '';
	
	public static function setLanguage($lng = 'en_EN')
	{
		
	}
	
	public static function setStyle($css_file)
	{
		
	}
	
	public static function setTitle($title = 'Installer')
	{
		
	}
	
	public static function setLogo($logo_file)
	{
		
	}
	
	public static function text($string = '')
	{
		$wai = self::getInstance();
		$parser = self::getWikiParser();
		
		$wai->html_string .= $parser->parse($string);
	}
	
	public static function dropdownField($field_name, $label, $default_value = '', $field_description = '')
	{
		
	}
	
	public static function textField($field_name, $label, $default_value = '', $field_description = '')
	{
		
	}
	
	public static function textareaField($field_name, $label, $default_value = '', $field_description = '')
	{
		
	}
	
	public static function checkboxField($field_name, $label, $default_value = '', $field_description = '')
	{
		
	}
	
	public static function validateCustom($custom_class_name)
	{
		if(!class_exists($custom_class_name, true))
		{
			if(!is_readable('install/custom/' . $custom_class_name . '.php'))
			{
				throw new Exception('ClassFile "install/custom/' . $custom_class_name . '.php" Not Found');
			}
			
			include('install/custom/' . $custom_class_name . '.php');
		}
		
		if(!class_exists($custom_class_name, true))
		{
			throw new Exception('Class "' . $custom_class_name . '" Not Found');
		}
		$cls = new $custom_class_name();
		
		if($cls instanceof IWebApplicationInstaller_CustomScript)
		{
			if(!$cls->run())
			{
				self::errorMsg($cls->getErrorMsg());
			}
		}
		else
		{
			throw new Exception('Class "' . $custom_class_name . '" must be implementing IWebApplicationInstaller_CustomScript');
		}
	}
	
	public static function requestDatabaseSettings($type, $parameter = null)
	{
		
	}
	
	public static function requirePhpConfiguration($type, $parameter = null)
	{
		
	}
	
	public static function requirePermission($type, $parameter = null)
	{
		
	}
	
	public static function requireWebserverConfiguration($type, $parameter = null)
	{
		
	}
	
	private function translate($string)
	{
		return $string;
	}
	
	private function getHtmlHeader()
	{
		$wai = self::getInstance();
		
		$header = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
		<head>
		<title>' . $wai->translate($wai->title) . '</title>';
		
		if($wai->style != '')
		{
			$header .= '<link rel="stylesheet" type="text/css" href="' . $wai->style . '" />';
		}
		
		$header .= '</head><body>';
		
		if($wai->logo != '')
		{
			$header .= '<img src="' . $wai->logo . '" />';
		}
		
		return $header;
	}
	
	private function getHtmlFooter()
	{
		return '</body></html>';
	}
	
	public static function dispatch()
	{
		$wai = self::getInstance();
		
		echo $wai->getHtmlHeader() . $wai->html_string . $wai->getHtmlFooter();
	}
	
	public static function getWikiParser()
	{
		if(self::$wiki_parser === null)
		{
			require_once('install/lib/WikiParser.class.php');
			
			self::$wiki_parser = new WikiParser();
		}
		return self::$wiki_parser;
	}
	
	public static function getInstance()
	{
		if(self::$instance === null)
		{
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct(){}
	private function __clone(){}
}

interface IWebApplicationInstaller_CustomScript
{
	public function run();
	public function getErrorMsg();
}

?>