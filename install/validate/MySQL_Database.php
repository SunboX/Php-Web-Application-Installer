<?php

/**
 * Just check that the database configuration is okay
 */
class MySQL_Database implements IWebApplicationInstaller_Script
{
	private $error_msg = '';
	
    /**
     * @see IWebApplicationInstaller_Script::run()
     */
    public function run()
    {
    	if(!function_exists('mysql_connect'))
		{
			$this->error_msg = 'MySQL support not included in PHP.';
			return false;
		}
		
		$conn = @mysql_connect($server, null, null);

		if(!$conn || mysql_errno() >= 2000)
		{
			$this->error_msg = 'can\'t find the a MySQL server on ' . $databaseConfig[server] . ': ' . mysql_error();
			return false;
		}
		
		$conn = @mysql_connect($server, $username, $password);
		
		if(!$conn)
		{
			$this->error_msg = 'That username/password doesn\'t work';
			return false;
		}
		
		if(!mysql_get_server_info())
		{
			WAI::warningMsg('Cannot determine the version of MySQL installed. Please ensure at least version 4.1 is installed.');
		}
		else
		{
			list($majorRequested, $minorRequested) = explode('.', '4.1');
			$result = mysql_query('SELECT VERSION()');
			$row=mysql_fetch_row($result);
			$version = ereg_replace('([A-Za-z-])', '', $row[0]);
			list($majorHas, $minorHas) = explode('.', substr(trim($version), 0, 3));
						
			if(($majorHas < $majorRequested) || ($majorHas == $majorRequested && $minorHas < $minorRequested))
			{
				$this->error_msg = 'MySQL version 4.1 is required, you only have ' . $majorHas . '.' . $minorHas;
				return false;
			}
		}
		
		if(@mysql_select_db($database))
		{
			return true;
		}
		
		if(@mysql_query('CREATE DATABASE testing123'))
		{
			mysql_query('DROP DATABASE testing123');
			return true;

		}
		else
		{
			$this->error_msg = "user '$username' doesn't have CREATE DATABASE permissions.";
			return false;
		}
		
        return true; // no error
    }

    /**
     * @see IWebApplicationInstaller_Script::getErrorMsg()
     */
    public function getErrorMsg()
    {
        return $this->error_msg;
    }
}

?>