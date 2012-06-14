<?php

include('../inc.php');


/*
 * Sanitizes res id.
 *
 * Removes all characters except letters, digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=
 */

$resId = filter_input(INPUT_GET | INPUT_POST, "res", FILTER_SANITIZE_URL);



define('JC_NO_ERROR', 0);
define('JC_DB_CONNECT_ERROR', 1);
define('HTTP_NOT_FOUND', 404);
$resource = array('error' => HTTP_NOT_FOUND);

if (!isset($resource['value'])) {
	// Look up uri in redis.
	
	require "../predis/autoload.php";
	Predis\Autoloader::register();

	// Since we connect to default setting localhost
	// and 6379 port there is no need for extra
	// configuration. If not then you can specify the
	// scheme, host and port to connect as an array
	// to the constructor.
	// 
	try {
		$redis = new Predis\Client();

	//	$redis = new Predis\Client(array(
	//		"scheme" => "tcp",
	//		"host" => "127.0.0.1",
	//		"port" => 6379));
	
	    // "Successfully connected to Redis";

		$prefix = 'jcore-uri';
		$resKey = $prefix.$resId;
		$resource['value'] = null;

		if ($redis->exists($resKey)) {

		//	$redis->set('/hello-world', 'Hello, jCore!');
			$value = $redis->get($resKey);

		    $resource['error'] = JC_NO_ERROR;
			$resource['value'] = $value;
		}
		else {
			// Specified uri does not exist.
		    $resource['error'] = HTTP_NOT_FOUND;
		}
	}
	catch (Exception $e) {
	//	echo "Couldn't connect to Redis";
		$resource['error'] = JC_DB_CONNECT_ERROR;
		$resource['desc'] = $e->getMessage();
	}
}

// This should be the only thing we echo on this page.
echo json_encode($resource);
exit(0);





