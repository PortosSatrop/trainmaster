<?php
/*
	This script provides a RESTful API interface for a web application

	Input:

		$_GET['format'] = [ json | html | xml ]
		$_GET['info'] = JSON
		JSON method = [ toggle | allstop | allstart | startcircuit | stopcircuit | getdevicestatus | allstraight ]
		JSON device = [P-REL01 for example] or [JSON category = power for example] or [ JSON circuit = A for exmaple ]

	Output: A formatted HTTP response

	Author: Manuel based on  Mark Roland example


*/

// --- Step 1: Initialize variables and functions

/**
 * Deliver HTTP Response
 * @param string $format The desired HTTP response content type: [json, html, xml]
 * @param string $api_response The desired HTTP response data
 * @return void
 **/
function deliver_response($format, $api_response){

	// Define HTTP responses
	$http_response_code = array(
		200 => 'OK',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not Found'
	);

	// Set HTTP Response
	header('HTTP/1.1 '.$api_response['status'].' '.$http_response_code[ $api_response['status'] ]);

	// Process different content types
	if( strcasecmp($format,'json') == 0 ){

		// Set HTTP Response Content Type
		header('Content-Type: application/json; charset=utf-8');

		// Format data into a JSON response
		$json_response = json_encode($api_response);

		// Deliver formatted data
		echo $json_response;

	}elseif( strcasecmp($format,'xml') == 0 ){

		// Set HTTP Response Content Type
		header('Content-Type: application/xml; charset=utf-8');

		// Format data into an XML response (This is only good at handling string data, not arrays)
		$xml_response = '<?xml version="1.0" encoding="UTF-8"?>'."\n".
			'<response>'."\n".
			"\t".'<code>'.$api_response['code'].'</code>'."\n".
			"\t".'<data>'.$api_response['data'].'</data>'."\n".
			'</response>';

		// Deliver formatted data
		echo $xml_response;

	}else{

		// Set HTTP Response Content Type (This is only good at handling string data, not arrays)
		header('Content-Type: text/html; charset=utf-8');

		// Deliver formatted data
		echo $api_response['data'];

	}

	// End script process
	exit;

}

// Define whether an HTTPS connection is required
$HTTPS_required = FALSE;

// Define whether user authentication is required
$authentication_required = FALSE;

// Define API response codes and their related HTTP response
$api_response_code = array(
	0 => array('HTTP Response' => 400, 'Message' => 'Unknown Error'),
	1 => array('HTTP Response' => 200, 'Message' => 'Success'),
	2 => array('HTTP Response' => 403, 'Message' => 'HTTPS Required'),
	3 => array('HTTP Response' => 401, 'Message' => 'Authentication Required'),
	4 => array('HTTP Response' => 401, 'Message' => 'Authentication Failed'),
	5 => array('HTTP Response' => 404, 'Message' => 'Invalid Request'),
	6 => array('HTTP Response' => 400, 'Message' => 'Invalid Response Format')
);

// Set default HTTP response of 'ok'
$response['code'] = 0;
$response['status'] = 404;
$response['data'] = NULL;

// --- Step 2: Authorization

// Optionally require connections to be made via HTTPS
if( $HTTPS_required && $_SERVER['HTTPS'] != 'on' ){
	$response['code'] = 2;
	$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
	$response['data'] = $api_response_code[ $response['code'] ]['Message'];

	// Return Response to browser. This will exit the script.
	deliver_response($_GET['format'], $response);
}

// Optionally require user authentication
if( $authentication_required ){

	if( empty($_POST['username']) || empty($_POST['password']) ){
		$response['code'] = 3;
		$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
		$response['data'] = $api_response_code[ $response['code'] ]['Message'];

		// Return Response to browser
		deliver_response($_GET['format'], $response);

	}

	// Return an error response if user fails authentication. This is a very simplistic example
	// that should be modified for security in a production environment
	elseif( $_POST['username'] != 'foo' && $_POST['password'] != 'bar' ){
		$response['code'] = 4;
		$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
		$response['data'] = $api_response_code[ $response['code'] ]['Message'];

		// Return Response to browser
		deliver_response($_GET['format'], $response);

	}

}

// --- Step 3: Process Request
// Mandatory to have a method field in the JSON
$info = json_decode(urldecode($_GET['info']));
if(!property_exists($info, 'method') && !is_array($info->method)){
	$response['data']= "Missing method";
	$response['code'] = 5;
}else{
	$method = $info->method;

	// Method Say Hello to the API
	if( strcasecmp($method,'hello') == 0){
		$response['code'] = 1;
		$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
		$response['data'] = 'Hello World';
	}

	// Method Toggle device
	if( strcasecmp($method,'toggle') == 0){
		$device = $info->device;
		$response['code'] = 1;
		$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
		$result = shell_exec("sudo python relay.py ".$method." ".$device);
		$response['data'] = $result;
	}
	
	// Method Emergency stop
	if( strcasecmp($method,'allstop') == 0){
		$response['code'] = 1;
		$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
		$result = shell_exec("sudo python relay.py ".$method." dummy");
		$response['data'] = $result;
	}
	// Method to enable all
	if( strcasecmp($method,'allstart') == 0){
		$response['code'] = 1;
		$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
		$result = shell_exec("sudo python relay.py ".$method." dummy");
		$response['data'] = $result;
	}
	
	// Method to enable a specific Circuit
	if( strcasecmp($method,'startcircuit') == 0){
		$circuit = $info->circuit;
		$response['code'] = 1;
		$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
		$result = shell_exec("sudo python relay.py ".$method." ".$circuit);
		$response['data'] = $result;
	}
	
	// Method to stop a specific Circuit
	if( strcasecmp($method,'stopcircuit') == 0){
		$circuit = $info->circuit;
		$response['code'] = 1;
		$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
		$result = shell_exec("sudo python relay.py ".$method." ".$circuit);
		$response['data'] = $result;
	}
	
	// Method to get the status of a relay of a category : power or turnout
	if( strcasecmp($method,'getdevicestatus') == 0){
		$category = $info->category;
		$response['code'] = 1;
		$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
		$result = shell_exec("sudo python relay.py ".$method." ".$category);
		$response['data'] = $result;
	}

	// Method to set al turnouts straight
	if( strcasecmp($method,'allstraight') == 0){
		$response['code'] = 1;
		$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
		$result = shell_exec("sudo python relay.py ".$method." dummy");
		$response['data'] = $result;
	}

	
}
// --- Step 4: Deliver Response

// Return Response to browser
deliver_response($_GET['format'], $response);

?>
