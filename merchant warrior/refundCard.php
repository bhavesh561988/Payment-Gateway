<?php
	/**
	 * Very basic procedural connectivity example to the MW Test
	 * Environment using CURL and PHP5.
	 * 
	 * This example will perform a test refund transaction for
	 * an existing transaction.
	 * 
	 * Please refer to http://dox.merchantwarrior.com/ for help.
	 * 
	 * @author Merchant Warrior
	 */

	// Setup your unique authentication data
	define('MW_MERCHANT_UUID', '** YOUR Merchant UUID **');
	define('MW_API_KEY', '** YOUR API Key **');
	define('MW_API_PASS_PHRASE', '** YOUR API Pass Phrase **');
	
	// Setup the POST url
	define('MW_API_ENDPOINT', 'https://base.merchantwarrior.com/post/');
	
	/**
	 * Generates and returns the request hash after being
	 * provided with the postData array.
	 *
	 * @param array $postData
	 */
	function calculateHash(array $postData = array())
	{
		// Check the amount param
		if (!isset($postData['transactionAmount']) || !strlen($postData['transactionAmount']))
		{
			exit("Missing or blank amount field in postData array.");
		}
		
		// Check the currency param
		if (!isset($postData['transactionCurrency']) || !strlen($postData['transactionCurrency']))
		{
			exit("Missing or blank currency field in postData array.");
		}
		
		// Generate & return the hash
		return md5(strtolower(MW_API_PASS_PHRASE. MW_MERCHANT_UUID. $postData['transactionAmount'] . $postData['transactionCurrency']));
	}
	
	// Setup POST data
	$postData['method'] = 'refundCard'; 
	$postData['merchantUUID'] = MW_MERCHANT_UUID; 
	$postData['apiKey'] = MW_API_KEY; 
	$postData['transactionAmount'] = '10.00'; 
	$postData['transactionCurrency'] = 'aud'; 
	$postData['transactionID'] = '1-fa43fae6-137d-11df-a221-000c29753ad4';
	$postData['refundAmount'] = '10.00';
	$postData['hash'] = calculateHash($postData);
	
	// Setup CURL defaults
	$curl = curl_init();	
	curl_setopt($curl, CURLOPT_TIMEOUT, 60);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
	curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	
	// Setup CURL params for this request
	curl_setopt($curl, CURLOPT_URL, MW_API_ENDPOINT);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData, '', '&'));
	
	// Run CURL
	$response = curl_exec($curl);
	$error = curl_error($curl);
	
	// Check for CURL errors
	if (isset($error) && strlen($error))
	{
		throw new Exception("CURL Error: {$error}");
	}
	
	// Make sure the API returned something
	if (!isset($response) || strlen($response) < 1)
	{
		throw new Exception("API response was empty");
	}
	
	// Parse the XML
	$xml = simplexml_load_string($response);
	// Convert the result from a SimpleXMLObject into an array
	$xml = (array)$xml;
	
	// Check for a valid response code
	if (!isset($xml['responseCode']) || strlen($xml['responseCode']) < 1)
	{
		throw new Exception("API Response did not contain a valid responseCode");
	}
	
	// Validate the response - the only successful code is 0
	$status = ((int)$xml['responseCode'] === 0) ? true : false;
	
	// Make the response a little more useable
	$result = array('status' => $status, 'transactionID' => (isset($xml['transactionID']) ? $xml['transactionID'] : null), 'responseData' => $xml);
	
	// If you don't have xdebug, you can echo a <pre> and then exit(print_r($result, true)); below instead of the var_dump.
	exit(var_dump($result));