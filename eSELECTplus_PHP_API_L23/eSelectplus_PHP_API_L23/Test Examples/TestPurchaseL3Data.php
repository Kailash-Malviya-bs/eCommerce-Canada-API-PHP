<?php
##
## This program takes 3 arguments from the command line:
## 1. Store id
## 2. api token
## 3. order id
##
## Example php -q TestPurchaseL3Data.php store1 45728773 45109
##

require "../mpg/include/mpgClasses.php";

$storeid='store1';
$apitoken='yesguy';
$orderid=microtime();


## step 1) create an mpgCustInfo object

$mpgCustInfo = new mpgCustInfo();


## step 2) call set methods of the mpgCustinfo object with the 
## proper information

$email ='Joe@widgets.com';
$mpgCustInfo->setEmail($email);

$instructions ="Make it fast";
$mpgCustInfo->setInstructions($instructions);

$billing = array( first_name => 'Joe', 
                  last_name => 'Thompson', 
                  company_name => 'Widget Company Inc.',
                  address => '111 Bolts Ave.',
                  city => 'Toronto', 
                  province => 'Ontario',
                  postal_code => 'M8T 1T8', 
                  country => 'Canada',
                  phone_number => '416-555-5555', 
                  fax => '416-555-5555', 
                  tax1 => '123.45', 
                  tax2 => '12.34',   
                  tax3 => '15.45',   
                  shipping_cost => '456.23');

$mpgCustInfo->setBilling($billing);

$shipping = array( first_name => 'Joe',    
                  last_name => 'Thompson', 
                  company_name => 'Widget Company Inc.', 
                  address => '111 Bolts Ave.',
                  city => 'Toronto', 
                  province => 'Ontario', 
                  postal_code => 'M8T 1T8', 
                  country => 'Canada', 
                  phone_number => '416-555-5555', 
                  fax => '416-555-5555', 
                  tax1 => '123.45',  
                  tax2 => '12.34',   
                  tax3 => '15.45',   
                  shipping_cost => '456.23');

$mpgCustInfo->setShipping($shipping);


##set items purchased
$item1 = array (name=>'item 2 name', 
                quantity=>'53', 
                product_code=>'item 1 product code',
                extended_amount=>'1.00');
$mpgCustInfo->setItems($item1);


$item2 = array(name=>'item 2 name', 
                quantity=>'53', 
                product_code=>'item 2 product code',
                extended_amount=>'1.00');
#$mpgCustInfo->setItems($item2);

#etc...


## step 3) create transaction array ###
$txnArray=array(type=>'purchase',
         order_id=>$orderid,
         amount=>'1.01',
         pan=>'4242424242424242',
         expdate=>'0303',
         crypt_type=>'7'
           );


## step 4) create a transaction  object passing the array created in
## step 3.

$mpgTxn = new mpgTransaction($txnArray);

## step 5) use the setCustInfo method  of mpgTransaction object to
## set the customer info (level 3 data) for this transaction
$mpgTxn->setCustInfo($mpgCustInfo);

## step 6) create a mpgRequest object passing the transaction object created
## in step 4 
$mpgRequest = new mpgRequest($mpgTxn);

## step 7) create mpgHttpsPost object which does an https post ##
$mpgHttpPost  =new mpgHttpsPost($storeid,$apitoken,$mpgRequest);


## step 8) get an mpgResponse object ##
$mpgResponse=$mpgHttpPost->getMpgResponse();

## step 9) retrieve data using get methods


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

