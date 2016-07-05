<?php

## Example php -q TestPurchase-CustInfo.php

require "../msrMpgClasses.php";

/************************ Request Variables ***************************/

$store_id='store1';
$api_token='yesguy';

/********************* Transactional Variables ************************/

$type='track2_purchase';
$order_id='ord-'.date("dmy-G:i:s");
$cust_id='My Cust ID';
$amount='17.00';
$pan='';
$expiry_date='';
$pos_code='00';

/************ Swipe card and read Track1 and/or Track2 ***********************/

$stdin = fopen("php://stdin", 'r');

print ("Please swipe your card:\n");
$track1 = fgets ($stdin);

$startDelim = ";";
$firstChar = $track1{0};
$track = '';

if($firstChar==$startDelim)
{
	$track = $track1;
}
else
{
	print ("\nPlease swipe your card again:\n");
	$track2 = fgets ($stdin);
	$track = $track2;
}

$track = trim($track);

/******************* Customer Information Variables ********************/

$first_name = 'Cedric';
$last_name = 'Benson';
$company_name = 'Chicago Bears';
$address = '334 Michigan Ave';
$city = 'Chicago';
$province = 'Illinois';
$postal_code = 'M1M1M1';
$country = 'United States';
$phone_number = '453-989-9876';
$fax = '453-989-9877';
$tax1 = '1.01';
$tax2 = '1.02';
$tax3 = '1.03';
$shipping_cost = '9.95';
$email ='Joe@widgets.com';
$instructions ="Make it fast";

/*********************** Line Item Variables **************************/

$item_name[0] = 'Guy Lafleur Retro Jersey';
$item_quantity[0] = '1';
$item_product_code[0] = 'JRSCDA344';
$item_extended_amount[0] = '129.99';

$item_name[1] = 'Patrick Roy Signed Koho Stick';
$item_quantity[1] = '1';
$item_product_code[1] = 'JPREEA344';
$item_extended_amount[1] = '59.99';

/******************** Customer Information Object *********************/

$mpgCustInfo = new msrMpgCustInfo();

/********************** Set Customer Information **********************/

$billing = array(
				 'first_name' => $first_name,
                 'last_name' => $last_name,
                 'company_name' => $company_name,
                 'address' => $address,
                 'city' => $city,
                 'province' => $province,
                 'postal_code' => $postal_code,
                 'country' => $country,
                 'phone_number' => $phone_number,
                 'fax' => $fax,
                 'tax1' => $tax1,
                 'tax2' => $tax2,
                 'tax3' => $tax3,
                 'shipping_cost' => $shipping_cost
                 );

$mpgCustInfo->setBilling($billing);

$shipping = array(
				 'first_name' => $first_name,
                 'last_name' => $last_name,
                 'company_name' => $company_name,
                 'address' => $address,
                 'city' => $city,
                 'province' => $province,
                 'postal_code' => $postal_code,
                 'country' => $country,
                 'phone_number' => $phone_number,
                 'fax' => $fax,
                 'tax1' => $tax1,
                 'tax2' => $tax2,
                 'tax3' => $tax3,
                 'shipping_cost' => $shipping_cost
                 );

$mpgCustInfo->setShipping($shipping);

$mpgCustInfo->setEmail($email);
$mpgCustInfo->setInstructions($instructions);

/*********************** Set Line Item Information *********************/

$item[0] = array(
			   'name'=>$item_name[0],
               'quantity'=>$item_quantity[0],
               'product_code'=>$item_product_code[0],
               'extended_amount'=>$item_extended_amount[0]
               );

$item[1] = array(
			   'name'=>$item_name[1],
               'quantity'=>$item_quantity[1],
               'product_code'=>$item_product_code[1],
               'extended_amount'=>$item_extended_amount[1]
               );

$mpgCustInfo->setItems($item[0]);
$mpgCustInfo->setItems($item[1]);

/*********************** Transactional Associative Array **********************/

$txnArray=array('type'=>$type,
     		    'order_id'=>$order_id,
     		    'cust_id'=>$cust_id,
    		    'amount'=>$amount,
		    	'track2'=>$track,
   		    	'pan'=>$pan,
   		    	'expdate'=>$expiry_date,
   		    	'pos_code'=>$pos_code
   		       );

/********************** Transaction Object ****************************/

$mpgTxn = new msrMpgTransaction($txnArray);

/******************** Set Customer Information ************************/

$mpgTxn->setCustInfo($mpgCustInfo);

/************************* Request Object *****************************/

$mpgRequest = new msrMpgRequest($mpgTxn);

/************************ HTTPS Post Object ***************************/

$mpgHttpPost = new msrMpgHttpsPost($store_id,$api_token,$mpgRequest);

/****************8********** Response *********************************/

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

