<?php

##
## This program takes 5 arguments from the command line:
## 1. Store id
## 2. api token
## 3. order id
## 4. trans number
## 5. MC level2/3 transaction data file name
##
## Example php -q TestMCLevel23.php moneris hurgle 3333 eeeee mclevel23.txt
##

require "../l23Classes.php";

$storeid=$argv[1];
$apitoken=$argv[2];
$orderid=$argv[3];
$txnnumber=$argv[4];
$mclevel23filename=$argv[5];
$mclevel23String=implode('',file($mclevel23filename));

## step 1) create transaction array ###
$txnArray=array(type=>'mclevel23',
         txn_number=>$txnnumber,
         order_id=>$orderid,
           );

## step 2) create a transaction  object passing the array created in
## step 1.

$mpgTxn = new mpgTransaction($txnArray);
print ($mclevel23String);
## step 2.1) set MC Level2/3 transaction string
$mpgTxn->setMcLevel23String($mclevel23String);

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

