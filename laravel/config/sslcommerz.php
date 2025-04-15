<?php

$apiDomain = env('SSLCZ_TESTMODE') ? "https://sandbox.sslcommerz.com" : "https://securepay.sslcommerz.com";
define('SSLCOMMERZ_VALIDATION_API', "/validator/api/merchantTransIDvalidationAPI.php");

return [
	'apiCredentials' => [
		'store_id' => env("SSLCZ_STORE_ID"),
		'store_password' => env("SSLCZ_STORE_PASSWORD"),
	],
	'apiUrl' => [
		'make_payment' => "/gwprocess/v4/api.php",
		'transaction_status' => SSLCOMMERZ_VALIDATION_API,
		'order_validate' => "/validator/api/validationserverAPI.php",
		'refund_payment' => SSLCOMMERZ_VALIDATION_API,
		'refund_status' => SSLCOMMERZ_VALIDATION_API,
	],
	'apiDomain' => $apiDomain,
	'connect_from_localhost' => env("IS_LOCALHOST", false), // For Sandbox, use "true", For Live, use "false"
	'success_url' => '/payments/success',
	'failed_url' => '/payments/fail',
	'cancel_url' => '/payments/cancel',
	'ipn_url' => '/payments/ipn',
];
