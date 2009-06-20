<?php

require_once('install/lib/WebApplicationInstaller.class.php');

WAI::setLanguage('de_DE');

WAI::setStyle('install/custom/css/silverstripe.css');
WAI::setTitle('SilverStripe CMS Installation');
WAI::setLogo('install/custom/images/silverstripe.png');

WAI::text('== Welcome to SilverStripe ==');
WAI::text('Thanks for choosing to use SilverStripe! Please follow the instructions below to get SilverStripe installed.');

WAI::dropdownField(
	'template',
	'Template to install:',
	array(
		'mysite'
	),
	'mysite',
	'You can change the template or download another from the SilverStripe website after installation.'
);

WAI::checkboxField('send_information', 'Send information on my webserver to SilverStripe (this is only version information, used for statistical purposes)', true, true);

WAI::validateCustom('CheckPreviousInstallstion');

WAI::requestDatabaseSettings(WAI::DB_MySQL, array('min_version' => 4));

WAI::text('=== SilverStripe Administration Account ===');

WAI::textField(
	'admin_email',
	'Administrator email:',
	'',
	'We will set up 1 administrator account for you automatically. Enter the email address and password. If you\'d rather log-in with a username instead of an email address, enter that instead.'
);
WAI::textField('admin_password', 'Administrator password:');
WAI::textField('admin_first_name', 'Administrator first name:');
WAI::textField('admin_surname', 'Administrator surname:');

WAI::validateCustom('CreateAdminAccount');

WAI::text('=== Development Servers ===');

WAI::textareaField(
	'development_servers',
	'Development servers:',
	"localhost\n127.0.0.1",
	'SilverStripe allows you to run a site in [http://doc.silverstripe.com/doku.php?id=devmode development mode]. This shows all error messages in the web browser instead of emailing them to the administrator, and allows the database to be built without logging in as administrator. Please enter the host/domain names for servers you will be using for development.'
);

WAI::requirePhpConfiguration(WAI::PHP5);
WAI::requirePhpConfiguration(WAI::GD2);
WAI::requirePhpConfiguration(WAI::XML);
WAI::requirePhpConfiguration(WAI::DB_MySQL);
WAI::requirePhpConfiguration(WAI::MEMORY_ALLOCATED, array('min' => '32MB'));
WAI::requirePhpConfiguration(WAI::NOT_DEFINED_CLASSES, array(
	'Query',
	'HTTPResponse'
));

WAI::requirePermission(WAI::FOLDER_EXISTS, WAI::SERVER_ROOT);
WAI::requirePermission(WAI::FOLDER_EXISTS, 'mysite/');
WAI::requirePermission(WAI::FOLDER_EXISTS, 'sapphire/');
WAI::requirePermission(WAI::FOLDER_EXISTS, 'cms/');
WAI::requirePermission(WAI::FOLDER_EXISTS, 'jsparty/');
WAI::requirePermission(WAI::FILE_WRITE, '.htaccess');
WAI::requirePermission(WAI::FILE_WRITE, 'mysite/_config.php');
WAI::requirePermission(WAI::FOLDER_WRITE, 'assets/');
WAI::requirePermission(WAI::FOLDER_WRITE, WAI::TEMPORARY_FOLDER);

WAI::requireWebserverConfiguration(WAI::SERVER_SOFTWARE, array('min_version' => 'Apache/2'));
WAI::requireWebserverConfiguration(WAI::MOD_REWRITE);

WAI::dispatch();

?>
