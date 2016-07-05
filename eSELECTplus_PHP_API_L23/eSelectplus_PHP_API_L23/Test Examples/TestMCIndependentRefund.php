<?php

##
## This program takes 6 arguments from the command line:
## 1. Store id
## 2. api token
## 3. order id
## 4. amount
## 5. pan
## 6. expdate
##
## Example php -q TestMCIndependentRefund.php level23 moymoy 200404130332 24.01 4242424242424242 0505
##

require "../l23Classes.php";

$storeid=$argv[1];
$apitoken=$argv[2];
$orderid=$argv[3];
$amount=$argv[4];
$pan=$argv[5];
$expdate=$argv[6];

## step 1) create transaction array ###
$txnArray=array(type=>'mcind_refund',
         order_id=>$orderid,
         amount=>$amount,
         pan=>$pan,
         expdate=>$expdate,
         crypt_type=>'7'
           );


## step 2) create a transaction  object passing the hash created in
## step 1.

$mpgTxn = new mpgTransaction($txnArray);

## step 3) create a mpgRequest object passing the transaction object created
## in step 2 
$mpgRequest = new mpgRequest($mpgTxn);

## step 4) create mpgHttpsPost object which does an https post ##
$mpgHttpPost  =new mpgHttpsPost($storeid,$apitoken,$mpgRequest);

## step 5) get an mpgResponse object ##
$mpgResponse=$mpgHttpPost->getMpgResponse();

## step 6) retrieve data using get methods

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
print("\nCorporateCard = " . $mpgResponse->getCorporateCard());
print("\nMessageId = " . $mpgResponse->getMessageId());

?>

