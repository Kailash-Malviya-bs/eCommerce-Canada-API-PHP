<?php

##
## Example php -q TestResAddCC.php store3 yesguy
##

require "../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='moneris';
$api_token='EB6I4LlAHrSy2Y50oSXH';

/************************* Transactional Variables ****************************/

$type='res_add_cc';
$cust_id='customer1';
$phone = '5555551234';
$email = 'bob@smith.com';
$note = 'this is my note';
$pan='603207857785992';
$expiry_date='1412';
$crypt_type='1';
$avs_street_number = '123';
$avs_street_name = 'lakeshore blvd';
$avs_zipcode = '90210';

$data_key='abc4514011602310979';

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
			'cust_id'=>$cust_id,
			'phone'=>$phone,
			'email'=>$email,
			'note'=>$note,
    		'pan'=>$pan,
   			'expdate'=>$expiry_date,
			'crypt_type'=>$crypt_type
		);

/********************** AVS Associative Array *********************************/

$avsTemplate = array(
			'avs_street_number' => $avs_street_number,
			'avs_street_name' => $avs_street_name,
			'avs_zipcode' => $avs_zipcode
		);

/************************** AVS Object ***************************************/

$mpgAvsInfo = new mpgAvsInfo ($avsTemplate);

/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);
$mpgTxn->setAvsInfo($mpgAvsInfo);

//$mpgTxn->setDataKeyFormat("1"); //1=F6L4 w/ Length preserve, 2=F6L4 w/o Length preserve
$mpgTxn->setDataKey($data_key);

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

print("\n\nCust ID = " . $mpgResponse->getResDataCustId());
print("\nPhone = " . $mpgResponse->getResDataPhone());
print("\nEmail = " . $mpgResponse->getResDataEmail());
print("\nNote = " . $mpgResponse->getResDataNote());
print("\nMasked Pan = " . $mpgResponse->getResDataMaskedPan());
print("\nExp Date = " . $mpgResponse->getResDataExpDate());
print("\nCrypt Type = " . $mpgResponse->getResDataCryptType());
print("\nAvs Street Number = " . $mpgResponse->getResDataAvsStreetNumber());
print("\nAvs Street Name = " . $mpgResponse->getResDataAvsStreetName());
print("\nAvs Zipcode = " . $mpgResponse->getResDataAvsZipcode());

?>

