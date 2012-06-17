<?php

include('inc.php');

//
// After building, follow these steps:
//
// 1. Set DEBUG to 0 here.
define('DEBUG', 1);

// 2. Search for where DEBUG is used and follow the instructions there.
//


$doc = new HtmlDoc();

if (!DEBUG) {
	// Replace XXXX below with the name of your built css file.
	$doc->css = function($chain) {
		?>
			<link rel="stylesheet" href="css/XXXX.css">
		<?php
	};
}

$defaultJs = $doc->js;
$doc->js = function($chain) use ($defaultJs) {
	?>
	<script src="js/libs/json3.min.js"></script>
	<script src="js/libs/jshash-2.2/sha256-min.js"></script>
<?php //	<script src="js/libs/knockout-2.1.0.js"></script> ?>
	<script src="js/libs/knockout-2.1.0.debug.js"></script>
	<script src="js/libs/knockout.mapping-2.1.2.js"></script>
	<script src="js/libs/jcore-client-0.0.1.js"></script>
	<?php

	if (!DEBUG) {
		// Replace XXXX below with the name of your built js file.
		?>
			<script src="js/XXXX.js"></script>
		<?php
	}
	else {
		$defaultJs($chain);
	}
};

$doc->title = function($chain) {
	echo 'jCore v0.0.1';
};

$doc->desc = $doc->title;

$doc->author = function($chain) {
	echo 'Ryan Barry';
};

$doc->main = function($chain) {

/*
		Server time: <span data-bind='text: datetime.month'>Syncing...</span> <span data-bind='text: datetime.mday'></span> <span data-bind='text: datetime.hours'>0</span>:<span data-bind='text: datetime.minutes'>00</span>:<span data-bind='text: datetime.seconds'>00</span>.

 */
	?>
	<div class="box">
		<code data-bind="text: resource.text"></code><br/>
		<code data-bind="text: test.foo"></code>
	</div>
	<code class="debug" data-bind="text: ko.toJSON(viewModel)"></code>
	<?php
};

$doc->run();







