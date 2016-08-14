<?php
require_once(__ROOT__.'\model\ResultWrapper.php');
abstract class AbstractAPI
{
    public $param;
    public $method;
    public $key;
    public $basicUrl;
    function __construct() {
    }
    public function _response($code=200,$message='',$data=null)
    {
    	$result=new ResultWrapper();
    	$result->code=$code;
    	$result->message=$message==''?$result->statusCode($code):$message;
    	$result->results=$data;
    	return $result;
    }
}

?>