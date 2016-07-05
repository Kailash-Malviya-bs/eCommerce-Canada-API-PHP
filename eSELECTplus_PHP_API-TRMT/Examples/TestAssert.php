<?php

require "../riskClasses.php";


/************************ Request Variables ***************************/

$store_id='moneris';
$api_token='hurgle';


/********************* Transactional Variables ************************/

$type='assert';
$orig_order_id='risktest-071111-10:48:11';
$activities_description='charge_back';
$impact_description='medium';
$confidence_description='suspicious';

/***************** Transactional Associative Array ********************/

$txnArray=array	(
			'type'=>$type,
       			'orig_order_id'=>$orig_order_id,
       			'activities_description'=>$activities_description,
       			'impact_description'=>$impact_description,
       			'confidence_description'=>$confidence_description
          	);

/********************** Transaction Object ****************************/

$riskTxn = new riskTransaction($txnArray);

/************************ Request Object ******************************/

$riskRequest = new riskRequest($riskTxn);

/*********************** HTTPS Post Object ****************************/

$riskHttpsPost  =new riskHttpsPost($store_id,$api_token,$riskRequest);

/***************************** Response ******************************/

$riskResponse=$riskHttpsPost->getRiskResponse();

print("\nResponseCode = " . $riskResponse->getResponseCode());
print("\nMessage = " . $riskResponse->getMessage());

$results = $riskResponse->getResults();

foreach($results as $key => $value)
{
	print("\n".$key ." = ". $value);
}

?>

