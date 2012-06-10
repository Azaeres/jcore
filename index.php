<?php

include('inc.php');

$doc = new HtmlDoc();

$doc->js = function() {
	?>
	<script src="js/libs/json3.min.js"></script>
	<script src="js/libs/jcore-client-0.0.1.js"></script>
	<script src="js/libs/knockout-2.1.0.js"></script>
	<script src="js/libs/knockout.mapping-latest.js"></script>
	<?php
};

$doc->title = function($chain) {
	echo 'jCore v0.1';
};

$doc->desc = $doc->title;

$doc->author = function($chain) {
	echo 'Ryan Barry';
};

$doc->main = function($chain) {
	?>
	<div class="box">
		Server time: <span data-bind='text: datetime.month'></span> <span data-bind='text: datetime.mday'></span> <span data-bind='text: datetime.hours'>0</span>:<span data-bind='text: datetime.minutes'>00</span>:<span data-bind='text: datetime.seconds'>00</span>.
	</div>
	<?php
};

$doc->run();







