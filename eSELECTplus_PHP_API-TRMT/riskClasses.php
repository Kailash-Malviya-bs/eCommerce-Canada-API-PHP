
<?php

#################### riskGlobals ###########################################


class riskGlobals{

var $Globals=array(
                  MONERIS_PROTOCOL => 'https',

                  MONERIS_HOST => 'esqa.moneris.com',

                  MONERIS_PORT =>'443',

                  MONERIS_FILE => '/gateway2/servlet/MpgRequest',

                  API_VERSION  =>'PHP - 1.0.0 - RISK',

                  CLIENT_TIMEOUT => '60'
                 );

 function riskGlobals()
 {
  // default
 }

 function getGlobals()
 {
  return($this->Globals);
 }

}//end class riskGlobals



###################### riskHttpsPost #########################################

class riskHttpsPost{

 var $api_token;
 var $store_id;
 var $riskRequest;
 var $riskResponse;

 function riskHttpsPost($storeid,$apitoken,$riskRequestOBJ)
 {

  $this->store_id=$storeid;
  $this->api_token= $apitoken;
  $this->riskRequest=$riskRequestOBJ;
  $dataToSend=$this->toXML();

  echo "DATA TO SEND: $dataToSend\n";
  //do post

  $g=new riskGlobals();
  $gArray=$g->getGlobals();

  $url=$gArray[MONERIS_PROTOCOL]."://".
       $gArray[MONERIS_HOST].":".
       $gArray[MONERIS_PORT].
       $gArray[MONERIS_FILE];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  curl_setopt ($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$dataToSend);
  curl_setopt($ch,CURLOPT_TIMEOUT,$gArray[CLIENT_TIMEOUT]);
  curl_setopt($ch,CURLOPT_USERAGENT,$gArray[API_VERSION]);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);

  $response=curl_exec ($ch);

  //echo "RESPONSE: $response\n";

  curl_close ($ch);

  if(!$response)
  {

     $response="<?xml version=\"1.0\"?><response><receipt>".
          "<ReceiptId>Global Error Receipt</ReceiptId>".
          "<ResponseCode>null</ResponseCode>".
          "<AuthCode>null</AuthCode><TransTime>null</TransTime>".
          "<TransDate>null</TransDate><TransType>null</TransType><Complete>false</Complete>".
          "<Message>null</Message><TransAmount>null</TransAmount>".
          "<CardType>null</CardType>".
          "<TransID>null</TransID><TimedOut>null</TimedOut>".
          "</receipt></response>";
  }

    //print "Got a xml response of: \n$response\n";
  	$this->riskResponse=new riskResponse($response);

 }



 function getRiskResponse()
 {
  return $this->riskResponse;

 }

 function toXML( )
 {

  $req=$this->riskRequest ;
  $reqXMLString=$req->toXML();

  $xmlString .="<?xml version=\"1.0\"?>".
               "<request>".
               "<store_id>$this->store_id</store_id>".
               "<api_token>$this->api_token</api_token>".
               "<risk>".
               $reqXMLString.
               "</risk>".
               "</request>";

  return ($xmlString);

 }

}//end class riskHttpsPost



############# riskResponse #####################################################


class riskResponse{

 var $responseData;

 var $p; //parser

 var $currentTag;
 var $isResults;
 var $isRule;
 var $ruleName;
 var $results = array();
 var $rules = array();
 
 function riskResponse($xmlString)
 {

  $this->p = xml_parser_create();
  xml_parser_set_option($this->p,XML_OPTION_CASE_FOLDING,0);
  xml_parser_set_option($this->p,XML_OPTION_TARGET_ENCODING,"UTF-8");
  xml_set_object($this->p,&$this);
  xml_set_element_handler($this->p,"startHandler","endHandler");
  xml_set_character_data_handler($this->p,"characterHandler");
  xml_parse($this->p,$xmlString);
  xml_parser_free($this->p);

 }//end of constructor


 function getRiskResponse()
 {
 	 return($this->responseData);
 }

 //-----------------  Receipt Variables  ---------------------------------------------------------//

 function getReceiptId()
 {
 print("\nreceiptId get value: ".$this->responseData['ReceiptId']);
  	return ($this->responseData['ReceiptId']);
 }

 function getResponseCode()
 {
 	return ($this->responseData['ResponseCode']);
 }

 function getMessage()
 {
 	return ($this->responseData['Message']);
 }

 function getResults()	
 {
 	return ($this->results);
 }
 
 function getRules()	
 {
  	return ($this->rules);
 }

//-----------------  Parser Handlers  ---------------------------------------------------------//

function characterHandler($parser,$data)
{
   	@$this->responseData[$this->currentTag] .=$data;
   	
   	if($this->isResults)
	{
		//print("\n".$this->currentTag."=".$data);
		$this->results[$this->currentTag] = $data;
	                          
        }
        
        if($this->isRule)
        {
        
        	if ($this->currentTag == "RuleName")
	    	{
			$this->ruleName=$data;
    		}
    		$this->rules[$this->ruleName][$this->currentTag] = $data;
    		
        }
}//end characterHandler



function startHandler($parser,$name,$attrs)
{
	$this->currentTag=$name;

	if($this->currentTag == "Result")
   	{
    		$this->isResults=1;
   	}
   	
   	if($this->currentTag == "Rule")
   	{
   		$this->isRule=1;
   	}
} //end startHandler

function endHandler($parser,$name)
{
 	$this->currentTag=$name;

	if($name == "Result")
   	{
    		$this->isResults=0;
   	}
   	
   	if($this->currentTag == "Rule")
	{
		$this->isRule=0;
   	}
   	
	$this->currentTag="/dev/null";
} //end endHandler



}//end class riskResponse


