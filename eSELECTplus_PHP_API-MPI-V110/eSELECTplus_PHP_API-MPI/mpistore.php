<?php

$storeid ="moneris";
$apitoken="hurgle";
$merchUrl="https://esqa.moneris.com/mpistore/mpistore.php";

include("mpiClasses.php");

if( isset($purchase_amount)) 
{
    $xid =sprintf("%'920d", rand());
	 
	$HTTP_ACCEPT = getenv("HTTP_ACCEPT"); 
	$HTTP_USER_AGENT = getenv("HTTP_USER_AGENT");
    
    //these are form variable gotten after cardholder hits buy button on merchant site
    //(purchase_amount,pan,expiry)

	$txnArray=array(type=>'txn',
         xid=>$xid,
         amount=>$purchase_amount,
         pan=>$pan,
         expdate=>$expiry,

         MD=>   "xid=" . $xid           //MD is merchant data that can be passed along
               ."&amp;pan=" . $pan      
               ."&amp;expiry=".$expiry
               ."&amp;amount=" .$purchase_amount,
 
         merchantUrl=>$merchUrl,
	     accept =>$HTTP_ACCEPT,
	     userAgent =>$HTTP_USER_AGENT
           );

	$mpiTxn = new MpiTransaction($txnArray);

	$mpiRequest = new MpiRequest($mpiTxn);
    
	$mpiHttpPost  = new MpiHttpsPost($storeid,$apitoken,$mpiRequest);

	$mpiResponse = $mpiHttpPost->getMpiResponse();

    
     	 if($mpiResponse->getMpiMessage() == 'Y')
        {
                $vbvInLineForm = $mpiResponse->getMpiInLineForm();
                print "$vbvInLineForm\n";
        }
        else {
                if ($mpiResponse->getMpiMessage() == 'U')       {
		// merchant assumes liability for charge back (usu. corporate cards)
                        $crypt_type='7';
                }
                else {
		// merchant is not liable for chargeback (attempt was made)
                        $crypt_type='6';
                }
                include ("./mpgClasses.php");
                // Send regular transaction with appropriate ECI
                $txnArray=array(type=>'purchase',
                        order_id=>$xid,
                        cust_id=>$cust_id,
                        amount=>$purchase_amount,
                        pan=>$pan,
                        expdate=>$expiry,
                        crypt_type=>$crypt_type
                        );

                $mpgTxn = new mpgTransaction($txnArray);
                $mpgRequest = new mpgRequest($mpgTxn);
                $mpgHttpPost  =new mpgHttpsPost($storeid,$apitoken,$mpgRequest);

                $mpgResponse=$mpgHttpPost->getMpgResponse();
                print "<br>Response = ".$mpgResponse->getMessage();
                print "<br>VBV Resp = ".$mpiResponse->getMpiMessage();
                print "<br>Crypt Type = ".$crypt_type;
        }
	
}
//$PaRes    This variable is gotten from ACS 

else if(isset($PaRes))
{
    $txnArray=array( type=>'acs',
                     PaRes=>$PaRes,
                     MD=>$MD
                    );

    $mpiTxn = new MpiTransaction($txnArray);

    $mpiRequest = new MpiRequest($mpiTxn);

    $mpiHttpPost  = new MpiHttpsPost($storeid,$apitoken,$mpiRequest);

    $mpiResponse=$mpiHttpPost->getMpiResponse();

    parse_str($MD); //this function will parse MD field as if it were a query string
                    //and bring the resultant variables into this scope 

    if( strcmp($mpiResponse->getMpiSuccess(),"true") == 0 )
    {
        require("mpgClasses.php");
           
        $orderid =sprintf("%'920d", rand());
        
        $cavv = $mpiResponse->getMpiCavv();

        $txnArray=array(
            type=>'cavv_purchase',
            order_id=> $orderid,
            amount=>$amount,
            pan=>$pan,
            expdate=>$expiry,
            cavv=>$cavv,
           );

        $mpgTxn = new mpgTransaction($txnArray);

        $mpgRequest = new mpgRequest($mpgTxn);
       
        $mpgHttpPost  = new mpgHttpsPost($storeid,$apitoken,$mpgRequest);

        $mpgResponse =$mpgHttpPost->getMpgResponse();
        
        print "<br>The message is " .$mpgResponse->getMessage();
    }
    else
    {

        //At this point the merchant should deny this transaction

        print "<br>Success = ".$mpiResponse->getMpiSuccess();
        print "<br>Message = ".$mpiResponse->getMpiMessage();
    }
}
else
{
?> 
    <html>
    <form method=post action="https://esqa.moneris.com/mpistore/mpistore.php">
    <table> 
      <tr>
        <td>Credit Card Number:</td>
        <td colspan><input type=text name=pan size=16 value="4242424242424242"></td>
      </tr>
      <tr>
        <td>Expiry Date:</td>
        <td colspan><input type=text  name=expiry size=4 value="0404"></td>
      </tr>
      <tr>
        <td>Amount:</td>
        <td colspan><input type=text  name=purchase_amount size=4 value="1.01"></td>
      </tr>
      <tr>
      <td colspan=2 align=center><input type=submit  name=submit value='Buy'></td>
      </tr>
     </table>
    </form>
    </html>    
   
<?php
}
?>
