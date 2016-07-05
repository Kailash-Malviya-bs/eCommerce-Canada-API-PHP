<?php

require "../mpgClasses.php";

/************************ Request Variables **********************************/

$store_id='moneris';
$api_token='hurgle';

/************************ Transaction Variables ******************************/

$data_key='424242IM7Jj24242';
$amount='1.00';
$xid = "12345678910111213162";
$MD = "amount=1.00&datakey=ot-VYgr6wJN3I0XOizaGzTFe6aYv&expdate=1702&xid=9999###########93964";
$merchantUrl = "www.mystoreurl.com";
$accept = "true";
$userAgent = "Mozilla";

/************************ Transaction Array **********************************/

$txnArray =array(type=>'res_mpitxn',
				 data_key=>$data_key,
				 amount=>$amount,
				 xid=>$xid,
				 MD=>$MD,
				 merchantUrl=>$merchantUrl,
				 accept=>$accept,
				 userAgent=>$userAgent
				 );

/************************ Transaction Object *******************************/

$mpgTxn = new mpgTransaction($txnArray);

/************************ Request Object **********************************/

$mpgRequest = new mpgRequest($mpgTxn);

/************************ mpgHttpsPost Object ******************************/

$mpgHttpPost = new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/************************ Response Object **********************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();

print("\nMpiSuccess = " . $mpgResponse->getMpiSuccess());

if($mpgResponse->getMpiSuccess() == "true")
{
	print($mpgResponse->getMpiInLineForm());
}
else
{
	print("\nMpiMessage = " . $mpgResponse->getMpiMessage());
}

?>