<?php

include('../inc.php');


$resId = filter_input(INPUT_GET | INPUT_POST, "res", FILTER_SANITIZE_NUMBER_INT);

$json = '';

$resources = array(
	'10' => function() {
		// Returns the current server time.
		date_default_timezone_set('America/Los_Angeles');
		return getdate();
	}
);

$resource = NULL;

if (isset($resources[$resId])) {
	$resource = $resources[$resId]();
}

echo json_encode($resource);
exit(0);





