<?php
error_reporting(E_ALL|E_STRICT); 
ini_set('display_errors', true); 
$rootDir = dirname(dirname(__FILE__));
//echo $rootDir . '/public_html/library'. PATH_SEPARATOR . get_include_path();
set_include_path( $rootDir . '/public_html/library'. PATH_SEPARATOR . get_include_path()); 
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Debug');
Zend_Loader::loadClass('Zend_Controller_Front');
// setup controller
$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(true); 
$frontController->setControllerDirectory('../application/controllers');
// run!
$frontController->dispatch();

?>