<?php

##
## Example php -q TestTrack2PreAuth.php
##

require "../msrMpgClasses.php";

/**************************** Request Variables *******************************/

$store_id='store1';
$api_token='yesguy';

/************************* Transactional Variables ****************************/

$type='track2_preauth';
$cust_id='customer1';
$order_id='cfmsr-001-'.date("dmy-G:i:s");
$amount='1.00';
$pan='';
$expiry_date='';
$pos_code='27';

/************ Swipe card and read Track1 and/or Track2 ***********************/

$track=';4924190000004568=09121013902251544535?';

/*********************** Transactional Associative Array **********************/

$txnArray=array(
				'type'=>$type,
     		    'order_id'=>$order_id,
     		    'cust_id'=>$cust_id,
    		    'amount'=>$amount,
		    	'track2'=>$track,
   		    	'pan'=>$pan,
   		    	'expdate'=>$expiry_date,
   		    	'pos_code'=>$pos_code
   		       );

/**************************** Transaction Object *****************************/

$mpgTxn = new msrMpgTransaction($txnArray);

/****************************** Request Object *******************************/

$mpgRequest = new msrMpgRequest($mpgTxn);

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost = new msrMpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse = $mpgHttpPost->getMpgResponse();

print ("\nCardType = " . $mpgResponse->getCardType());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTicket = " . $mpgResponse->getTicket());
print("\nTimedOut = " . $mpgResponse->getTimedOut());

?>
