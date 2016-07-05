<?php

require "../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='store_ppi';
$api_token='ppiguy';

/************************* Transactional Variables ****************************/

$type='res_tokenize_cc';
//$order_id = '201125504';
//$txn_number = '30302-0_9';
$order_id='2013112111627';
$txn_number='190-0_9';
$cust_id='customer1';
$phone = '4165555555';
$email = 'bob@smith.com';
$note = 'this is my note';

$avs_street_number = '123';
$avs_street_name = 'lakeshore blvd';
$avs_zipcode = '90210';

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
				'order_id'=>$order_id,
				'txn_number'=>$txn_number,
				'cust_id'=>$cust_id,
				'phone'=>$phone,
				'email'=>$email,
				'note'=>$note
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

