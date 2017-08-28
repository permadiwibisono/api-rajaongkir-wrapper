<?php
	//require_once('\model\ResultWrapper.php');
	require_once(__ROOT__.DS.'model'.DS.'City.php');
	require_once(__ROOT__.DS.'model'.DS.'Province.php');
	require_once(__ROOT__.DS.'controller'.DS.'AbstractAPI.php');
class cityController extends AbstractAPI{
    private $urlApi='';
    //private $result;
    function __construct() {
    }
    public function Process(){
    	switch($this->method)
    	{
    		case "GET":$result=$this->Get();break;
    		default:{
    			$result=$this->_response(500,'Method Not Allowed!');
    		} break;
    	}
    	return $result;
    }

    function Get()
    {
    	$curl = curl_init();
    	$this->urlApi=$this->basicUrl.'/city';
    	if(isset($this->param['id']))
    	{
    		$this->urlApi=$this->urlApi.'?id='.$this->param['id'];    		
    	}
    	if(isset($this->param['province']))
    	{
    		$this->urlApi=$this->urlApi.'&province='.$this->param['province'];    		
    	}
    	//echo $this->urlApi;
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
					$city=new City;
					$city->id=$rList->city_id;
					$city->province=new Province;
					$city->province->id=$rList->province_id;
					$city->province->name=$rList->province;
					$city->type=$rList->type;
					$city->name=$rList->city_name;
					$city->postal_code=$rList->postal_code;
					$result->results=$city;
				}
				else{	
					$list=array();				
					for($i=0;$i<count($rList);$i++)
					{
						$city=new City;
						$city->id=$rList[$i]->city_id;
						$city->province=new Province;
						$city->province->id=$rList[$i]->province_id;
						$city->province->name=$rList[$i]->province;
						$city->type=$rList[$i]->type;
						$city->name=$rList[$i]->city_name;
						$city->postal_code=$rList[$i]->postal_code;
						array_push($list,$city);
					}
					$result->results=$list;
				}
			}
		  return $result;
		}
    }
}

?>