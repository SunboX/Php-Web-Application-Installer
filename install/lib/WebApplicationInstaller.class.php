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
	private $request_data;
	
	public static function setLanguage($lng = 'en_EN')
	{
		
	}
	
	public static function setStyle($css_file)
	{
		$wai = self::getInstance();
		$wai->style = $css_file;
	}
	
	public static function setTitle($title = 'Installer')
	{
		$wai = self::getInstance();
		$wai->title = $title;	
	}
	
	public static function setLogo($logo_file)
	{
		$wai = self::getInstance();
		$wai->logo_file = $logo_file;
	}
	
	public static function text($string = '')
	{
		$wai = self::getInstance();
		
		$wai->html_string .= $wai->translate($string);
	}
	
	public static function dropdownField($field_name, $label = '', $values = array(), $default_value = '', $field_description = '')
	{
		if(!is_array($values)) return;
		
		$wai = self::getInstance();
		$id = $field_name . '_' . microtime();
		
		if($label != '') $wai->html_string .= '<label for="' . $id . '">' . $wai->translate($label) . '</label>';
		$wai->html_string .= '<select name="' . $field_name . '" id="' . $id . '">';
		foreach($values as $value)
		{
			$wai->html_string .= '<option value="' . $value . '"' . ($value == $wai->getRequest($field_name, $default_value) ? ' selected="selected"' : '') . '>' . $wai->translate($value) . '</select>';
		}
		$wai->html_string .= '</select>';
		if($field_description != '') $wai->html_string .= '<span class="description">' . $wai->translate($field_description) . '</label>';
	}
	
	public static function textField($field_name, $label = '', $default_value = '', $field_description = '')
	{
		$wai = self::getInstance();
		$id = $field_name . '_' . microtime();
		
		if($label != '') $wai->html_string .= '<label for="' . $id . '">' . $wai->translate($label) . '</label>';
		$wai->html_string .= '<input type="text" name="' . $field_name . '" id="' . $id . '" value="' . $wai->getRequest($field_name, $default_value) . '" />';
		if($field_description != '') $wai->html_string .= '<span class="description">' . $wai->translate($field_description) . '</label>';
	}
	
	public static function textareaField($field_name, $label = '', $default_value = '', $field_description = '')
	{
		$wai = self::getInstance();
		$id = $field_name . '_' . microtime();
		
		if($label != '') $wai->html_string .= '<label for="' . $id . '">' . $wai->translate($label) . '</label>';
		$wai->html_string .= '<textarea name="' . $field_name . '" id="' . $id . '">' . $wai->getRequest($field_name, $default_value) . '</textarea>';
		if($field_description != '') $wai->html_string .= '<span class="description">' . $wai->translate($field_description) . '</label>';
	}
	
	public static function checkboxField($field_name, $label = '', $value = '', $checked = false, $field_description = '')
	{
		$wai = self::getInstance();
		$id = $field_name . '_' . microtime();
		
		if($label != '') $wai->html_string .= '<label for="' . $id . '">' . $wai->translate($label) . '</label>';
		$wai->html_string .= '<input type="checkbox" name="' . $field_name . '" id="' . $id . '" value="' . $value . '"' . ($wai->getRequest($field_name, $checked) ? ' checked="checked"' : '') . ' />';
		if($field_description != '') $wai->html_string .= '<span class="description">' . $wai->translate($field_description) . '</label>';
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
		switch($type)
		{
			case self::DB_MySQL:

				self::text('=== MySQL Database ===');

				WAI::textField('database_server', 'MySQL server:', 'localhost');
				WAI::textField('database_username', 'MySQL username:', 'root');
				WAI::textField('database_password', 'MySQL password:');
				WAI::textField('database_database', 'MySQL database:');
				
				break;
		}
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
	
	private function translate($string, $parse = true)
	{
		if($parse)
		{
			$parser = self::getWikiParser();
			return $parser->parse($string);	
		}
		return $string;
	}
	
	private function getRequest($name, $default = null)
	{
		if($this->request_data === null)
		{
			$this->request_data = array_merge($_POST, $_GET, $_COOKIE);
		}
		return isset($this->request_data[$name]) ? $this->request_data[$name] : $default;
	}
	
	private function getHtmlHeader()
	{
		$wai = self::getInstance();
		
		$header = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
		<head>
		<title>' . $wai->translate($wai->title, false) . '</title>';
		
		if($wai->style != '')
		{
			$header .= '<link rel="stylesheet" type="text/css" href="' . $wai->style . '" />';
		}
		
		$header .= '</head><body>';
		
		if($wai->logo_file != '')
		{
			$header .= '<img src="' . $wai->logo_file . '" />';
		}
		
		$header .= '<form name="install_fom" id="install_fom" method="post" action="' . $_SERVER['PHP_SELF'] . '" enctype="application/x-www-form-urlencoded" accept-charset="UTF-8">';
		
		return $header;
	}
	
	private function getHtmlFooter()
	{
		return '<input type="submit" name="submit" value="' . self::translate('Install') . '" class="action" /></body></html>';
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