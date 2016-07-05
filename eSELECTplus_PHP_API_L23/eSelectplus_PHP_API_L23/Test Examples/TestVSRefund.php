<?php

##
## This program takes 4/7 arguments from the command line:
## 1. Store id
## 2. api token
## 3. order id
## 4. trans number
## 5. order level gst
## 6. merchant gst number
## 7. customer relationship identification
##
## Example php -q TestMCRefund.php store1 45728773 45109 76452
##

require "../l23Classes.php";

$storeid=$argv[1];
$apitoken=$argv[2];
$orderid=$argv[3];
$txnnumber=$argv[4];
$paras=count($argv);
$ol_gst="";
$ol_gstno="";
$cri="";
if ( $paras > 5 )
{
  $ol_gst=$argv[5];
}
if ( $paras > 6 )
{
  $ol_gstno=$argv[6];
}
if ( $paras > 7 )
{
  $cri=$argv[7];
}

## step 1) create transaction array ###
$txnArray=array(type=>'vsrefund',
         txn_number=>$txnnumber,
         order_id=>$orderid,
         amount=>'1.01',
         crypt_type=>'7',
         order_level_gst=>$ol_gst,
         merchant_gst_no=>$ol_gstno,
         cri=>$cri
           );

## step 2) create a transaction  object passing the array created in
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

