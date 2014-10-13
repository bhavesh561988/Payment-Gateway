<?php
// Include the paypal library
include_once ('Paypal.php');

// Create an instance of the paypal library
$myPaypal = new Paypal();


// Specify your paypal email
$myPaypal->addField('business', 'chaudh_1245213166_biz@yahoo.co.in');

// Specify the currency
$myPaypal->addField('currency_code', 'USD');
$host=$_SERVER['HTTP_HOST'];
// Specify the url where paypal will send the user on success/failure
$myPaypal->addField('return', 'http://'.$host.'/consultantDB/subscribe/payment/paypal_success.php');
$myPaypal->addField('cancel_return', 'http://'.$host.'/consultantDB/subscribe/payment/paypal_failure.php');

// Specify the url where paypal will send the IPN
$myPaypal->addField('notify_url', 'http://'.$host.'/consultantDB/subscribe/payment/paypal_ipn.php');
$total=$_GET['total'];

// Specify the product information
$myPaypal->addField('item_name', 'Employee Subscribe Fees');
$myPaypal->addField('amount', $total);
$myPaypal->addField('item_number', '001');
$user_id=$_GET['id'];

// Specify any custom value
$myPaypal->addField('custom', $user_id);

// Enable test mode if needed
$myPaypal->enableTestMode();

// Let's start the train!
$myPaypal->submitPayment();