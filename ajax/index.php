<?php

$info = pathinfo(getcwd());
define('JCORE_PROJECT', $info['dirname'].'/');

require_once(JCORE_PROJECT.'libs/php/jcore-server.inc.php');

define('JC_NO_ERROR', 0);
define('JC_DB_CONNECT_ERROR', 1);
define('JC_INVALID_REQUEST', 2);

/**
* Define
*
* Large description.
*
* @package jCore
* 
*/
define('HTTP_NOT_FOUND', 404);

/**
* AJAX Gateway
*
* This is the long description for a DocBlock. This text may contain
* multiple lines and even some _markdown_.
*
* * Markdown style lists function too
* * Just try this out once
*
* The section after the long description contains the tags; which provide
* structured meta-data concerning the given element.
*
* @since 0.0.1
* @package jCore
* 
*/
function ajaxGateway() {
	/*
	 * Sanitizes res id.
	 *
	 * Removes all characters except letters, digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=
	 */
	$resId = filter_input(INPUT_GET | INPUT_POST, "res", FILTER_SANITIZE_URL);

	$batchPullRequest = array();

	if (isset($_POST['pull'])) {
		$batchPullRequest = json_decode($_POST['pull']);
	}
	//error_log(print_r($batchPullRequest, 1));

	$batchResponse = array();

	try {
		require_once(JCORE_PROJECT."libs/php/predis/autoload.php");
		Predis\Autoloader::register();

		require_once('db-connect.php');
		
	//	$redis = new Predis\Client();

		$redis = new Predis\Client(array(
			"scheme" => DB_SCHEME,
			"host" => DB_HOST,
			"port" => DB_PORT,
			"password" => DB_PASSWORD));

		//	$redis->set('/hello-world', 'Hello, jCore!');

	    // "Successfully connected to Redis";


		// Since we connect to default setting localhost and 6379 port there is no need for extra configuration. 
		// If not then you can specify the scheme, host and port to connect as an array to the constructor.
		// 
		require_once("sha256.inc.php");


		foreach ($batchPullRequest as $uri => $pullRequest) {
			$resource = array();

			// Look up uri in redis.
			
			$resourcePrefix = '/jc/resources';
			$resKey = $resourcePrefix.$uri;

			if ($redis->exists($resKey)) {
				$value = $redis->get($resKey);

				if (isset($pullRequest->value)) {
					$clientRes = json_encode($pullRequest->value);
					$serverRes = $value;

					// Compare resource values.
					if (strcasecmp($clientRes, $serverRes) != 0) {
						$resource['value'] = $value;
					}
					else {
						$resource['_'] = JC_NO_ERROR;
					}
				}
				else if (isset($pullRequest->hash)) {
					$clientRes = $pullRequest->hash;

					// Without caching.
					$hash = hash('sha256', $value);

				/*
					// With caching.
					// Get the server-side hash, generating one if it hasn't been cached.
					// This only works if the only resource changes are going through the jCore gateway.
					// Otherwise, we have to assume that resources might have been changed since 
					// last we checked.
					$hashPrefix = '/jc/hashes';
					$hashKey = $hashPrefix.$uri;
					$hash = '';

					if ($redis->exists($hashKey)) {
						// Server-side hash exists.
						$hash = $redis->get($hashKey);
					}
					else {
						// Server-side hash doesn't exist and needs to be generated.
						$hash = hash('sha256', $value);
						$redis->set($hashKey, $hash);
					}
				 */

					$serverRes = $hash;

					// Compare resource values.
					if (strcasecmp($clientRes, $serverRes) != 0) {
						$resource['value'] = $value;
					}
					else {
						$resource['_'] = JC_NO_ERROR;
					}
				}
				else {
					// No value or hash means this is an improperly formed request.
					$resource['_'] = JC_INVALID_REQUEST;
				}
			}
			else {
				// Specified uri does not exist.
			    $resource['_'] = HTTP_NOT_FOUND;
			}

			$batchResponse[$uri] = $resource;
		}
	}
	catch (Exception $e) {
	//	echo "Couldn't connect to Redis";
		$batchResponse['_'] = JC_DB_CONNECT_ERROR.' Could not connect to database';
		$batchResponse['desc'] = $e->getMessage();
	}



	//error_log(print_r($batchResponse, 1));

	// This should be the only thing we echo on this page.
	echo json_encode($batchResponse);
	exit(0);
}

ajaxGateway();



