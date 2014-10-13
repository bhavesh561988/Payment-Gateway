<?php
// Include the paypal library
include_once ('Authorize.php');

// Create an instance of the authorize.net library
$myAuthorize = new Authorize();

// Specify your authorize.net login and secret
$myAuthorize->setUserInfo('2823cn5AJ2', '46MEa5A8r7St7J4y');

// Specify the url where authorize.net will send the user on success/failure
$myAuthorize->addField('x_Receipt_Link_URL', 'http://localhost/payment/authorize_success.php');

// Specify the url where authorize.net will send the IPN
$myAuthorize->addField('x_Relay_URL', 'http://localhost/payment/authorize_ipn.php');

// Specify the product information
$myAuthorize->addField('x_Description', 'Registration Fees');
$myAuthorize->addField('x_Amount', $_GET['radio']);
//$myAuthorize->addField('x_currency_code', 'GBP') . 
$myAuthorize->addField('x_Invoice_num', rand(1, 100));
$myAuthorize->addField('x_Cust_ID', '');

// Enable test mode if needed
$myAuthorize->enableTestMode();

// Let's start the train!
$myAuthorize->submitPayment();
?>