################## riskRequest ###########################################################

class riskRequest{

 var $txnTypes =array(
 		session_query => array('order_id','session_id','service_type','event_type'),
        	attribute_query => array('order_id','policy_id','service_type'),
        	assert => array('orig_order_id','activities_description','impact_description','confidence_description')
     );

var $txnArray;

function riskRequest($txn){

 if(is_array($txn))
 {
    $this->txnArray = $txn;
 }
 else
 {
    $temp[0]=$txn;
    $this->txnArray=$temp;
 }
}

function toXML()
{

 $tmpTxnArray=$this->txnArray;

 $txnArrayLen=count($tmpTxnArray); //total number of transactions
 for($x=0;$x < $txnArrayLen;$x++)
 {
    $txnObj=$tmpTxnArray[$x];
    $txn=$txnObj->getTransaction();

    $txnType=array_shift($txn);
    $tmpTxnTypes=$this->txnTypes;
    $txnTypeArray=$tmpTxnTypes[$txnType];
    $txnTypeArrayLen=count($txnTypeArray); //length of a specific txn type

    $txnXMLString="";
    for($i=0;$i < $txnTypeArrayLen ;$i++)
    {
      $txnXMLString  .="<$txnTypeArray[$i]>"   //begin tag
                       .$txn[$txnTypeArray[$i]] // data
                       . "</$txnTypeArray[$i]>"; //end tag
    }
    
    $txnXMLString = "<$txnType>$txnXMLString";
    
    $sessionQuery  = $txnObj->getSessionAccountInfo();
   
    if($sessionQuery != null)
    {
   	$txnXMLString .= $sessionQuery->toXML();
    }
	

    $attributeQuery  = $txnObj->getAttributeAccountInfo();
      
    if($attributeQuery != null)
    {
      	$txnXMLString .= $attributeQuery->toXML();
    }	
    
    
    $txnXMLString .="</$txnType>";
    
    $xmlString .=$txnXMLString;
    
    return $xmlString;


 }

 return $xmlString;

}//end toXML



}//end class

##################### mpgSessionAccountInfo #######################################################

class mpgSessionAccountInfo
{

	var $params;
	var $sessionAccountInfoTemplate = array('policy','account_login','password_hash','account_number','account_name',
	'account_email','account_telephone','pan','account_address_street1','account_address_street2','account_address_city',
	'account_address_state','account_address_country','account_address_zip','shipping_address_street1','shipping_address_street2','shipping_address_city',
	'shipping_address_state','shipping_address_country','shipping_address_zip','local_attrib_1','local_attrib_2','local_attrib_3','local_attrib_4',
	'local_attrib_5','transaction_amount','transaction_currency');

	function mpgSessionAccountInfo($params)
	{
		$this->params = $params;
	}

	function toXML()
	{
		foreach($this->sessionAccountInfoTemplate as $tag)
		{
			$xmlString .= "<$tag>". $this->params[$tag] ."</$tag>";
		}

		return "<session_account_info>$xmlString</session_account_info>";
	}

}//end class

##################### mpgAttributeAccountInfo #######################################################

class mpgAttributeAccountInfo
{

	var $params;
	var $attributeAccountInfoTemplate = array('device_id','account_login','password_hash','account_number','account_name',
	'account_email','account_telephone','cc_number_hash','ip_address','ip_forwarded','account_address_street1','account_address_street2','account_address_city',
	'account_address_state','account_address_country','account_address_zip','shipping_address_street1','shipping_address_street2','shipping_address_city',
	'shipping_address_state','shipping_address_country','shipping_address_zip');

	function mpgAttributeAccountInfo($params)
	{
		$this->params = $params;
	}

	function toXML()
	{
		foreach($this->attributeAccountInfoTemplate as $tag)
		{
			$xmlString .= "<$tag>". $this->params[$tag] ."</$tag>";
		}

		return "<attribute_account_info>$xmlString</attribute_account_info>";
	}

}//end class


##################### riskTransaction #######################################################

class riskTransaction{

 var $txn;
 var $attributeAccountInfo = null;
 var $sessionAccountInfo = null;

function riskTransaction($txn)
{
	$this->txn=$txn;
}

function getTransaction()
{
	return $this->txn;
}

function getAttributeAccountInfo()
{
	return $this->attributeAccountInfo;
}
function setAttributeAccountInfo($attributeAccountInfo)
{
	$this->attributeAccountInfo = $attributeAccountInfo;
}

function getSessionAccountInfo()
{
	return $this->sessionAccountInfo;
}
function setSessionAccountInfo($sessionAccountInfo)
{
	$this->sessionAccountInfo = $sessionAccountInfo;
}

}//end class


?>


