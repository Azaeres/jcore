<?php

include('inc.php');

$doc = new HtmlDoc();

$doc->title = function($chain) {
	echo 'Function Chains';
};

$doc->author = function($chain) {
	echo 'Ryan Barry';
};

$doc->desc = function($chain) {
	echo 'Function Chains';
};

$doc->header = function($chain) {
?>
<section class="box">
	<h1>Hello, World!</h1>
</section>
<?php
};

$doc->run();







