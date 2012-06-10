<?php

// Convenient dump.
//
function _d($arg) {
	var_dump($arg);
}


// Simple function chain class.
//
class FunctionChain {

	private $_ptr = 0;
	public $chain = array();

	public function __construct($c) {
		if (is_array($c)) 
			$this->chain = $c;
		else
            throw new Exception("Function chain must be initialized to an array of anonymous functions.");
	}

	public function next() {
		$this->_ptr++;
		$c = $this;

		if ($this->_ptr < count($this->chain)) {
			if (array_key_exists($this->_ptr, $this->chain)) {
				$this->chain[$this->_ptr]($c);
			}
		}
	}

	public function run() {
		$this->_ptr = 0;
		$chain = $this;
		$this->chain[$this->_ptr]($chain);
	}

	public function reset() {
		$this->_ptr = 0;
	}

	public function removeLink($linkIndex) {
		array_splice($this->chain, $linkIndex, 1);
	}
}


// Inline text element.
//
class InlineText extends FunctionChain {
	public $className, $text;

	public function __construct() {
		parent::__construct(array(
			// Element.
			function($chain) {
				?><span class="<?php $chain->next(); ?>"><?php $chain->next(); ?></span><?php 
			}
			// CSS class.
			,function($chain) {
				echo 'class-name';
			}
			// Text content.
			,function($chain) {
				echo '[text]';
			}
		));

		$this->className = &$this->chain[1];
		$this->text = &$this->chain[2];
	}
}

// HTML 5 document chain.
//
class HtmlDoc extends FunctionChain {
	public $title, $desc, $author, $header, $main, $footer;

	public function __construct() {
		parent::__construct(array(
			function($chain) {
				?><!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php $chain->next(); ?></title>
	<meta name="description" content="<?php $chain->next(); ?>">
	<meta name="author" content="<?php $chain->next(); ?>">

	<meta name="viewport" content="width=device-width">

	<link rel="stylesheet" href="css/4676e5a.css">

	<script src="js/libs/modernizr-2.5.3.min.js"></script>
</head>
<body>
	<header><?php $chain->next(); ?></header>
	<div role="main"><?php $chain->next(); ?></div>
	<footer><?php $chain->next(); ?></footer>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>

	<!-- scripts concatenated and minified via ant build script-->
	<script src="js/a7f84a0.js"></script>
	<!-- end scripts-->

	<script>
		var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>

</body>
</html>
<?php
			}
			// Title.
			,function($chain) {
				echo 'Untitled';
			}
			// Description.
			,function($chain) {
			}
			// Author.
			,function($chain) {
			}
			// Header.
			,function($chain) {
			}
			// Main.
			,function($chain) {
			}
			// Footer.
			,function($chain) {
			}
		));

		$this->title = &$this->chain[1];
		$this->desc = &$this->chain[2];
		$this->author = &$this->chain[3];
		$this->header = &$this->chain[4];
		$this->main = &$this->chain[5];
		$this->footer = &$this->chain[6];
	}
}





