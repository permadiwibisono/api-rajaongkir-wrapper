<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__. '\model\ResultWrapper.php');
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
//https://www.cheatography.com/davechild/cheat-sheets/mod-rewrite/
//http://code.tutsplus.com/tutorials/a-deeper-look-at-mod_rewrite-for-apache--net-6708
//http://coreymaynard.com/blog/creating-a-restful-api-with-php/
//http://requiremind.com/a-most-simple-php-mvc-beginners-tutorial/
//echo __ROOT__;
$_request=$_SERVER['REQUEST_URI'];
$_method=$_SERVER['REQUEST_METHOD'];
$_key='0f89cac1a365b19d9232df468d89fd48';
$_apiUrl="http://api.rajaongkir.com/starter";
$_pathController=__ROOT__. '\\controller\\';
$_getController=($_GET)?$_GET['controller']:null;
$controller;
if(isset($_getController)){
	require_once($_pathController . $_getController . 'Controller.php');
	$type=$_getController . 'Controller';
	//echo $type;
	$controller=new $type;
	$controller->method=$_method;
	$controller->key=$_key;
	$controller->param=$_GET;
	$controller->basicUrl=$_apiUrl;
	echo json_encode($controller->Process());
}
else
{
	$result=new ResultWrapper;
	$result->code=500;
	$result->message=$result->statusCode(500);
	echo json_encode($result);
}
?>