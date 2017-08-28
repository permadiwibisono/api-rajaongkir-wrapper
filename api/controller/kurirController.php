<?php
	//require_once('\model\ResultWrapper.php');
	require_once(__ROOT__.DS.'model'.DS.'Kurir.php');
    require_once(__ROOT__.DS.'controller'.DS.'costController.php');
    require_once(__ROOT__.DS.'controller'.DS.'AbstractAPI.php');
class kurirController extends AbstractAPI{
    private $urlApi='';
    //private $result;
    function __construct() {
    }

    public function Process(){
    	switch($this->method)
    	{
    		case "POST":$result=$this->Post();break;
    		default:{
                $result=$this->_response(500,'Method Not Allowed!');
    		} break;
    	}
    	return $result;
    }
    public function Post(){    	
		$origin=$this->param["origin"]; //origin city id
		$destination=$this->param["destination"];
		$weight=$this->param["weight"];
		$courier=$this->param["courier"];
        $kurir=$this->Kurir($destination,$origin,$weight,$courier);
        return $kurir;
    }
    function Kurir($destination,$origin,$weight,$courier)
    {
        $list=explode(',', $courier);
        $kurirList=array();
        foreach ($list as $key) {
            $result=$this->GetCost($origin,$destination,$key,$weight);
            if($result->code==200)
            {
                if(count($result->results->costs)>0)
                {
                    $k=new Kurir();
                    $k->code=$result->results->code;
                    $k->name=strtoupper($result->results->name);
                    array_push($kurirList,$k);
                }
            }
        }
        if(count($kurirList)>0)
        {
            $result=$this->_response(200,'');
            $result->results=$kurirList;
            return $result;
        }
        return $result=$this->_response(404,'Not Found');
    }
    function GetCost($origin,$destination,$courier,$weight)
    {
        $costCtrl=new costController();
        $costCtrl->key=$this->key;
        $costCtrl->method='POST';
        $costCtrl->param=['origin'=>$origin,'destination'=>$destination,'courier'=>$courier,'weight'=>$weight];
        $costCtrl->basicUrl=$this->basicUrl;
        return $costCtrl->Post();
    }
}

?>