<?php
	# Setup your unique authentication data
	define('MW_MERCHANT_UUID', '51f9b7966612d');
	define('MW_API_KEY', 'txwmyifu');
	define('MW_API_PASS_PHRASE', 'bnl7xrg7');
	
	# Setup the POST url
	$url = 'https://base.merchantwarrior.com/token/addCard';

	# Setup POST data for add card method
	/*$postData['merchantUUID'] = MW_MERCHANT_UUID; 
	$postData['apiKey'] = MW_API_KEY; 
	$postData['cardName'] = 'Testing at IndiaNIC - JT'; 
	$postData['cardNumber'] = '36430000000007'; 
	$postData['cardExpiryMonth'] = '06'; 
	$postData['cardExpiryYear'] = '22'; 
	$postData['cardGlobal'] = '1'; 
	$postData['cardEmail'] = 'jaladhi.trivedi@indianic.com'; 
	$postData['cardContact'] = '9998843960'; 
	
	$res = GetRespnces($postData,$url);
	
	if($res['status'] == 1)	{
			
			//add card sucessfully - Operation successful
		    $responseMessage 	= 	$res['responseData']['responseMessage'];
            echo $cardID 			= 	$res['responseData']['cardID'];
            $cardKey			= 	$res['responseData']['cardKey'];
            $ivrCardID 			=   $res['responseData']['ivrCardID'];

            $res_Pcard = ProcessCard($cardID);

            if($res_Pcard['status'] == 1){

            	echo "Satus:- " . $res_Pcard['status']."<br>";
            	echo "transactionID:- " . $res_Pcard['transactionID']."<br>";
            	echo "responseMessage:- " . $res_Pcard['responseData']['responseMessage']."<br>";
            	echo "transactionID:- " . $res_Pcard['responseData']['transactionID']."<br>";
            	echo "authCode:- " . $res_Pcard['responseData']['authCode']."<br>";
            	echo "receiptNo:- " . $res_Pcard['responseData']['receiptNo']."<br>";
            	echo "customHash:- " . $res_Pcard['responseData']['customHash']."<br>";
            
            }

    }
	else{

		echo $res['responseData']['responseMessage'];
		exit;
	}*/

	$res_Pcard = ProcessCard('CQFM11642301');

	if($res_Pcard['status'] == 1){

		echo "Satus:- " . $res_Pcard['status']."<br>";
		echo "transactionID:- " . $res_Pcard['transactionID']."<br>";
		echo "responseMessage:- " . $res_Pcard['responseData']['responseMessage']."<br>";
		echo "transactionID:- " . $res_Pcard['responseData']['transactionID']."<br>";
		echo "authCode:- " . $res_Pcard['responseData']['authCode']."<br>";
		echo "receiptNo:- " . $res_Pcard['responseData']['receiptNo']."<br>";
		echo "customHash:- " . $res_Pcard['responseData']['customHash']."<br>";

	}

	
	# Process card method 
	function ProcessCard($cardID){

		// Setup the POST url
		
		$url = 'https://base.merchantwarrior.com/token/processCard';

		// Setup POST data
		$postData['merchantUUID'] = MW_MERCHANT_UUID; 
		$postData['apiKey'] = MW_API_KEY; 
		$postData['transactionAmount'] = '15.00'; 
		$postData['transactionCurrency'] = 'AUD'; 
		$postData['transactionProduct'] = 'Test Product- IndiaNIC'; 
		$postData['customerName'] = 'Jaladhi Trivedi'; 
		$postData['customerCountry'] = 'AU'; 
		$postData['customerState'] = 'Queensland';
		$postData['customerCity'] = 'Brisbane'; 
		$postData['customerAddress'] = '123 Fake St'; 
		$postData['customerPostCode'] = '4000'; 
		$postData['customerPhone'] = '07 3123 4567'; 
		$postData['customerEmail'] = 'bhavesh.Khanpara@indainic.com'; 
		$postData['customerIP'] = '127.0.0.1'; 
		$postData['cardID'] = $cardID;
		$postData['hash'] = calculateHash($postData);

		// Post param to server and get responces from mw APi server
		$res = GetRespnces($postData,$url);

		return $res;
	}

	#get responces from mwh server
	function GetRespnces($postData,$url){
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
		curl_setopt($curl, CURLOPT_URL, $url);
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
		return $result;
	}

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


	