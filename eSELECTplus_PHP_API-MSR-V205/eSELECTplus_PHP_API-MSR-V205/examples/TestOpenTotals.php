<?php
##
## This program takes 3 arguments from the command line:
## 1. Store id
## 2. api token
## 3. ecr number
##
## Example php -q TestOpenTotals.php store1 yesguy 66002173
##

require "../msrMpgClasses.php";

/**************************** Request Variables *******************************/

$store_id = $argv[1];
$api_token = $argv[2];

/************************* Transactional Variables ****************************/

$ecr_number = $argv[3];

/*********************** Transactional Associative Array **********************/

$txnArray = array('type'=>'opentotals',
         		  'ecr_number'=>$ecr_number
           		 );

/**************************** Transaction Object *****************************/

$mpgTxn = new msrMpgTransaction($txnArray);

/****************************** Request Object *******************************/

$mpgReq = new msrMpgRequest($mpgTxn);

/***************************** HTTPS Post Object *****************************/

$mpgHttpPost = new msrMpgHttpsPost($store_id,$api_token,$mpgReq);

/******************************* Response ************************************/

$mpgResponse = $mpgHttpPost->getMpgResponse();

$creditCards = $mpgResponse->getCreditCards($ecr_number);

for($i=0; $i < count($creditCards); $i++)
 {
  print "\nCard Type = $creditCards[$i]";

  print "\nPurchase Count = "
        . $mpgResponse->getPurchaseCount($ecr_number,$creditCards[$i]);

  print "\nPurchase Amount = "
        . $mpgResponse->getPurchaseAmount($ecr_number,$creditCards[$i]);

  print "\nRefund Count = "
        . $mpgResponse->getRefundCount($ecr_number,$creditCards[$i]);

  print "\nRefund Amount = "
        . $mpgResponse->getRefundAmount($ecr_number,$creditCards[$i]);

  print "\nCorrection Count = "
        . $mpgResponse->getCorrectionCount($ecr_number,$creditCards[$i]);

  print "\nCorrection Amount = "
        . $mpgResponse->getCorrectionAmount($ecr_number,$creditCards[$i]) . "\n";

 }

?>