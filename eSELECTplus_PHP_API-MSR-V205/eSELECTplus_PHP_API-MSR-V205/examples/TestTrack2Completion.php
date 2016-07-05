<?php

##
## This program takes 4 arguments from the command line:
## 1. Store id
## 2. api token
## 3. order id
## 4. trans number
##
## Example php -q TestTrack2Completion.php store1 yesguy unique_order_id 764-20-0
##

require "../msrMpgClasses.php";

/**************************** Request Variables *******************************/

$store_id=$argv[1];
$api_token=$argv[2];

/************************* Transactional Variables ****************************/

$orderid=$argv[3];
$txnnumber=$argv[4];

$compamount='1.00';

/*********************** Transactional Associative Array **********************/

$txnArray=array( 'type'=>'track2_completion',
				 'txn_number'=>$txnnumber,
				 'order_id'=>$orderid,
				 'comp_amount'=>$compamount
           		);


/**************************** Transaction Object *****************************/

$mpgTxn = new msrMpgTransaction($txnArray);

/****************************** Request Object *******************************/

$mpgRequest = new msrMpgRequest($mpgTxn);

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost  =new msrMpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

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