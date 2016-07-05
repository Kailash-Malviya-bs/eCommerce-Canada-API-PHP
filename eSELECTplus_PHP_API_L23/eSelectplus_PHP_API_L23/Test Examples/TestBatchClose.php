<?php
##
## This program takes 3 arguments from the command line:
## 1. Store id
## 2. api token
## 3. ecr number
##
## Example php -q TestBatchClose.php store1 45728773 66004444
##

require "../l23Classes.php";

$storeid=$argv[1];
$apitoken=$argv[2];
$ecr_number=$argv[3];

## step 1) create transaction array ###
$txnArray=array(type=>'batchclose',
         ecr_number=>$ecr_number
           );


$mpgTxn = new mpgTransaction($txnArray);

## step 2) create mpgRequest object ### 
$mpgReq=new mpgRequest($mpgTxn);

## step 3) create mpgHttpsPost object which does an https post ##
$mpgHttpPost=new mpgHttpsPost($storeid,$apitoken,$mpgReq);

## step 4) get an mpgResponse object ##
$mpgResponse=$mpgHttpPost->getMpgResponse();


##step 5) get array of all credit cards
$creditCards = $mpgResponse->getCreditCards($ecr_number);


## step 6) loop through the array of credit cards and get information

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
        . $mpgResponse->getCorrectionAmount($ecr_number,$creditCards[$i]);

 

 }



?>

