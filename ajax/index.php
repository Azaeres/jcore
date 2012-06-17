<?php

include('../inc.php');

define('JC_NO_ERROR', 0);
define('JC_DB_CONNECT_ERROR', 1);
define('HTTP_NOT_FOUND', 404);


/*
 * Sanitizes res id.
 *
 * Removes all characters except letters, digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=
 */
$resId = filter_input(INPUT_GET | INPUT_POST, "res", FILTER_SANITIZE_URL);
$batchPullRequest = json_decode($_POST['pull']);
//error_log(print_r($batchPullRequest, 1));

$batchResponse = array();

try {
	require "../predis/autoload.php";
	Predis\Autoloader::register();

	$redis = new Predis\Client();

//	$redis = new Predis\Client(array(
//		"scheme" => "tcp",
//		"host" => "127.0.0.1",
//		"port" => 6379));

	//	$redis->set('/hello-world', 'Hello, jCore!');

    // "Successfully connected to Redis";


	// Since we connect to default setting localhost and 6379 port there is no need for extra configuration. 
	// If not then you can specify the scheme, host and port to connect as an array to the constructor.
	// 
	require_once("sha256.inc.php");


	foreach ($batchPullRequest as $uri => $pullRequest) {
		$resource = array();

		// Look up uri in redis.
		
		$prefix = '/jc/resources';
		$resKey = $prefix.$uri;

		if ($redis->exists($resKey)) {
			$value = $redis->get($resKey);
			$resource['value'] = $value;

			$shaStr = hash('sha256', $value);
			error_log($shaStr);
		}
		else {
			// Specified uri does not exist.
		    $resource['error'] = HTTP_NOT_FOUND;
		}

	//	array_push($batchResponse, $resource);
		$batchResponse[$uri] = $resource;
	}
}
catch (Exception $e) {
//	echo "Couldn't connect to Redis";
	$batchResponse['error'] = JC_DB_CONNECT_ERROR;
	$batchResponse['desc'] = $e->getMessage();
}



//error_log(print_r($batchResponse, 1));

// This should be the only thing we echo on this page.
echo json_encode($batchResponse);
exit(0);





