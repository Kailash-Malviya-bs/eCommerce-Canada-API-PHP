<?php

##
## Example php -q TestResIscorporatecard.php moneris hurgle
##

require "../mpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='moneris';
$api_token='EB6I4LlAHrSy2Y50oSXH';

/************************* Transactional Variables ****************************/

$type='res_iscorporatecard';
$data_key='5454548KcyEC5454';

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
				'data_key'=>$data_key
   			    );


/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);

/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nDataKey = " . $mpgResponse->getDataKey());
print("\nCorporateCard = " . $mpgResponse->getCorporateCard());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nCardType = " . $mpgResponse->getCardType());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nResSuccess = " . $mpgResponse->getResSuccess());
print("\nPaymentType = " . $mpgResponse->getPaymentType());
?>

