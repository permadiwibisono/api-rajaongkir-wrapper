<?php
	require_once(__ROOT__.'\model\Province.php');
	require_once(__ROOT__.'\controller\AbstractAPI.php');
class provinceController extends AbstractAPI
{
    private $urlApi='';
    //private $result;
    function __construct() {
    }
    public function Process(){
    	switch($this->method)
    	{
    		case "GET":$result=$this->Get();break;
    		default:{
    			$result=$this->_response(500);
    		} break;

    	}
    	return $result;
    }

    function Get()
    {
		//echo 'masuk';
    	$curl = curl_init();
    	$this->urlApi=$this->basicUrl.'/province';
    	if(isset($this->param['id']))
    	{
    		$this->urlApi=$this->urlApi.'?id='.$this->param['id'];    		
    	}
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->urlApi,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => $this->method,
		  CURLOPT_HTTPHEADER => array(
		    "key: ".$this->key
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		if ($err) {
		  return  $this->_response(500,$err);
		} else {
			$obj=json_decode($response);
			//echo $response;
			if($obj->rajaongkir->status->code==400)
			{
				$result=$this->_response($obj->rajaongkir->status->code,$obj->rajaongkir->status->description);
			}
			else
			{
				$result=$this->_response($obj->rajaongkir->status->code,$obj->rajaongkir->status->description);
				$rList=$obj->rajaongkir->results;
				if(count($rList)==1)
				{
					$prov=new Province;
					$prov->id=$rList->province_id;
					$prov->name=$rList->province;
					$result->results=$prov;
				}
				else{	
					$list=array();				
					for($i=0;$i<count($rList);$i++)
					{
						$prov=new Province;
						$prov->id=$rList[$i]->province_id;
						$prov->name=$rList[$i]->province;
						array_push($list,$prov);
					}
					$result->results=$list;
				}
			}
		  return $result;
		}
    }
}

?>