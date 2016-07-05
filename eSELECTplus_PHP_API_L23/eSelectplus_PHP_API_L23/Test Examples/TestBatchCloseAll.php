<?php
##
## This program takes 2 arguments from the command line:
## 1. Store id
## 2. api token
##
## Example php -q TestBatchCloseAll.php store1 45728773 
##

require "../mpgClasses.php";

$storeid=$argv[1];
$apitoken=$argv[2];

## step 1) create transaction array ###
$txnArray=array(type=>'batchcloseall'
                );


$mpgTxn = new mpgTransaction($txnArray);

## step 2) create mpgRequest object ### 
$mpgReq=new mpgRequest($mpgTxn);

## step 3) create mpgHttpsPost object which does an https post ##
$mpgHttpPost=new mpgHttpsPost($storeid,$apitoken,$mpgReq);

## step 4) get an mpgResponse object ##
$mpgResponse=$mpgHttpPost->getMpgResponse();

##step 5) get arrays of all terminal IDs (ecr's) and  credit cards

$ecrs = $mpgResponse->getTerminalIDs();

## step 6) loop through all ecrs

for($x=0; $x < count($ecrs);$x++)
{
 
 print "\n\nECR Number = $ecrs[$x]";

 $creditCards = $mpgResponse->getCreditCards($ecrs[$x]);

 ##loop through the array of credit cards and get information

  for($i=0; $i < count($creditCards); $i++)
   {
     print "\nCard Type = $creditCards[$i]";

     print "\nPurchase Count = " 
           . $mpgResponse->getPurchaseCount($ecrs[$x],$creditCards[$i]);

     print "\nPurchase Amount = " 
           . $mpgResponse->getPurchaseAmount($ecrs[$x],$creditCards[$i]);

 
     print "\nRefund Count = "
           . $mpgResponse->getRefundCount($ecrs[$x],$creditCards[$i]);


     print "\nRefund Amount = "
           . $mpgResponse->getRefundAmount($ecrs[$x],$creditCards[$i]);  
    


     print "\nCorrection Count = " 
            . $mpgResponse->getCorrectionCount($ecrs[$x],$creditCards[$i]);
     
     print "\nCorrection Amount = "
           . $mpgResponse->getCorrectionAmount($ecrs[$x],$creditCards[$i]);


    }

 }//end outer for  



?>

