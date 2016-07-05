<?php

require "../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='moneris';
$api_token='EB6I4LlAHrSy2Y50oSXH';

/************************* Transactional Variables ****************************/

$type='res_temp_add';
$pan='4242424242424242';
$expiry_date='0000';
$crypt_type='7';
$duration="900";

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
				'pan'=>$pan,
				'expdate'=>$expiry_date,
				'crypt_type'=>$crypt_type,
				'duration'=>$duration
   			    );


/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);

$mpgTxn->setDataKeyFormat("1"); //1=F6L4 w/ Length preserve, 2=F6L4 w/o Length preserve
/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nDataKey = " . $mpgResponse->getDataKey());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nResSuccess = " . $mpgResponse->getResSuccess());
print("\nPaymentType = " . $mpgResponse->getPaymentType());

//----------------- ResolveData ------------------------------

print("\n\nMasked Pan = " . $mpgResponse->getResDataMaskedPan());
print("\nExp Date = " . $mpgResponse->getResDataExpDate());

?>

