<?php
	//require_once('\model\ResultWrapper.php');
	require_once(__ROOT__.'\model\Cost.php');
    require_once(__ROOT__.'\controller\cityController.php');
    require_once(__ROOT__.'\controller\AbstractAPI.php');
class costController extends AbstractAPI{
    private $urlApi='';
    //private $result;
    function __construct() {
    }

    public function Process(){
    	switch($this->method)
    	{
    		case "POST":$result=$this->Post();break;
    		default:{
                $result=$this->_response(500);
    		} break;
    	}
    	return $result;
    }
    public function Post(){    	
		$origin=457; //origin city id
		$destination=$this->param["destination"];
		$weight=$this->param["weight"];
		$courier=$this->param["courier"];
		$city=$this->GetCity($destination);
        if(is_null($city))
        {
            return $result=$this->_response(500);
        }
        $cost=$this->Cost($city,$origin,$weight,$courier);
        return $cost;
    }
    function Cost($city,$origin,$weight,$courier)
    {
        $curl2=curl_init();
        $this->urlApi=$this->basicUrl.'/cost';
        curl_setopt_array($curl2, array(
          CURLOPT_URL => $this->urlApi,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $this->method,
          CURLOPT_POSTFIELDS => "origin=". $origin ."&destination=" .$city->id. "&weight=".$weight."&courier=".$courier,
          CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: ".$this->key
          ),
        ));
        $response2 = curl_exec($curl2);
        $err2 = curl_error($curl2);
        curl_close($curl2);

        if ($err2) {
            return $result=$this->_response(500,$err);
        }
        else{
            $obj=json_decode($response2);
            $result=$this->_response($obj->rajaongkir->status->code,$obj->rajaongkir->status->description);
            $rList=$obj->rajaongkir->results[0]->costs;
            if ($obj->rajaongkir->status->description=="OK") 
            {
                 
                /*$list=array();              
                for($i=0;$i<count($rList);$i++)
                {
                    $cost=new Cost;
                    $cost->service=$rList[$i]->service;
                    $cost->description=$rList[$i]->description;
                    $cost->cost=$rList[$i]->cost;
                    array_push($list,$cost);
                }*/
                $cost=new Cost;
                if(!is_null($this->param["service"]))
                {
                    $service=strtolower($this->param["service"]);
                    for ($i=0; $i < count($rList); $i++) { 
                    if (strtolower($rList[$i]->service)==$service) {
                        $cost->service=$rList[$i]->service;
                        $cost->description=$rList[$i]->description;
                        $cost->cost=$rList[$i]->cost[0];
                      break;   
                    }
                    else
                    {
                       if ($service=="reg") {
                          if (strtolower($rList[$i]->service)=="ctc") {
                            $cost->service=$rList[$i]->service;
                            $cost->description=$rList[$i]->description;
                            $cost->cost=$rList[$i]->cost[0];
                            break;   
                          }
                       }
                       else if ($service=="yes") {
                          if (strtolower($rList[$i]->service)=="ctcyes") {
                            $cost->service=$rList[$i]->service;
                            $cost->description=$rList[$i]->description;
                            $cost->cost=$rList[$i]->cost[0];
                            break;   
                          }
                       }

                    }
                  }  

                }
                else
                {
                    $result=$this->_response(404,'Please provide Service!');
                }
                if(is_null($cost->service))
                {
                    $result=$this->_response(404,'Service not found!');                    
                }
                $result->results=$cost;
            }
            return $result;       
        }
    }
    function GetCity($destination){
    	$cityCtrl=new cityController();
    	$cityCtrl->key=$this->key;
    	$cityCtrl->method='GET';
        $cityCtrl->basicUrl=$this->basicUrl;
    	$cityList=$cityCtrl->Get();
        $city=null;
		for($i=0;$i<count($cityList->results);$i++)
		{
			if(strtolower($cityList->results[$i]->name)==strtolower($destination))
			{
				$city=$cityList->results[$i];
				break;
			}
		}
    	return $city;
    }
}

?>