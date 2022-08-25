<?php 
	require_once('top.php');
	// require_once('db/dbconnector.php');
    require_once('helper/pesapalV30Helper.php');

    $consumer_key = "qkio1BGGYAXTu2JOfm7XSXNruoZsrqEW";
    $consumer_secret = "osGQ364R49cXKeOYSpaOnT++rHs=";

    $api = 'demo';

    $helper = new pesapalV30Helper($api);

    $access = $helper->getAccessToken($consumer_key, $consumer_secret);
    $access_token = $access->token;
    // echo $access_token;

        
    if(isset($_GET['OrderTrackingId']))
        $orderTrackingId = $_GET['OrderTrackingId'];
        
    
    $status = $helper->getTransactionStatus($orderTrackingId, $access_token);

    // var_dump($status)
    
    //At this point, you can update your database.
    //In my case i will let the IPN do this for me since it will run
    //IPN runs when there is a status change  and since this is a new transaction, 
    //the status has changed for UNKNOWN to PENDING/COMPLETED/FAILED
    // <b>Status: </b> <?php echo $status->payment_status_description 
?>
<h3>Callback/ return URl</h3>
<div class="row-fluid">
	<div class="span6">
        <b>PAYMENT RECEIVED</b>
        <blockquote>
         	<b>Order Tracking ID: </b> <?php echo $orderTrackingId; ?><br />
         	<b>Status: </b> <?php echo $status->payment_status_description; ?><br /> 
        </blockquote>
    </div>
	<div class="span6">
    	<ol>
        	<li>This is your callback URL. We return OrderTrackingId only</li>
            <li>Currently we don't have an API to query payment Amount. Always store your data before redirecting to PesaPal's APIs</li>
            <li>Configure your IPN: [domain]/pesapalPHPExample/ipn.php
            	<ul>
                	<li>The IPN link when executed is appended with; notification type, merchant reference and tracking id</li>
                </ul>
            </li>
            <li>You have two options: 
            	<ul>
                    <li>Update db on callback.</li>
                    <li>use IPN to do bd update. </li>
                    Best approach, always use IPN to do all db update or other functions that are executed after a certain Payment status is confirmed.
                </ul>
             </li>
            <li>IPN runs when there is a status change. Even a new payment that completes automatically(eg using pesapal wallet) will still call the IPN since there is a ststus change from "UNKNOWN/NEW" to PENDING/COMPLETED/FAILED</li>
            <li>INVALID Status- Returned if:
            	<ul>
                	<li>Your merchant reference is dublicated on the merchant's account and you are using querybymerchantref API</li>
                    <li>The transaction doesnt exist on PesaPal</li>
                </ul>
            </li>
        </ol>
    </div>
</div>