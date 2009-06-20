<?php

class CheckPreviousInstallstion implements IWebApplicationInstaller_CustomScript
{
    /**
     * @see IWebApplicationInstaller_CustomScript::run()
     */
    public function run()
    {
        //TODO Auto generated method stub
    }

    /**
     * @see IWebApplicationInstaller_CustomScript::getErrorMsg()
     */
    public function getErrorMsg()
    {
        return "'''Note:''' It seems as though SilverStripe is already installed here. If you ask me to install, I will overwrite the '''.htaccess''' and '''mysite/_config.php''' files.";
    }
}

?>