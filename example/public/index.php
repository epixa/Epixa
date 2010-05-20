<?php

if (!defined('APPLICATION_ROOT')) {
    define('APPLICATION_ROOT', realpath(implode(DIRECTORY_SEPARATOR, array(
        dirname(__FILE__),
        '..'
    ))));
}

if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(implode(DIRECTORY_SEPARATOR, array(
        APPLICATION_ROOT,
        'application'
    ))));
}

if (!defined('APPLICATION_ENV')) {
    if (!$env = getenv('APPLICATION_ENV')) {
        $env = 'production';
    }
    
    define('APPLICATION_ENV', $env);
}

set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH,
    realpath(implode(DIRECTORY_SEPARATOR, array(
        APPLICATION_ROOT,
        '..',
        'library'
    ))),
    realpath(implode(DIRECTORY_SEPARATOR, array(
        APPLICATION_ROOT,
        'vendor',
        'Zend',
        'library'
    ))),
    get_include_path()
)));

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()
    ->registerNamespace('Epixa\\')
    ->registerNamespace('Zend_');

$application = new Epixa\Application(APPLICATION_ENV);
$application->bootstrap()->run();