<?php
set_time_limit(0);
ini_set('memory_limit','320M'); // make sure the application has enough memory

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('REMOTE_DEBUG', true);

if (APPLICATION_ENV !== 'production') {
    ini_set("display_errors",true);
    error_reporting(E_ALL);
 }

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));


// Ensure external common libraries are on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    "../../../ZendFramework/library",//ZF path on dev server
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

if(!defined('DONT_RUN_ZFAPP') || DONT_RUN_ZFAPP == false) {
    $application->bootstrap()->run();
}
