<?php
class ResultWrapper{
	public $code=0;
	public $message='';
	public $results;
	public function statusCode($kode){
		$status=array(
			200=>'OK',			
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
			);
		return ($status[$kode])?$status[$kode]:$status[500]; 
	}

}

?>