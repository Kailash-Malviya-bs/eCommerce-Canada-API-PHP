<?php

require "../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='moneris';
$api_token='hurgle';

/************************ Transaction Variables ******************************/

$data_key='QrwfsaSMRmtMHccZvI8eB7mXp';
$orderid='res-preauth-'.date("dmy-G:i:s");
$amount='1.00';
$cavv='AAABBJg0VhI0VniQEjRWAAAAAAA';
$custid='cust';	//if sent will be submitted, otherwise cust_id from profile will be used

//$expdate='1512';

/************************ Transaction Array **********************************/

$txnArray =array(type=>'res_cavv_purchase_cc',
				 data_key=>$data_key,
				 order_id=>$orderid,
				 cust_id=>$custid,
				 amount=>$amount,
				 cavv=>$cavv
				 );

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************ Response Object **********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nDataKey = " . $mpgResponse->getDataKey());
print("\nReceiptId = " . $mpgResponse->getReceiptId());
print("\nReferenceNum = " . $mpgResponse->getReferenceNum());
print("\nResponseCode = " . $mpgResponse->getResponseCode());
print("\nISO = " . $mpgResponse->getISO());
print("\nAuthCode = " . $mpgResponse->getAuthCode());
print("\nMessage = " . $mpgResponse->getMessage());
print("\nTransDate = " . $mpgResponse->getTransDate());
print("\nTransTime = " . $mpgResponse->getTransTime());
print("\nTransType = " . $mpgResponse->getTransType());
print("\nComplete = " . $mpgResponse->getComplete());
print("\nTransAmount = " . $mpgResponse->getTransAmount());
print("\nCardType = " . $mpgResponse->getCardType());
print("\nTxnNumber = " . $mpgResponse->getTxnNumber());
print("\nTimedOut = " . $mpgResponse->getTimedOut());
print("\nAVSResponse = " . $mpgResponse->getAvsResultCode());
print("\nResSuccess = " . $mpgResponse->getResSuccess());
print("\nPaymentType = " . $mpgResponse->getPaymentType());
print("\nCavvResultCode = " . $mpgResponse->getCavvResultCode());

//----------------- ResolveData ------------------------------

print("\n\nCust ID = " . $mpgResponse->getResDataCustId());
print("\nPhone = " . $mpgResponse->getResDataPhone());
print("\nEmail = " . $mpgResponse->getResDataEmail());
print("\nNote = " . $mpgResponse->getResDataNote());
print("\nMasked Pan = " . $mpgResponse->getResDataMaskedPan());
print("\nExp Date = " . $mpgResponse->getResDataExpDate());
print("\nCrypt Type = " . $mpgResponse->getResDataCryptType());
print("\nAvs Street Number = " . $mpgResponse->getResDataAvsStreetNumber());
print("\nAvs Street Name = " . $mpgResponse->getResDataAvsStreetName());
print("\nAvs Zipcode = " . $mpgResponse->getResDataAvsZipcode());

?>