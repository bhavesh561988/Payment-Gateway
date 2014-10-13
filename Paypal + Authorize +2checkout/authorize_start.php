<?php
//include('emp_insertdata.php');
// Include the paypal library
include_once ('Authorize.php');
$total=$_GET['total'];
$user_id=$_GET['id'];
$server=$_SERVER['HTTP_HOST'];


// Create an instance of the authorize.net library
$myAuthorize = new Authorize();

// Specify your authorize.net login and secret
$myAuthorize->setUserInfo('2823cn5AJ2', '46MEa5A8r7St7J4y');

// Specify the url where authorize.net will send the user on success/failure
//$myAuthorize->addField('x_Receipt_Link_URL', 'http://'.$server.'/consultantDB/subscribe/payment/authorize_success.php');

// Specify the url where authorize.net will send the IPN
//$myAuthorize->addField('x_Relay_URL', 'http://localhost/payment/authorize_ipn.php');

// Specify the product information
$myAuthorize->addField('x_Description', 'Employee Subscription Fees');

//zen_draw_hidden_field('x_Amount', number_format($_GET['radio'], 2)) .
  //      zen_draw_hidden_field('x_currency_code', 'GBP') .
$host=$_SERVER['HTTP_HOST'];
$success_url='http://'.$server.'/consultantDB/subscribe/payment/authorize_success.php?id='.$user_id.'';
$myAuthorize->addField('x_Amount', number_format($total, 2));
//$myAuthorize->addField('x_currency_code', 'USD') . 
$myAuthorize->addField('x_Invoice_num', rand(1, 100));
$myAuthorize->addField('x_Cust_ID', $user_id);
$myAuthorize->addField('x_Receipt_Link_Text','Click here to return on site !');
$myAuthorize->addField('x_Receipt_Link_Method','POST');
$myAuthorize->addField('x_Receipt_Link_URL',$success_url );
// Enable test mode if needed
$myAuthorize->enableTestMode();

// Let's start the train!
$myAuthorize->submitPayment();
?>