<?php

class CheckPreviousInstallstion implements IWebApplicationInstaller_CustomScript
{
    /**
     * @see IWebApplicationInstaller_CustomScript::run()
     */
    public function run()
    {
        $alreadyInstalled = false;
		if(file_exists('mysite/_config.php'))
		{
			// Find the $database variable in the relevant config file without having to execute the config file
			if(preg_match('/\\\$database\s*=\s*[^\n\r]+[\n\r]/', file_get_contents('mysite/_config.php'), $parts))
			{
				eval($parts[0]);
				if($database)
				{
					$alreadyInstalled = true;
					// Assume that if $databaseConfig is defined in mysite/_config.php, then a non-environment-based installation has
					// already gone ahead
				}
				else if(preg_match('/\\\$databaseConfig\s*=\s*[^\n\r]+[\n\r]/', file_get_contents('mysite/_config.php'), $parts))
				{
					$alreadyInstalled = true;
				}
			}
		}
		
		if($alreadyInstalled)
		{
			WAI::warningMsg("'''Note:''' It seems as though SilverStripe is already installed here. If you ask me to install, I will overwrite the '''.htaccess''' and '''mysite/_config.php''' files.");
		}
		
		return true;
    }

    /**
     * @see IWebApplicationInstaller_CustomScript::getErrorMsg()
     */
    public function getErrorMsg()
    {
        return '';
    }
}

?>