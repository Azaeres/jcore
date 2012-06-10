<?php

include('../inc.php');

date_default_timezone_set('America/Los_Angeles');
$currentDate = getdate();
//_d($today);

$json = json_encode($currentDate);

echo $json;

exit(0);


/*
$json = '';
if (isset($_POST['tasks'])) {
	$json = $_POST['tasks'];

	echo '<pre>';

	//echo 'Post: '.print_r($json, 1);
	$decodedJson = json_decode($json, true);

	echo print_r($decodedJson, 1);

	echo '</pre>';
}
else {
	echo '[{"title":"Wire the money to Panama","isDone":true},{"title":"Get hair dye, beard trimmer, dark glasses and \"passport\"","isDone":false},{"title":"Book taxi to airport","isDone":false},{"title":"Arrange for someone to look after the cat","isDone":false}]';
}


*/