<?php

include('inc.php');

$doc = new HtmlDoc();

$doc->js = function() {
	?>
	<script src="js/libs/json3.min.js"></script>
	<script src="js/libs/knockout-2.1.0.js"></script>
	<script src="js/libs/knockout.mapping-latest.js"></script>
	<?php
};

$doc->title = function($chain) {
	echo 'Network Bindings';
};

$doc->desc = $doc->title;

$doc->author = function($chain) {
	echo 'Ryan Barry';
};

$doc->header = function($chain) {
	?>
	<section class="box">
		<h1>Hello, World!</h1>
	</section>
	<?php
};

$doc->run();







